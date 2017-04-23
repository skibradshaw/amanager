<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    //
	protected $guarded = [];
	protected $dates = ['deposit_date'];
	static $types = [1 => 'Rent & Fee Payments',2 => 'Security Deposit Payments'];

	public function getAmountInDollarsAttribute()
	{
		return money_format('%.2n',$this->amount/100);
	}

	public function bank_account()
	{
		return $this->belongsTo(BankAccount::class);
	}

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}

	public function creator()
	{
		return $this->belongsTo(User::class,'user_id');
	}
}
