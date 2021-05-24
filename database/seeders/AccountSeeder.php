<?php

namespace Database\Seeders;

use App\Models\Account;
use App\Models\AccountPaymentProviderProfile;
use App\Models\PaymentMethod;
use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class AccountSeeder extends Seeder
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
                $profile = AccountPaymentProviderProfile::factory()->make();
                $profile->paymentProvider()->associate(PaymentProvider::first());
                $profile->account()->associate($account);
                $profile->save();

                $profile->paymentMethods()->save(
                    PaymentMethod::factory()->make()
                );
            })
            ->count(3)
            ->create();
    }
}
