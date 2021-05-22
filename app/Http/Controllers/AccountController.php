<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Services\AccountService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AccountController extends Controller
{
    private AccountService $accountService;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
    }

    /**
     *
     * @OA\Get(
     *     path="/account",
     *     description="Account",
     *     @OA\Response(response="default", description="Welcome page")
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $accounts = $this->accountService->getAccounts();

        return response()->json($accounts);
    }

    public function store()
    {
        $paymentAccount = $this->accountService->createAccount();

        return response()->json($paymentAccount, Response::HTTP_CREATED);
    }

    public function show(string $uuid)
    {
        $paymentAccount = $this->accountService->getAccount($uuid);

        return response()->json($paymentAccount);
    }

    public function update(string $uuid)
    {

    }

    public function destroy(string $uuid)
    {
        $paymentAccount = $this->accountService->getAccount($uuid);

        if ($paymentAccount) {
            $paymentAccount->delete();
            return $this->json($paymentAccount, Response::HTTP_ACCEPTED);
        }

        return $this->json(['status' => false], Response::HTTP_NO_CONTENT);
    }
}
