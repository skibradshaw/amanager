<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Fee extends Model
{
    use LogsActivity;
    //
	protected $guarded = [];
	protected $dates = ['due_date'];
	protected $logOnlyDirty = true;
	static $types = ['Miscellaneous' => 'Miscellaneous', 'Late Fee' => 'Late Fee', 'Damage Fee' => 'Damage Fee'];

    public function getAmountInDollarsAttribute()
    {
        return money_format('%.2n',$this->amount/100);
    }

}
