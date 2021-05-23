<?php

namespace App\Services;

use App\Classes\PaymentDetails;
use App\Models\Account;
use App\Models\AccountPaymentProviderProfile;
use App\Models\Transaction;
use App\Models\PaymentProvider;
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

    public function charge(Account $account, PaymentDetails $paymentDetails, string $remark = null): Transaction
    {
        // Setup payment card if payment method is not attached
        if (!$paymentDetails->getPaymentMethod()) {
            $paymentProvider = $this->getPaymentProvider();

            $profile = $this->setUpPaymentProvider($account, $paymentProvider);
        }

        $transaction = $account->transactions()
            ->make([
                'type' => array_search('payment', Transaction::PAYMENT_TYPES),
                'currency' => $paymentDetails->getCurrency(),
                'amount' => $paymentDetails->getAmount(),
                'remark' => $remark,
                'status' => array_search('success', Transaction::PAYMENT_STATUS),
            ]);

        $transaction->paymentMethod()->associate($paymentDetails->getPaymentMethod());

        $transaction->save();

        return $transaction;
    }

    public function refund(Transaction $transaction, ?int $amount = null): Transaction
    {
        $refund = $transaction->replicate(['uuid', 'type', 'status', 'original_transaction_id']);

        $amount = $this->getRefundAmount($transaction, $amount);

        if ($amount <= 0) {
            throw new \Exception('Incorrect refund amount or already refunded');
        }

        $refund->fill([
            'type' => array_search('refund', Transaction::PAYMENT_TYPES),
            'amount' => -abs($amount),
            'remark' => 'Refund: ' . $transaction->remark,
            'status' => array_search('success', Transaction::PAYMENT_STATUS),
        ]);

        $transaction->children()->save($refund);

        $refund->unsetRelation('children');
        $refund->load('parent');

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
     * Setup Payment Provider on account if it wasn't existed
     *
     * @param Account $account
     * @param PaymentProvider $paymentProvider
     * @return AccountPaymentProviderProfile
     */
    public function setUpPaymentProvider(
        Account $account,
        PaymentProvider $paymentProvider
    ): AccountPaymentProviderProfile {
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
