<?php

use App\Models\Account;

class PaymentControllerTest extends TestCase
{
    public function testChargeWithPaymentMethod()
    {
        $account = Account::First();
        $paymentMethod = $account->paymentMethods()->inRandomOrder()->first();

        $this->json('POST', 'payment', [
            'account_uuid' => $account->uuid,
            'payment_method_uuid' => $paymentMethod->uuid,
            'amount' => 1000, //  Amount of the transaction, in pence.
        ])->seeJsonContains([
            'currency' => 'GBP',
            'status' => 'success',
            'amount' => 1000
        ])->assertResponseOk();
    }

    public function testChargeWithPaymentCard()
    {
        $account = Account::First();

        $this->json('POST', 'payment', [
            'account_uuid' => $account->uuid,
            'payment_card' => [
                'number' => '4242424242424242',
                'exp_month' => '04',
                'exp_year' => '2104',
                'cvc' => '123'
            ],
            'amount' => 1000, //  Amount of the transaction, in pence.
        ])->seeJsonContains([
            'currency' => 'GBP',
            'status' => 'success',
            'amount' => 1000
        ])->assertResponseOk();
    }
}
