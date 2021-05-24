<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(PaymentProviderSeeder::class);
        $this->call(AccountSeeder::class);
        $this->call(TransactionSeeder::class);
    }
}
