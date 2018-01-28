<?php
namespace App\Repositories;

use Carbon\Carbon;

class HelperRepository
{

    /**
     * Calculates the fractional different in months between given dates.
     * @param  [type] $begindate [description]
     * @param  [type] $enddate   [description]
     * @return [type]            [description]
     */
    public function fractionalMonths($begindate, $enddate)
    {
        $begindate = Carbon::parse($begindate);
        $enddate = Carbon::parse($enddate);
        $fractionalStart = 0;
        $fractionalEnd = 0;
        $monthsInBetween = 0;
        $total_months = 0;
        //Total days in the first month minus the current day divided by the total days in the month
        $fractionalStart = $this->fractionalStart($begindate);
        
        if ($enddate->lte(Carbon::parse('last day of '.$begindate->format('F')." ".$begindate->year))) {
            $fractionalEnd = $this->fractionalEnd($enddate)-1;
        } else {
            //Day of the last month divided by the total days in the month
            $fractionalEnd = $this->fractionalEnd($enddate);
            //Count of the months starting with month after $begindate and ending with month before $enddate
            $monthsInBetween = Carbon::parse('last day of '.$begindate->format('F')." ".$begindate->year)->addDay()->diffInMonths(Carbon::parse('first day of '.$enddate->format('M').' '.$enddate->year));
        }
        $total_months = $fractionalStart+$monthsInBetween+$fractionalEnd;

     //    $begindate = new \DateTime($begindate);
     //    $enddate = new \DateTime($enddate);
        // $interval = $enddate->diff($begindate);

        // $total_months = ($interval->y * 12) + $interval->m;
        // $total_months += number_format($interval->d / 30, 2);
        
        return round($total_months, 2);
    }

    public function fractionalStart($date)
    {
        $date = Carbon::parse($date);
        return round((Carbon::parse('last day of '.$date->format('F')." ".$date->year)->day-($date->day-1))/Carbon::parse('last day of '.$date->format('F')." ".$date->year)->day, 2);
    }

    public function fractionalEnd($date)
    {
        $date = Carbon::parse($date);
        return round($date->day/Carbon::parse('last day of '.$date->format('F')." ".$date->year)->day, 2);
    }
}
