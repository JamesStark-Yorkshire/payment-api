<?php

namespace App\Classes;

class PaymentCard
{
    private int $number;

    private int $expMonth;

    private int $exp_year;

    private ?int $cvc;

    /**
     * PaymentCard constructor.
     * @param int $number
     * @param int $expMonth
     * @param int $exp_year
     * @param ?int $cvc
     */
    public function __construct(int $number, int $expMonth, int $exp_year, int $cvc = null)
    {
        $this->number = $number;
        $this->expMonth = $expMonth;
        $this->exp_year = $exp_year;
        $this->cvc = $cvc;
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
        return $this->exp_year;
    }

    /**
     * @return int
     */
    public function getCvc(): int
    {
        return $this->cvc;
    }
}
