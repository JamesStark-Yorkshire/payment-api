<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentAccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Account::factory()
            ->afterCreating(function ($account) {
                $accountPaymentProvider = $account->paymentProviderProfiles()
                    ->create([
                        'payment_provider_id' => PaymentProvider::first()->getKey(),
                        'external_account_id' => 'dsfds'
                    ]);

                $card = PaymentMethod::create([
                    'account_payment_provider_profile_id' => $accountPaymentProvider->getKey(),
                    'card_type' => 'visa',
                    'last4' => 1223
                ]);

//                $accountPaymentProvider->paymentMethods()
//                    ->create([
//                        'card_type' => 'visa',
//                        'last4' => 1223
//                    ]);
            })
            ->count(3)
            ->create();
    }
}
