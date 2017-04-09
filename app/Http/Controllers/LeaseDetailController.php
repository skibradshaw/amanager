<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Lease;
use App\LeaseDetail;
use App\Property;
use Illuminate\Http\Request;

class LeaseDetailController extends Controller
{
    //
    public function showPetRent(Property $property, Apartment $apartment, Lease $lease)
    {
        $lease_details = $lease->details;
        return view('leases.pet_rent',[
        	'title' => 'Manage Pet Rent',
        	'property' => $property,
        	'apartment' => $apartment,
        	'lease' => $lease,
        	'lease_details' => $lease_details
        	]);
    }


    public function storePetRent(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        $input = $request->all();
        foreach($input as $key => $value)
        {
            if($key == 'monthly_pet_rent')
            {
            	foreach($input['monthly_pet_rent'] as $k => $v)
            	{
                    $detail = LeaseDetail::find($k);
	                $detail->monthly_pet_rent = $v;
	                $detail->save();         		
            	}
                //echo $key . ": " . $value . "<br>";
            }
        } 
        return redirect()->back()->with('status','Pet Rent Updated');
        // return $lease->details;
        // return back();
    }

    public function storeSinglePetRent(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        $detail = LeaseDetail::find($request->detail_id);
        $detail->monthly_pet_rent = $request->monthly_pet_rent;
        $detail->save();
        return number_format($detail->monthly_pet_rent);
    }
}
