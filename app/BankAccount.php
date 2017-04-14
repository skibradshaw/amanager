<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BankAccount extends Model
{
	protected $guarded = [];

	public function deposits()
	{
		return $this->hasMany(BankDeposit::class);
	}

}
