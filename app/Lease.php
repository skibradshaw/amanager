<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $guarded = [];
    protected $dates = ['start','end'];

    public function getRentTotalAttribute()
    {
        return $this->details->sum('monthly_rent');
    }

    public function getPetrentTotalAttribute()
    {
        return $this->details->sum('monthly_pet_rent');
    }

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    public function tenants()
    {
    	return $this->belongsToMany(Tenant::class,'lease_tenants');
    }

    public function details()
    {
    	return $this->hasMany(LeaseDetail::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function fees()
    {
        return $this->hasMany(Fee::class);
    }
}
