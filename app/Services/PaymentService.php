<?php

namespace App\Services;

use App\Classes\PaymentDetails;
use App\Models\Transaction;
use App\Models\PaymentProvider;

class PaymentService
{
    /**
     * Get Payment Provider
     *
     * @param string|null $provider
     */
    private function getPaymentProvider(?string $provider = null)
    {
        if (!$provider) {
            $provider = config('payment.default_provider');
        }

        return PaymentProvider::query()
            ->where('name', $provider)
            ->first();
    }

    public function charge(PaymentDetails $charge): Transaction
    {
        $this->getPaymentProvider();

        // Setup payment card if payment method is not attached
        if (!$charge->getPaymentMethod()) {

        }
    }

    public function refund(): Transaction
    {

    }
}
