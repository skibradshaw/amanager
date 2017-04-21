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
    	return $this->hasMany(Lease::class,'apartment_id');
    }
    public function property()
    {
    	return $this->belongsTo(Property::class);
    }

    public function scopeVacant($query)
    {
        return $query->whereRaw("(SELECT COUNT(id) FROM leases WHERE apartment_id = apartments.id AND  end >= now()) = 0");
    }

    public function scopeNotVacant($query)
    {
        return $query->whereRaw("(SELECT COUNT(id) FROM leases WHERE apartment_id = apartments.id AND  end >= now()) > 0");
    }

    public function checkAvailability($start,$end)
    {
        // \DB::connection()->enableQueryLog();
        $return = false;
        $start = Carbon::parse($start);
        $end = Carbon::parse($end);
        // $leases = $this->leases()->where(function($q) use ($start,$end){
        //     $q->whereBetween('start',[$start,$end])

        // })
        $leases = \App\Lease::where('apartment_id',$this->id)->where(function($q) use ($start,$end) {
                        $q->whereRaw('"' . $start . '" <= end');
                        $q->WhereRaw('"' . $end . '" >= start');
                    })
                    ->get();
        
        (count($leases) == 0) ? $return = true : $return = false;
        // dd($this->leases);
        return $return;
        
    }

    public function currentLease() {
        $lease = $this->leases()->whereRaw("DATE('".Carbon::now()."') BETWEEN start AND end")->first();
        // (empty($lease)) ? $lease = $this->leases()->whereRaw("DATE('".Carbon::now()."') <= start")->first() : null;
        return $lease;
    }
    public function nextLease() {
        $lease = $this->leases()->where('end','>',Carbon::now())->orderBy('end','desc')->first();
        return $lease;
    }

    public function pastLeases()
    {
        $lease = $this->leases()->where('end','<',Carbon::now())->orderBy('end','desc')->get();
        return $lease;
    }
}
