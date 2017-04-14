<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LeaseDetail extends Model
{
    protected $dates = ['start','end'];

    public function lease()
    {
    	return $this->belongsTo(Lease::class);
    }

    public function getMonthlyRentInDollarsAttribute()
    {
        return money_format('%.2n',$this->monthly_rent/100);
    }

    public function getMonthlyPetRentInDollarsAttribute()
    {
        return money_format('%.2n',$this->monthly_pet_rent/100);
    }

    public function getMonthDueInDollarsAttribute()
    {
        return money_format('%.2n',$this->monthDue()/100);
    }

    public function getMonthBalanceInDollarsAttribute()
    {
        return money_format('%.2n',$this->monthBalance()/100);
    }

    public function getNameAttribute()
    {
    	return ucwords($this->end->format('M y'));
    }

    /**
     * Returns the total of all payments within the start and end dates of this detail.  If a tenant_id is supplied, only payments for that tenant are included.
     * @return [type] [description]
     */
    public function rentPayments($tenant_id = null)
    {
        $query = $this->lease->payments();
        if(!is_null($tenant_id))
        {
            $query = $query->where('tenant_id',$tenant_id);
        }
        //BUSINESS RULE: Any rent payments recieved outside of the Lease dates should be applied to the first month.
        if($this->start->eq($this->lease->start))
        {
            $query = $query->where(function($q){
                $q->whereBetween('paid_date',[$this->start,$this->end])
                    ->orwhere('paid_date','<',$this->lease->start)
                    ->orWhere('paid_date','>',$this->lease->end);
            });
            // $query = $query->where('paid_date',Carbon::now());
        } else $query = $query->whereBetween('paid_date',[$this->start,$this->end]);

        return $query->sum('amount');
    }

    public function monthBalance()
    {
    	$balance = 0;
    	$amount_due = ($this->monthly_rent + $this->monthly_pet_rent) + $this->lease->fees()->whereBetween('due_date', [$this->start,$this->end])->sum('amount');
    	$balance = $amount_due-$this->rentPayments();
    	return $balance;
    }

    public function monthDue()
    {
	 	$d_start = $this->start;
	 	$d_end = $this->end;

	 	$amount_due = ($this->monthly_rent + $this->monthly_pet_rent) + $this->lease->fees()->whereBetween('due_date', [$d_start,$d_end])->sum('amount');
	 	//$paid_to_date = $this->payments()->whereBetween('paid_date',[$d_start,$d_end])->sum('amount');
	 	// $paid_to_date = $this->lease->payments()->where('payment_type','<>', 'Deposit')->sum('amount');
	 	// foreach ($this->lease->tenants as $t) {
	 	// 	$paid_to_date += $this->monthAllocation($t->id);
	 	// }
	 	// $balance = $amount_due-$paid_to_date;
	 	return $amount_due;    	
    }
}
