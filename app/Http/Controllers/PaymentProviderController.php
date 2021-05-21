<?php

namespace App\Http\Controllers;

use App\Models\PaymentProvider;
use Illuminate\Http\Request;

/**
 * @OA\Info(
 *     title="My the best API",
 *     version="1.0.0"
 * )
 */

class PaymentProviderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return response()->json(PaymentProvider::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PaymentProvider  $paymentProvider
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentProvider $paymentProvider)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\PaymentProvider  $paymentProvider
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentProvider $paymentProvider)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PaymentProvider  $paymentProvider
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentProvider $paymentProvider)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PaymentProvider  $paymentProvider
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentProvider $paymentProvider)
    {
        //
    }
}
