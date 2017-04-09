<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $guarded = [];

    // public function setNameAttribute()
    // {
    // 	return $this->attributes['name'] = $this->property->abbreviation . $this->number;
    // }

    public function leases()
    {
    	return $this->hasMany(Lease::class);
    }
    public function property()
    {
    	return $this->belongsTo(Property::class);
    }

    public function checkAvailability($start,$end)
    {
        // \DB::connection()->enableQueryLog();
        $return = false;
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        $leases = $this->leases()->where(function($q) use ($start,$end) {
                        $q->whereBetween('start',[$start,$end])
                            ->orWhereBetween('end',[$start,$end]);
                    })->orWhere(function($q) use ($start,$end) {
                        $q->whereRaw('"' . $start . '" <= end');
                        $q->whereRaw('"' . $end . '" >= start');
                    })
                    ->get();
        
        ($leases->count() == 0) ? $return = true : $return = false;
        return $return;
        
    }
}
