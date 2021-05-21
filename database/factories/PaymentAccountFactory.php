<?php

namespace Database\Factories;

use App\Model;
use App\Models\PaymentAccount;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaymentAccountFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentAccount::class;

    public function definition(): array
    {
    	return [
//    	    'uuid' => $this->faker->uuid
    	];
    }
}
