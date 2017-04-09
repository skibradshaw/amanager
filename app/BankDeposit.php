<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankDeposit extends Model
{
    //
	protected $guarded = [];
	protected $dates = ['deposit_date'];

	public function payments()
	{
		return $this->hasMany(Payment::class);
	}
}
