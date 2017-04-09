<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $guarded = [];
    protected $dates = ['paid_date'];

    static $types = ['Rent' => 'Rent','Fee' => 'Fee','Deposit' => 'Deposit'];

    public function tenant()
    {
    	return $this->belongsTo(Tenant::class);
    }

    public function scopeUndeposited($query)
    {
    	return $query->whereNull('bank_deposit_id');
    }

}
