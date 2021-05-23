<?php

namespace App\Classes;

use App\Models\PaymentMethod;
use App\Models\PaymentProvider;

class PaymentDetails
{
    private string $currency;

    private int $amount;

    private ?PaymentCard $paymentCard = null;

    private ?PaymentMethod $paymentMethod = null;

    /**
     * Charge constructor.
     * @param int $amount
     * @param string|null $currency
     */
    public function __construct(int $amount, string $currency = null)
    {
        $this->currency = $currency ?? config('payment.default_currency');
        $this->amount = $amount;
    }

    public static function make(array $data): self
    {
        $paymentDetails = new self(
            data_get($data, 'amount')
        );

        return $paymentDetails;
    }

    /**
     * @param PaymentMethod $paymentMethod
     */
    public function setPaymentMethod(PaymentMethod $paymentMethod): void
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return PaymentMethod|null
     */
    public function getPaymentMethod(): ?PaymentMethod
    {
        return $this->paymentMethod;
    }

    /**
     * @return PaymentCard|null
     */
    public function getPaymentCard(): ?PaymentCard
    {
        return $this->paymentCard;
    }

    /**
     * @param PaymentCard $paymentCard
     */
    public function setPaymentCard(PaymentCard $paymentCard): void
    {
        $this->paymentCard = $paymentCard;
    }
}
