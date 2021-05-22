<?php

namespace App\Services;

use App\Models\Account;
use App\Models\PaymentProvider;
use Illuminate\Pagination\LengthAwarePaginator;

class AccountService
{
    public function createAccount(): Account
    {
        return Account::create();
    }

    public function getAccounts(int $perPage = 20): LengthAwarePaginator
    {
        return Account::paginate($perPage);
    }

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

    }

    public function setUpPaymentProvider(Account $account, PaymentProvider $provider)
    {

    }
}
