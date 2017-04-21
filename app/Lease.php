<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    public static function boot() {
            parent::boot();

            // create a event to happen on saving
            static::saving(function($table)  {
                $table->created_by = \Auth::user()->id;
            });
    }




    protected $guarded = [];
    protected $dates = ['start','end'];

    public function getMonthlyRentInDollarsAttribute()
    {
        return money_format('%.2n',$this->monthly_rent/100);
    }

    public function getPetRentInDollarsAttribute()
    {
        return money_format('%.2n',$this->pet_rent/100);
    }

    public function getDepositInDollarsAttribute()
    {
        return money_format('%.2n',$this->deposit/100);
    }

    public function getPetDepositInDollarsAttribute()
    {
        return money_format('%.2n',$this->pet_deposit/100);
    }

    public function getRentTotalInDollarsAttribute()
    {
        return money_format('%.2n',$this->rent_total/100);
    }

    public function getPetRentTotalInDollarsAttribute()
    {
        return money_format('%.2n',$this->petrent_total/100);
    }

    public function getRentTotalAttribute()
    {
        return $this->details->sum('monthly_rent');
    }

    public function getPetrentTotalAttribute()
    {
        return $this->details->sum('monthly_pet_rent');
    }

    public function getRentBalanceInDollarsAttribute()
    {
        return money_format('%.2n',$this->rentBalance()/100);
    }

    public function getDepositBalanceInDollarsAttribute()
    {
        return money_format('%.2n',$this->depositBalance()/100);
    }

    //Relationships
    public function creator()
    {
        return $this->belongsTo(User::class,'created_by');
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

    /**
     * Description: Open Balance is calculated by going through each month up through and including the current month and adding up the amount due.
     * @return decimal for currency
     */
    public function rentBalance($tenant_id = null)
    {
        $amount_due = 0;
        foreach($this->details as $m)
        {
            $lease_mo = Carbon::parse('first day of ' . $m->start->format("F") . ' ' . $m->start->year);
            $current_mo = Carbon::parse('last day of ' . Carbon::now());
            if($lease_mo->lt($current_mo))
            {
                $amount_due += $m->monthdue();             
            }
        }
        $balance = $amount_due-$this->payments()->rentsAndFees()->sum('amount');
        return $balance;

    }

    public function depositBalance()
    {
        // $deposit_amount = $this->leaseDeposits()->sum('amount');
        $deposit_payments = $this->payments()->where('payment_type','Security Deposit')->sum('amount');
        $deposit_amount = $this->deposit+$this->pet_deposit;
        $deposit_balance = $deposit_amount - $deposit_payments;
        return $deposit_balance;
    }    

    public function monthFees($month,$year)
    {
        $start = Carbon::parse($month . '/1/'.$year);
        $end = Carbon::parse('last day of ' . $start->format('F') . " " . $year);
        return $this->fees()->whereBetween('due_date',[$start,$end])->sum('amount');
    }

    /**
     * Active Leases are leases that are currently in place.  Not in the past and not in the future.
     * @return [type] [description]
     */
    public function scopeActive($query)
    {
        return $query->whereRaw("DATE('".Carbon::now()->format('Y-m-d')."') BETWEEN start AND end");
    }
}
