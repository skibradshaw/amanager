<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    protected $dates = ['paid_date'];

    static $types = ['Rent' => 'Rent','Fee' => 'Fee','Deposit' => 'Deposit'];
    static $methods = ['Cash' => 'Cash', 'Check' => 'Check', 'Credit Card' => 'Credit Card','PayPal' => 'PayPal'];

    public function tenant()
    {
    	return $this->belongsTo(Tenant::class);
    }

    public function getAmountInDollarsAttribute()
    {
        return money_format('%.2n',$this->amount/100);

    }

    public function scopeUndeposited($query)
    {
    	return $query->whereNull('bank_deposit_id');
    }

}
