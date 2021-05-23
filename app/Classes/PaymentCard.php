<?php

namespace App\Classes;

class PaymentCard
{
    private int $number;

    private int $expMonth;

    private int $expYear;

    private ?int $cvc;

    /**
     * PaymentCard constructor.
     * @param int $number
     * @param int $expMonth
     * @param int $expYear
     * @param ?int $cvc
     */
    public function __construct(int $number, int $expMonth, int $expYear, int $cvc = null)
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
     * @return int
     */
    public function getExpMonth(): int
    {
        return $this->expMonth;
    }

    /**
     * @return int
     */
    public function getExpYear(): int
    {
        return $this->expYear;
    }

    /**
     * @return int
     */
    public function getCvc(): int
    {
        return $this->cvc;
    }
}
