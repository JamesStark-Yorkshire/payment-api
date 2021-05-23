<?php

namespace App\Classes\PaymentProviders;

use App\Interfaces\PaymentProvider;
use Ramsey\Uuid\Uuid;

class Stripe implements PaymentProvider
{
    /**
     * Setup and configure account on payment provider then return account id [Mock]
     *
     * @param array $payload
     * @return string
     */
    public function setUpAccount(array $payload = []): string
    {
        return Uuid::uuid4();
    }
}
