<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    protected $guarded = [];
    protected $dates = ['start','end'];

    public function tenants()
    {
    	return $this->belongsToMany(Tenant::class,'lease_tenants');
    }
}
