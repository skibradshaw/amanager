<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Fee extends Model
{
    //
	protected $guarded = [];
	protected $dates = ['due_date'];
	static $types = ['Miscellaneous' => 'Miscellaneous', 'Late Fee' => 'Late Fee', 'Damage Fee' => 'Damage Fee'];


}
