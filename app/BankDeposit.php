<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    //
	protected $guarded = [];
	protected $dates = ['deposit_date'];
	static $types = [1 => 'Rent & Fee Payments',2 => 'Security Deposit Payments'];

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}
}
