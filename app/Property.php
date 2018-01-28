<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;

class Property extends Model
{
    use LogsActivity;
    //
    protected $guarded = [];
    protected $logOnlyDirty = true;

    public function getUnpaidRentInDollarsAttribute()
    {
        return money_format('%.2n', $this->unpaidRent()/100);
    }
    public function getUnpaidDepositsInDollarsAttribute()
    {
        return money_format('%.2n', $this->unpaidDeposits()/100);
    }

    public function getUndepositedFundsInDollarsAttribute()
    {
        return money_format('%.2n', $this->undepositedFunds()/100);
    }

    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }

    public function leases()
    {
        return $this->hasManyThrough(Lease::class, Apartment::class, 'property_id', 'apartment_id');
    }

    public function bank_accounts()
    {
        return $this->hasMany(BankAccount::class);
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function unpaidRent()
    {
        $leases = Lease::whereIn('apartment_id', $this->apartments->pluck('id'))->get();
        // dd($this->apartments->pluck('id'));
        // dd($leases);
        $totalUnpaidRent = $leases->reduce(function ($total, $l) {
            return $total + $l->rentBalance();
        }, 0);
        
        return $totalUnpaidRent;
    }

    public function unpaidDeposits()
    {
        $leases = Lease::whereIn('apartment_id', $this->apartments->pluck('id')->toArray())->get();
        // dd($this->apartments->pluck('id'));
        // dd($leases);
        $totalUnpaidDeposits = 0;
        foreach ($leases as $l) {
            $totalUnpaidDeposits += $l->depositBalance();
        }
        return $totalUnpaidDeposits;
    }

    public function undepositedFunds()
    {
        $leases = Lease::whereIn('apartment_id', $this->apartments->pluck('id')->toArray())->get();

        $totalUndeposited = 0;
        foreach ($leases as $l) {
            $totalUndeposited += $l->payments()->undeposited()->sum('amount');
        }
        return $totalUndeposited;
    }

    public function getUndepositedPayments()
    {
        $leases = Lease::whereIn('apartment_id', $this->apartments->pluck('id')->toArray())->get();
        return Payment::whereIn('lease_id', $leases->pluck('id'))->undeposited()->get();
    }
}
