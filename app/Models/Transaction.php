<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    public function paymentMethods()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
