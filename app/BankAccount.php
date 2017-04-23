<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
	protected $guarded = [];

	public function setNameAttribute($value)
	{
		$this->attributes['name'] = ucwords($value);
	}

	public function deposits()
	{
		return $this->hasMany(BankDeposit::class)->orderby('deposit_date','desc');
	}

}
