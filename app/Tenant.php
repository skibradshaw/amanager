<?php

namespace App;

use App\User;

class Tenant extends User
{
    protected $table = 'users';
    protected $guarded = [];

    public function getFullNameAttribute()
    {
    	return $this->firstname . " " . $this->lastname;
    }

    // Get and Set Phone Fields as presentable and numbers only
    public function getPhoneAttribute($value) 
    {
        return "(".substr($value, 0, 3).") ".substr($value, 3, 3)."-".substr($value,6);
    }
    public function setPhoneAttribute($value) 
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/i', '', trim($value));
    }    

    public function leases()
    {
    	return $this->belongsToMany(Lease::class,'leases_tenants');
    }
}
