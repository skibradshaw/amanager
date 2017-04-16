<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Lease;
use App\LeaseDetail;
use App\Property;
use App\Repositories\HelperRepository;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    //
    public function create(Property $property, Apartment $apartment)
    {
        return view('leases.create',[
            'title' => 'Create a New Lease: Apartment ' . $apartment->name, 
            'apartment' => $apartment,
            'property' => $property,
            ]);

    }

    public function store(Property $property, Apartment $apartment, Request $request)
    {
    	
        $this->validate($request,[
                'start' => 'required | date',
                'end' => 'required | date',
                'monthly_rent' => 'required|numeric',
                'pet_rent' => 'nullable | numeric',
                'deposit' => 'numeric',
                'pet_deposit' => 'nullable | numeric'
            ]);

        $input = $request->except('tenants');
        $input['start'] = Carbon::parse($input['start']);
        $input['end'] = Carbon::parse($input['end']);
        if(!$apartment->checkAvailability($input['start'],$input['end'])) return redirect()->back()->with('error', 'These dates are not available!')->withInput($request->all());

        //Convert Dollars to Cents for DB Storage
        $input['monthly_rent'] = round(preg_replace('/[^0-9\.\-]/i','', $input['monthly_rent'])*100,0);
        if(!empty($input['pet_rent']))
        {
            $input['pet_rent'] = round(preg_replace('/[^0-9\.\-]/i','', $input['pet_rent'])*100,0);
        } else $input['pet_rent'] = 0;
        
        if(!empty($input['deposit']))
        {
            $input['deposit'] = round(preg_replace('/[^0-9\.\-]/i','', $input['deposit'])*100,0);
        } else $input['deposit'] = 0;

        if(!empty($input['pet_deposit']))
        {
            $input['pet_deposit'] = round(preg_replace('/[^0-9\.\-]/i','', $input['pet_deposit'])*100,0);
        } else $input['pet_deposit'] = 0;
        
    	$lease = $apartment->leases()->create($input);
    	$apartment->leases()->save($lease);
        $this->createLeaseDetails($lease);

        //Add Tenants @TODO: Move to Separate Method
    	// $tenants = collect($request->input('tenants'));	
    	// $lease->tenants()->attach($tenants->pluck('id'));
    	return redirect()->route('leases.show',[$property,$apartment,$lease]);
    }

    public function show(Property $property, Apartment $apartment, Lease $lease)
    {
        $tenants = Tenant::all();
        return view('leases.show',[
                'title' => $property->name . " " . $apartment->name . " Lease",
                'property' => $property,
                'apartment' => $apartment,
                'lease' => $lease,
                'tenants' => $tenants
            ]);
    }

    public function showAddTenant(Property $property, Apartment $apartment, Lease $lease)
    {
        return view('leases.partials.add_tenant',[
                'property' => $property,
                'apartment' => $apartment,
                'lease' => $lease
            ]);
    }

    public function addTenant(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        $tenant = Tenant::find($request->input('tenant_id'));
        $lease->tenants()->attach($tenant);
        return $tenant;
    }

    public function createLeaseDetails(Lease $lease)
    {
        //Create Lease Details
        $start = $lease->start;
        $end = $lease->end;
        $inc = \DateInterval::createFromDateString('first day of next month');
        $p = new \DatePeriod($start,$inc,$end);
        
        foreach($p as $d)
        {
            // echo $p . " - " . $d . "<br>";
            // dd($p);
            $helper = new HelperRepository;
            $d = Carbon::instance($d);
            $lease_detail = new LeaseDetail;
            $lease_detail->month = $d->format('n');
            $lease_detail->year = $d->format('Y');
            // echo $end->month . " " . $end->year . " + " . $d->format('n') . " " . $d->format('Y');
            //If the startdate has the same month and year as the current month, calculate a partial
            if($start->month == $d->format('n') && $start->year == $d->format('Y')) {
                // $multiplier = (date('t',strtotime($d->format('Y-m-d')))-($start->day-1))/date('t',strtotime($d->format('Y-m-d')));
                $multiplier = $helper->fractionalStart($start);
                $lease_detail->start = $start;
                $lease_detail->end = Carbon::parse('last day of ' . $d->format('F') . " " . $d->year);
                // echo "Remaining Days/Total Days in Month (" . date('t',strtotime($d->format('Y-m-d'))) . " - " . ($start->day-1) . "/" .  date('t',strtotime($d->format('Y-m-d'))) . ") Mulitiplier: ";
            }
            //Else If the enddate has the same month and year as this month, calculate for partial          
            elseif($end->month == $d->format('n') && $end->year == $d->format('Y')) {
                // $multiplier = ($end->day)/date('t',strtotime($d->format('Y-m-d')));
                $multiplier = $helper->fractionalEnd($end);
                $lease_detail->start = Carbon::parse('first day of ' . $d->format('F') . " " . $d->year);
                $lease_detail->end = $end;
                // echo "# of Days in Last Month/Total Days in Month (" . ($end->day) . "/" .  date('t',strtotime($d->format('Y-m-d'))) . ") Mulitiplier: ";
            }
            //else calculate a full month
            else {
                //echo '- Full Month';
                $multiplier = 1.0;
                $lease_detail->start = Carbon::parse('first day of ' . $d->format('F') . " " . $d->year);
                $lease_detail->end = Carbon::parse('last day of ' . $d->format('F') . " " . $d->year);

            }
            // echo $multiplier . "<br>";
            $lease_detail->multiplier = $multiplier;
            $lease_detail->monthly_rent = ($lease->monthly_rent*$multiplier);
            $lease_detail->monthly_pet_rent = ($lease->pet_rent*$multiplier);

            $lease->details()->save($lease_detail);
        }

    }

    public function showTerminate(Property $property, Apartment $apartment, Lease $lease)
    {
        return view('leases.terminate',[
            'title' => $lease->apartment->name . ' Lease: ' . $lease->start->format('n/j/Y') . ' - ' . $lease->end->format('n/j/Y'),
            'lease' => $lease,
            'property' => $property,
            'apartment' => $apartment
            ]);
    }

    public function terminate(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        $input = $request->all();
        $helper = new HelperRepository;
        //Set the End Date
        $lease->end = Carbon::parse($input['end']);
        $lease->save();
        
        foreach($lease->details as $detail)
        {
            $detail_first_day = $detail->start;
            $detail_last_day = $detail->end;
            // echo $detail_last_day . ": ";
            if($detail_last_day >= Carbon::parse('first day of ' .$lease->end->format('F') . " " . $lease->end->year))
            {
                if($detail_last_day <= Carbon::parse('last day of ' .$lease->end->format('F') . " " . $lease->end->year))
                {
                    //Modify Mulitiplier on Last Month
                    $multiplier = $helper->fractionalEnd($lease->end);
                    $detail->end = $lease->end;
                    // echo "Modify Multiplier: " . $multiplier . "<br>";
                    $detail->multiplier = $multiplier;
                    $detail->monthly_rent = ($lease->monthly_rent*$multiplier);
                    $detail->monthly_pet_rent = ($lease->pet_rent*$multiplier);
                    $detail->save();
                } else {
                    //Delete Future Lease Details
                    // echo "Delete Future Details<br>";
                    $detail->delete();
                }
            }

        }
        
        return redirect()->route('leases.show',[$property,$apartment,$lease]);        
    }
}
