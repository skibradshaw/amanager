<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Apartment extends Model
{
    protected $guarded = [];

    public function leases()
    {
    	return $this->hasMany(Lease::class);
    }
}
