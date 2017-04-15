<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    //
    protected $guarded = [];

    public function apartments()
    {
    	return $this->hasMany(Apartment::class);
    }

    public function scopeActive($query)
    {
    	return $query->where('active',1);
    }

    public function leases()
    {
        return $this->hasManyThrough(Lease::class,Apartment::class,'property_id','apartment_id');
    }
}
