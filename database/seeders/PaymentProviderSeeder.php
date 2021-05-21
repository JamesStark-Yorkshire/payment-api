<?php

namespace Database\Seeders;

use App\Models\PaymentProvider;
use Illuminate\Database\Seeder;

class PaymentProviderSeeder extends Seeder
{
    private $providers = [
        'Stripe' => \App\Classes\PaymentProviders\Stripe::class
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach ($this->providers as $name => $class) {
            PaymentProvider::firstOrCreate(['name' => $name, 'class_name' => $class]);
        }
    }
}
