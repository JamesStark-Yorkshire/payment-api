<?php

namespace App\Classes\PaymentProviders;

use App\Interfaces\PaymentProvider;

class Braintree implements PaymentProvider
{
    public function setUpAccount()
    {
        return;
    }
}
