<?php

namespace App\Services;

use App\Models\Account;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountService
{
    /**
     * Create account
     *
     * @return Account
     */
    public function createAccount(): Account
    {
        return Account::create();
    }

    /**
     * List accounts
     *
     * @param int $perPage
     * @return LengthAwarePaginator
     */
    public function getAccounts(int $perPage = 20): LengthAwarePaginator
    {
        return Account::paginate($perPage);
    }

    /**
     * Get specific account with Payment Methods
     *
     * @param string $uuid
     * @return Account|null
     */
    public function getAccount(string $uuid): ?Account
    {
        $account = Account::whereUuid($uuid)
            ->with(['PaymentMethods'])
            ->firstOrFail();

        if ($account instanceof Account) {
            return $account;
        }

        return null;
    }

    public function setDefaultPaymentMethod()
    {
        //
    }
}
