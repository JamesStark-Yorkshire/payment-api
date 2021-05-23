<?php

namespace App\Classes\PaymentProviders;

use App\Classes\PaymentCard;
use App\Interfaces\PaymentProviderInterface;
use App\Models\AccountPaymentProviderProfile;
use Illuminate\Support\Str;

class Stripe implements PaymentProviderInterface
{
    /**
     * Setup and configure account on payment provider then return provider's account id [Mock]
     *
     * @param array $payload
     * @return string
     */
    public function setUpAccount(array $payload = []): string
    {
        return Str::uuid();
    }

    /**
     * Setup and configure account on payment provider then return provider payment's card id [Mock]
     *
     * @param PaymentCard $paymentCard
     * @return PaymentCard
     */
    public function addPaymentCard(PaymentCard $paymentCard, AccountPaymentProviderProfile $profile): PaymentCard
    {
        $paymentCard->setExternalId(Str::random());

        return $paymentCard;
    }
}
