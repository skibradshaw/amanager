<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Payment extends Model
{
    use LogsActivity;

    protected $guarded = [];
    protected $dates = ['paid_date'];
    protected $logOnlyDirty = true;

    static $types = ['Rent' => 'Rent','Fee' => 'Fee','Security Deposit' => 'Security Deposit'];
    static $methods = ['Cash' => 'Cash', 'Check' => 'Check', 'Credit Card' => 'Credit Card','PayPal' => 'PayPal'];

    public function getDepositedAttribute()
    {
        return !is_null($this->bank_deposit_id);
    }

    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function getAmountInDollarsAttribute()
    {
        return money_format('%.2n', $this->amount/100);
    }

    public function scopeUndeposited($query)
    {
        return $query->whereNull('bank_deposit_id');
    }

    public function scopeDeposited($query)
    {
        return $query->whereNotNull('bank_deposit_id');
    }

    public function scopeRentsAndFees($query)
    {
        $query->where(function ($q) {
            $q->where('payment_type', 'Rent')
                ->orWhere('payment_type', 'Fee');
        });
    }
}
