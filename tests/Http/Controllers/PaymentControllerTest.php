<?php

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Response;

class PaymentControllerTest extends TestCase
{
    public function testGetAccountsPayment()
    {
        $account = Account::First();
        $transaction = $account->transactions()->first();

        $this->json('GET', 'payment/', ['account_uuid' => $account->uuid])
            ->seeJsonStructure([
                'current_page',
                'data',
                'first_page_url'
            ])
            ->seeJsonContains([
                'uuid' => $transaction->uuid,
                'amount' => $transaction->amount
            ])
            ->assertResponseOk();
    }

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

    public function testRefund()
    {
        $account = Account::First();
        $paymentMethod = $account->paymentMethods()->inRandomOrder()->first();

        $transaction = Transaction::factory(['amount' => 1000])->make();
        $transaction->paymentMethod()->associate($paymentMethod);
        $transaction->account()->associate($account);
        $transaction->save();

        // Test refund
        $this->json('POST', 'payment/' . $transaction->uuid . '/refund', [
            'amount' => 500, //  Amount of the transaction, in pence.
        ])->seeJsonContains([
            'type' => 'refund',
            'currency' => 'GBP',
            'status' => 'success',
            'amount' => -500
        ])->assertResponseStatus(201);

        // Test failing refund - incorrect amount
        $this->json('POST', 'payment/' . $transaction->uuid . '/refund', [
            'amount' => 1000, //  Amount of the transaction, in pence.
        ])->seeJsonContains(['message' => 'Incorrect refund amount or already refunded.'])
            ->assertResponseStatus(Response::HTTP_BAD_REQUEST);
    }
}
