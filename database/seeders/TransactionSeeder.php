<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Database\Seeder;

class TransactionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $account = Account::first();
        $paymentMethod = $account->paymentMethods()->inRandomOrder()->first();

        Transaction::factory()
            ->afterMaking(function (Transaction $transaction) use ($account, $paymentMethod) {
                $transaction->account()->associate($account);
                $transaction->paymentMethod()->associate($paymentMethod);
            })
            ->count(3)
            ->create();
    }
}
