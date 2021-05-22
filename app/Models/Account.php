<?php

namespace App\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Account extends Model
{
    use HasFactory,
        SoftDeletes,
        GeneratesUuid;

    public function paymentProviderProfiles()
    {
        return $this->hasMany(AccountPaymentProviderProfile::class);
    }

    public function paymentMethods()
    {
        return $this->hasManyThrough(PaymentMethod::class, AccountPaymentProviderProfile::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
