<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccountPaymentProviderProfile extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'external_account_id'
    ];

    public function paymentAccount()
    {
        return $this->belongsTo(Account::class);
    }

    public function paymentProvider()
    {
        return $this->belongsTo(PaymentProvider::class);
    }

    public function paymentMethods()
    {
        return $this->hasMany(PaymentMethod::class);
    }
}
