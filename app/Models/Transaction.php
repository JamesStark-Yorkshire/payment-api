<?php

namespace App\Models;

use Dyrynda\Database\Support\GeneratesUuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Transaction extends Model
{
    use HasFactory,
        GeneratesUuid;

    /**
     * Payment types
     */
    const PAYMENT_TYPE_PAYMENT = 'P';
    const PAYMENT_TYPE_REFUND = 'R';

    /**
     * Payment status
     */
    const PAYMENT_STATUS_SUCCESS = 'S';
    const PAYMENT_STATUS_FAILED = 'F';
    const PAYMENT_STATUS_REFUNDED = 'R';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'type',
        'currency',
        'amount',
        'remark',
        'status',
    ];

    /**
     * Get charged amount
     */
    public function getChargedAttribute()
    {
        if ($this->getOriginal('status') != self::PAYMENT_STATUS_SUCCESS) {
            return 0;
        }

        $amount = $this->amount;

        // Calculate children amount
        if ($childAmount = $this->children->sum('amount')) {
            $amount += $childAmount;
        }

        return $amount;
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class);
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

    public function toJson($options = 0)
    {
        // Convert type and status code
        self::lookUpConst('PAYMENT_TYPES_', $this->type);

        $this->type = self::lookUpConst('PAYMENT_TYPE_', $this->type);
        $this->status = self::lookUpConst('PAYMENT_STATUS_', $this->getTransactionStatus());

        return parent::toJson($options); // TODO: Change the autogenerated stub
    }

    /**
     * Get combined transaction status
     *
     * @return string
     */
    public function getTransactionStatus(): string
    {
        // Calculate children amount
        if ($this->children->where('status', 'R')->isNotEmpty()) {
            return self::PAYMENT_STATUS_REFUNDED;
        }

        return $this->status;
    }

    /**
     * Lookup const
     *
     * @param string $prefix
     * @param string $code
     * @return string|null
     */
    public static function lookUpConst(string $prefix, string $code): ?string
    {
        $refl = new \ReflectionClass(self::class);

        $name = collect($refl->getConstants())
            ->search(function ($value, $key) use ($prefix, $code) {
                return preg_match("/$prefix/", $key) && $value == $code;
            });

        if ($name) {
            $name = preg_replace("/$prefix/", '', $name);

            return Str::lower($name);
        }

        return null;
    }
}
