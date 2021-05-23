<?php

namespace App\Interfaces;

interface PaymentProviderInterface
{
    public function setUpAccount(array $metadata = []): string;
}
