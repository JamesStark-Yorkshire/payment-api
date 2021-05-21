<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPaymentProvider extends Model
{
    public function account()
    {
        return $this->belongsTo(PaymentAccount::class);
    }

    public function provider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }
}
