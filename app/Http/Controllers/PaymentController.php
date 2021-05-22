<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function create(Request $request)
    {
        $this->validate($request, [
            'account_uuid' => 'uuid',
            'payment_method_uuid'
        ]);


    }
}
