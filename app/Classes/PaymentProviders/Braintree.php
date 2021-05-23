<?php

namespace App\Classes\PaymentProviders;

use App\Interfaces\PaymentProviderInterface;

class Braintree implements PaymentProviderInterface
{
    public function setUpAccount(array $payload = []): string
    {
        return;
    }
}
