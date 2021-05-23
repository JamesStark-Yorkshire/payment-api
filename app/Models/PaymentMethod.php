<?php

namespace App\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentMethod extends Model
{
    use SoftDeletes,
        GeneratesUuid;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'account_payment_provider_profile_id',
        'external_id',
        'card_type',
        'last4'
    ];

    public function accountPaymentProvider()
    {
        return $this->belongsTo(AccountPaymentProviderProfile::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }
}
