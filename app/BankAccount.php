<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;


class BankAccount extends Model
{
	use LogsActivity;

	protected $guarded = [];
	protected $logOnlyDirty = true;

	public function setNameAttribute($value)
	{
		$this->attributes['name'] = ucwords($value);
	}

	public function property()
	{
		return $this->belongsTo(Property::class);
	}

	public function deposits()
	{
		return $this->hasMany(BankDeposit::class)->orderby('deposit_date','desc');
	}

}
