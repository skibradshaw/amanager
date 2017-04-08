<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Lease;
use App\Property;
use App\Tenant;
use Illuminate\Http\Request;

class LeaseController extends Controller
{
    //
    public function create(Property $property, Apartment $apartment)
    {
        return view('leases.create',[
                'property' => $property,
                'apartment' => $apartment
            ]);
    }

    public function store(Property $property, Apartment $apartment, Request $request)
    {
    	$this->validate($request,[
                'start' => 'required | date',
                'end' => 'required | date',
                'tenants' => 'required | array'
            ]);

    	$lease = Lease::create($request->except('tenants'));
    	$apartment->leases()->save($lease);
    	$tenants = collect($request->input('tenants'));
	
    	$lease->tenants()->attach($tenants->pluck('id'));
    	return $request->all();
    }

    public function show(Property $property, Apartment $apartment, Lease $lease)
    {
        return view('leases.show',[
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


}
