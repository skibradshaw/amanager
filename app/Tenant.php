<?php

namespace App;

use App\User;
use Carbon\Carbon;
use Spatie\Activitylog\Traits\LogsActivity;

class Tenant extends User
{
    use LogsActivity;

    protected $table = 'users';
    protected $guarded = [];
    protected $logOnlyDirty = true;

    public function getFullNameAttribute()
    {
        return $this->firstname . " " . $this->lastname;
    }

    // Get and Set Phone Fields as presentable and numbers only
    public function getPhoneAttribute($value)
    {
        if (!empty($value)) {
            return "(".substr($value, 0, 3).") ".substr($value, 3, 3)."-".substr($value, 6);
        }
        return null;
    }
    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/i', '', trim($value));
    }

    public function setFirstnameAttribute($value)
    {
        $this->attributes['firstname'] = ucwords($value);
    }

    public function setLastnameAttribute($value)
    {
        $this->attributes['lastname'] = ucwords($value);
    }

    public function leases()
    {
        return $this->belongsToMany(Lease::class, 'lease_tenants');
    }

    public function scopeActive($query)
    {
        return $query->whereHas('leases', function ($q) {
            $q->where('end', '>=', Carbon::now());
        });
    }

    public function scopeActiveProperty($query, $property_id)
    {
        return $query->whereHas('leases', function ($q) use ($property_id) {
                $q->where('end', '>=', Carbon::now());
        })->whereHas('leases.apartment', function ($q) use ($property_id) {
            $q->where('property_id', $property_id);
        });
    }

    public function getActiveLeaseAttribute()
    {
        // return $this->leases()->where('end','>=',Carbon::now())->orderby('end','desc')->first();
        return $this->leases->last();
    }
}
