<?php

namespace Database\Factories;

use App\Models\PaymentMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentMethodFactory extends Factory
{
    protected $model = PaymentMethod::class;

    public function definition(): array
    {
        return [
            'external_id' => 'pm_' . $this->faker->sha1,
            'card_type' => 'visa',
            'last4' => $this->faker->randomNumber(4)
        ];
    }
}
