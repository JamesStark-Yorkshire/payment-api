<?php

namespace App\Services;

use App\Classes\PaymentCard;
use App\Classes\PaymentDetails;
use App\Models\Account;
use App\Models\AccountPaymentProviderProfile;
use App\Models\PaymentMethod;
use App\Models\PaymentProvider;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class PaymentService
{
    /**
     * Get Payment Provider
     *
     * @param string|null $provider
     * @return PaymentProvider
     */
    private function getPaymentProvider(?string $provider = null): PaymentProvider
    {
        if (!$provider) {
            $provider = config('payment.default_provider');
        }

        return PaymentProvider::query()
            ->where('name', $provider)
            ->first();
    }

    /**
     * Get transaction
     *
     * @param array $filter
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getTransactions(array $filter = [], int $perPage = 20): LengthAwarePaginator
    {
        return Transaction::query()
            ->whereNull('parent_id')
            ->when(!empty($filter), function (Builder $query) use ($filter) {
                // Account Filter
                if ($accountUuid = data_get($filter, 'account_uuid')) {
                    $query->whereHas('account', function (Builder $query) use ($accountUuid) {
                        $query->where('uuid', $accountUuid);
                    });
                }
                // Payment status filter (Does not support refunded payment)
                if ($status = data_get($filter, 'status')) {
                    $constName = Transaction::class . '::PAYMENT_STATUS_' . Str::upper($status);
                    if (defined($constName)) {
                        $status = constant($constName);
                    }

                    $query->where('status', $status);
                }
            })
            ->paginate($perPage);
    }

    public function charge(Account $account, PaymentDetails $paymentDetails, string $remark = null): Transaction
    {
        // Setup payment card if payment method is not attached
        if (!$paymentDetails->getPaymentMethod()) {
            $paymentProvider = $this->getPaymentProvider();

            $profile = $this->setUpPaymentProvider($account, $paymentProvider);
            $paymentMethod = $this->setUpPaymentMethod($paymentDetails->getPaymentCard(), $profile);
            $paymentDetails->setPaymentMethod($paymentMethod);
        }

        $transaction = $account->transactions()
            ->make([
                'type' => Transaction::PAYMENT_TYPE_PAYMENT,
                'currency' => $paymentDetails->getCurrency(),
                'amount' => $paymentDetails->getAmount(),
                'remark' => $remark,
                'status' => Transaction::PAYMENT_STATUS_SUCCESS
            ]);

        $transaction->paymentMethod()->associate($paymentDetails->getPaymentMethod());

        $transaction->save();
        $transaction->append('charged');

        return $transaction;
    }

    public function refund(Transaction $transaction, ?int $amount = null): Transaction
    {
        $refund = $transaction->replicate(['uuid', 'type', 'status', 'original_transaction_id']);

        $amount = $this->getRefundAmount($transaction, $amount);

        if ($amount <= 0) {
            throw new \Exception('Incorrect refund amount or already refunded.');
        }

        $refund->fill([
            'type' => Transaction::PAYMENT_TYPE_REFUND,
            'amount' => -abs($amount),
            'remark' => 'Refund: ' . $transaction->remark,
            'status' => Transaction::PAYMENT_STATUS_SUCCESS,
        ]);

        $transaction->children()->save($refund);
        $transaction->save();

        $refund->load('parent');
        $refund->makeHidden('children');

        return $refund;
    }

    /**
     * Get specific transaction
     *
     * @param string $uuid
     * @return Transaction|null
     */
    public function getTransaction(string $uuid): ?Transaction
    {
        $transaction = Transaction::whereUuid($uuid)
            ->with('children')
            ->firstOrFail()
            ->append('charged');

        if ($transaction instanceof Transaction) {
            return $transaction;
        }

        return null;
    }

    /**
     * Get Payment Method
     *
     * @param string $uuid
     * @return PaymentMethod
     */
    public function getPaymentMethod(string $uuid): ?PaymentMethod
    {
        $paymentMethod = PaymentMethod::whereUuid($uuid)
            ->firstOrFail();

        if ($paymentMethod instanceof PaymentMethod) {
            return $paymentMethod;
        }

        return null;
    }

    /**
     * Setup Payment Provider on account if it wasn't existed
     *
     * @param Account $account
     * @param PaymentProvider|null $paymentProvider
     * @return AccountPaymentProviderProfile
     */
    public function setUpPaymentProvider(
        Account $account,
        PaymentProvider $paymentProvider = null
    ): AccountPaymentProviderProfile {

        if (!$paymentProvider) {
            $paymentProvider = $this->getPaymentProvider();
        }

        $profile = $account->paymentProviderProfiles()
            ->where('payment_provider_id', $paymentProvider->getKey())
            ->first();

        // Setup account on Payment Provider
        if (!$profile) {
            $profile = $account->paymentProviderProfiles()
                ->create([
                    'external_account_id' => $paymentProvider->getProviderInstance()->setUpAccount()
                ]);
        }

        return $profile;
    }

    public function setUpPaymentMethod(
        PaymentCard $paymentCard,
        AccountPaymentProviderProfile $profile
    ): ?PaymentMethod {
        $instance = $profile->paymentProvider->getProviderInstance();

        $card = $instance->addPaymentCard($paymentCard, $profile);

        if ($paymentCard->isCreated()) {
            $paymentMethod = $profile->paymentMethods()
                ->create([
                    'external_id' => $card->getExternalId(),
                    'card_type' => 'debit',
                    'last4' => $paymentCard->getCvc()
                ]);

            return $paymentMethod;
        }

        return null;
    }

    /**
     * Get refunded amount
     *
     * @param Transaction $transaction
     * @param int|null $amount
     * @return int
     */
    private function getRefundAmount(Transaction $transaction, ?int $amount = null): int
    {
        // Refund amount cannot be greater than the transaction charged amount
        if (!$amount) {
            return $transaction->charged;
        }

        if ($transaction->charged >= $amount) {
            return $amount;
        }

        return 0;
    }
}
