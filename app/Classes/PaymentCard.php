<?php

namespace App\Classes;

class PaymentCard
{
    /**
     * @var string|null Provider's Payment Method ID
     */
    private ?string $externalId;

    private string $number;

    private string $expMonth;

    private string $expYear;

    private ?string $cvc;

    /**
     * PaymentCard constructor.
     * @param string $number
     * @param string $expMonth
     * @param string $expYear
     * @param string|null $cvc
     */
    public function __construct(string $number, string $expMonth, string $expYear, string $cvc = null)
    {
        $this->number = $number;
        $this->expMonth = $expMonth;
        $this->expYear = $expYear;
        $this->cvc = $cvc;
    }

    public static function make(array $data): self
    {
        $paymentCard = new self(
            data_get($data, 'number'),
            data_get($data, 'exp_month'),
            data_get($data, 'exp_year'),
            data_get($data, 'cvc'),
        );

        return $paymentCard;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getExpMonth(): string
    {
        return $this->expMonth;
    }

    /**
     * @return string
     */
    public function getExpYear(): string
    {
        return $this->expYear;
    }

    /**
     * @return string
     */
    public function getCvc(): string
    {
        return $this->cvc;
    }

    public function getLast4(): string
    {
        return substr($this->number, -4);
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    /**
     * @param string|null $externalId
     */
    public function setExternalId(?string $externalId): void
    {
        $this->externalId = $externalId;
    }

    /**
     * Return if Payment Card is created on provider
     *
     * @return bool
     */
    public function isCreated(): bool
    {
        return isset($this->externalId);
    }
}
