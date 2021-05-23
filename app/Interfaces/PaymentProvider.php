<?php

namespace App\Interfaces;

interface PaymentProvider
{
    public function setUpAccount(array $metadata = []): string;
}
