<?php

namespace App\Http\Controllers;

use App\Classes\PaymentCard;
use App\Classes\PaymentDetails;
use App\Models\Account;
use App\Models\PaymentMethod;
use App\Models\Transaction;
use App\Services\AccountService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PaymentController extends Controller
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

    public function index(Request $request)
    {
        $uuid = $request->input('account_uuid');

        $account = $this->accountService->getAccount($uuid);

        $transactions = $this->paymentService->getAccountsTransactions($account);

        return response()->json($transactions);
    }

    public function show(string $uuid)
    {
        // Get Transaction
        $transaction = $this->paymentService->getTransaction($uuid);

        return response()->json($transaction);
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

        // Fetch account
        $account = $this->accountService->getAccount($data['account_uuid']);

        // Create Payment details
        $paymentDetails = PaymentDetails::make($data);

        // Set Payment Method
        if (isset($data['payment_method_uuid'])) {
            $paymentDetails->setPaymentMethod(PaymentMethod::whereUuid($data['payment_method_uuid'])->firstOrFail());
        } else {
            $paymentDetails->setPaymentCard(PaymentCard::make($data['payment_card']));
        }
        $transaction = $this->paymentService->charge($account, $paymentDetails);

        return response()->json($transaction);
    }

    public function refund(Request $request, string $uuid)
    {
        $data = $this->validate($request, [
            'amount' => ['integer', 'min:1']
        ]);

        $transaction = $this->paymentService->getTransaction($uuid);

        try {
            $refund = $this->paymentService->refund($transaction, data_get($data, 'amount'));
        } catch (\Exception $exception) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, $exception->getMessage());
        }

        return response()->json($refund, Response::HTTP_CREATED);
    }
}
