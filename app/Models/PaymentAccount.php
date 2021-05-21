<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class PaymentAccount extends Model
{
    use HasFactory;

    /**
     * Boot function from Laravel.
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate UUID
            $model->uuid = Str::uuid()->toString();
        });
    }

    public function providers()
    {
        return $this->hasMany(AccountPaymentProvider::class);
    }
}
