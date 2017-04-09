<?php 
namespace App\Repositories;

use Carbon\Carbon;

class HelperRepository {

	/**
	 * Calculates the fractional different in months between given dates.
	 * @param  [type] $begindate [description]
	 * @param  [type] $enddate   [description]
	 * @return [type]            [description]
	 */
	public function fractionalMonths($begindate,$enddate)
    {			    
	    $begindate = new \DateTime($begindate);
	    $enddate = new \DateTime($enddate);
		$interval = $enddate->diff($begindate);

		$total_months = ($interval->y * 12) + $interval->m;
		$total_months += number_format($interval->d / 30, 1);
	    
	    // $total_months =  (Carbon::parse($begindate)->diffInMonths(Carbon::parse($enddate)))-1;
	    
	    // //fraction start
	    // $start_mo_days = cal_days_in_month(CAL_GREGORIAN, $begindate->month, $begindate->year);
	    // $total_months += round(($start_mo_days-($begindate->day - 1))/$start_mo_days, 1);

	    // //fraction end
	    // $end_mo_days = cal_days_in_month(CAL_GREGORIAN, $enddate->month, $enddate->year);
	    // $total_months += round($enddate->day/$end_mo_days, 1);
	    return $total_months;   
    }

}