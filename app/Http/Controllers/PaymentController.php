<?php

namespace App\Http\Controllers;

use App\Classes\PaymentCard;
use App\Classes\PaymentDetails;
use App\Models\PaymentMethod;
use App\Services\PaymentService;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    private PaymentService $paymentService;

    /**
     * PaymentController constructor.
     */
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }

    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'account_uuid' => ['required', 'uuid'],
            'payment_method_uuid' => ['required_without:payment_card', 'uuid'],
            'payment_card' => ['required_without:payment_method_uuid', 'array'],
            'payment_card.number' => ['required_with:payment_card', 'numeric', 'digits:16'],
            'payment_card.exp_month' => ['required_with:payment_card', 'numeric', 'digits:2'],
            'payment_card.exp_year' => ['required_with:payment_card', 'numeric', 'digits:4'],
            'payment_card.cvc' => ['required_with:payment_card', 'numeric', 'digits:3'],
            'amount' => ['required', 'integer']
        ]);

        $paymentDetails = PaymentDetails::make($data);

        // Set Payment Method
        if (isset($data['payment_method_uuid'])) {
            $paymentDetails->setPaymentMethod(PaymentMethod::whereUuid($data['payment_method_uuid'])->firstOrFail());
        } else {
            $paymentDetails->setPaymentCard(PaymentCard::make($data['payment_card']));
        }

        $this->paymentService->charge($paymentDetails);
    }
}
