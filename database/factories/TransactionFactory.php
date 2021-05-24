<?php

namespace Database\Factories;

use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition(): array
    {
        return [
            'type' => Transaction::PAYMENT_TYPE_PAYMENT,
            'amount' => $this->faker->randomNumber(),
            'remark' => $this->faker->realText(),
            'status' => Transaction::PAYMENT_STATUS_SUCCESS
        ];
    }

    /**
     * Indicate that the transaction is failed.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function failed(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'status' => Transaction::PAYMENT_STATUS_FAILED,
            ];
        });
    }
}
