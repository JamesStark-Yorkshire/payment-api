<?php

use App\Models\Account;
use App\Models\Transaction;
use Illuminate\Http\Response;
use App\Models\PaymentMethod;

class PaymentMethodControllerTest extends TestCase
{
    /**
     * Test Create Payment Method
     */
    public function testCreatePaymentMethod()
    {
        $account = Account::First();

        $this->json('POST', 'payment_method', [
            'account_uuid' => $account->uuid,
            'number' => '4242424242424242',
            'exp_month' => '04',
            'exp_year' => '2104',
            'cvc' => '123'
        ])->seeJsonStructure([
            'uuid',
            'external_id',
            'card_type',
            'last4'
        ])->assertResponseStatus(Response::HTTP_CREATED);
    }

    public function testDeletePaymentMethod()
    {
        $paymentMethod = PaymentMethod::first();

        $this->json('DELETE', 'payment_method/' . $paymentMethod->uuid)
            ->seeJsonStructure([
                'uuid',
                'external_id',
                'card_type',
                'last4'
            ])
            ->assertResponseStatus(Response::HTTP_ACCEPTED);
    }
}
