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
     * @OA\Get(
     *     path="/account",
     *     description="List account",
     *     tags={"account"},
     *     @OA\Response(response="default", description="List account")
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
     * @OA\Post(
     *     path="/account",
     *     description="Create account",
     *     tags={"account"},
     *     @OA\Response(response="default", description="Create account")
     * )
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function store()
    {
        $paymentAccount = $this->accountService->createAccount();

        return response()->json($paymentAccount, Response::HTTP_CREATED);
    }

    /**
     * @OA\Get(
     *     path="/account/{uuid}",
     *     description="Get account along with their payment methods",
     *     tags={"account"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Account's UUID",
     *         required=true
     *     ),
     *     @OA\Response(response="default", description="Get account")
     * )
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
     * @OA\Delete(
     *     path="/account/{uuid}",
     *     description="Remove account",
     *     tags={"account"},
     *     @OA\Parameter(
     *         name="uuid",
     *         in="path",
     *         description="Account's UUID",
     *         required=true
     *     ),
     *     @OA\Response(response="default", description="Remove account")
     * )
     *
     * @param string $uuid
     * @return mixed
     */
    public function destroy(string $uuid)
    {
        $paymentAccount = $this->accountService->getAccount($uuid);

        if ($paymentAccount) {
            $paymentAccount->delete();
            return response()->json($paymentAccount, Response::HTTP_ACCEPTED);
        }

        return response()->json(['status' => false], Response::HTTP_NO_CONTENT);
    }
}
