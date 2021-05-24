<?php

namespace Database\Factories;

use App\Models\AccountPaymentProviderProfile;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountPaymentProviderProfileFactory extends Factory
{
    protected $model = AccountPaymentProviderProfile::class;

    public function definition(): array
    {
        return [
            'external_account_id' => 'cus_' . $this->faker->sha1
        ];
    }
}
