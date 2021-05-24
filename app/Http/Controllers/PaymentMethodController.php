<?php

namespace App\Http\Controllers;

use App\Classes\PaymentCard;
use App\Services\AccountService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PaymentMethodController extends Controller
{
    private PaymentService $paymentService;

    private AccountService $accountService;

    /**
     * PaymentController constructor.
     */
    public function __construct(PaymentService $paymentService, AccountService $accountService)
    {
        $this->paymentService = $paymentService;
        $this->accountService = $accountService;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $this->validate($request, [
            'account_uuid' => ['required', 'uuid'],
            'number' => ['required_with:payment_card', 'numeric', 'digits:16'],
            'exp_month' => ['required_with:payment_card', 'numeric', 'digits:2'],
            'exp_year' => ['required_with:payment_card', 'numeric', 'digits:4'],
            'cvc' => ['required_with:payment_card', 'numeric', 'digits:3']
        ]);

        // Fetch account
        $account = $this->accountService->getAccount($data['account_uuid']);

        // Create Payment Method
        $paymentCard = PaymentCard::make($data);
        $profile = $this->paymentService->setUpPaymentProvider($account);
        $paymentMethod = $this->paymentService->setUpPaymentMethod($paymentCard, $profile);

        return response()->json($paymentMethod, Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     *
     * @return Response
     */
    public function show(Request $request, string $uuid)
    {

    }

    /**
     *  Remove the specified resource from storage.
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $uuid)
    {
        $paymentMethod = $this->paymentService->getPaymentMethod($uuid);

        if ($paymentMethod) {
            return response()->json($paymentMethod, Response::HTTP_ACCEPTED);
        }

        return response()->json(['status' => false], Response::HTTP_NO_CONTENT);
    }
}
