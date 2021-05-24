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

    /**
     * Create account
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $paymentAccount = $this->accountService->createAccount();

        return response()->json($paymentAccount, Response::HTTP_CREATED);
    }

    /**
     * Get account
     *
     * @param string $uuid
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(string $uuid)
    {
        $paymentAccount = $this->accountService->getAccount($uuid);

        return response()->json($paymentAccount);
    }

    /**
     * Remove account
     *
     * @param string $uuid
     * @return mixed
     */
    public function destroy(string $uuid)
    {
        $paymentAccount = $this->accountService->getAccount($uuid);

        if ($paymentAccount) {
            $paymentAccount->delete();
            return resposne()->json($paymentAccount, Response::HTTP_ACCEPTED);
        }

        return resposne()->json(['status' => false], Response::HTTP_NO_CONTENT);
    }
}
