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
	    
	    return $total_months;   
    }

}