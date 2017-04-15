<?php

namespace App\Http\Controllers;

use App\Lease;
use App\Property;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    //

	public function unpaidBalances(Property $property = null)
	{
		$query = Lease::select();

		if(!empty($property->id))
		{
			$query = $query->whereHas('apartment',function($q) use ($property) {
					$q->where('property_id',$property->id);
				});	
		} 
		$leases = $query->get();

		$unpaidRentLeases = $leases->filter(function($l){
			if($l->openBalance()>0) return true;
		});
		$totalUnpaidRent = $unpaidRentLeases->sum(function($l){
			return $l->openBalance();
		});

		// return $unpaidRentLeases;
		$unpaidDepositLeases = $leases->filter(function($l){
			if($l->depositBalance()>0) return true;
		});;
		$totalUnpaidDeposits = $unpaidDepositLeases->sum(function($l){
			return $l->depositBalance();
		});
		// return $unpaidDepositLeases;
		$unpaidLeases = $unpaidRentLeases->merge($unpaidDepositLeases);


		return view('reports.unpaid_balances',[
				'title' => 'Unpaid Balances',
				'unpaidLeases' => $unpaidLeases,
				'totalUnpaidRent' => $totalUnpaidRent,
				'totalUnpaidDeposits' => $totalUnpaidDeposits,
				'property' => $property
			]);
	}
}
