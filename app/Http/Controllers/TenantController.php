<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenant;
use App\Lease;
use App\Property;
use App\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
	public function index(Request $request)
	{
		$property = null;
		$query = Tenant::select();
		if($request->has('property_id'))
		{
			$query = $query->activeProperty($request->input('property_id'));
			$property = Property::find($request->input('property_id'));
		} else $query = $query->active();

		$tenants = $query->get();
		return view('tenants.index',[
			'title' => 'Active Tenants',
			'property' => $property,
			'tenants' => $tenants
			]);
	}

	public function create()
	{
		return view('tenants.create',['title' => 'Create a New Tenant']);
	}

    //
	public function store(StoreTenant $request)
	{
		// return $request->all();
		 
		// $this->validate($request,[
		// 		'firstname' => 'required',
		// 		'lastname' => 'required',
		// 		'email' => 'required | email | unique:users'
		// 	]);
		$input = $request->except('lease_id');
		$input['type'] = 'Tenant';
		$tenant = Tenant::create($input);

		//If a Lease ID is passed - attach Tenant to Lease
		if(!empty($request->input('lease_id')))
		{
			$lease = Lease::find($request->input('lease_id'));
			$lease->tenants()->attach($tenant->id);
			return redirect()->route('leases.show',[$lease->apartment->property,$lease->apartment,$lease])->with('status',$tenant->fullname . " was added to this lease.");
		}

		return redirect()->route('tenants.show',$tenant)->with('status',$tenant->fullname . " successfully added..nice work!"); 

	}

	public function edit(Tenant $tenant)
	{
		return view('tenants.edit',['title' => 'Edit '.$tenant->fullname, 'tenant' => $tenant]);
	}

	public function update(Tenant $tenant, Request $request)
	{
		// return $request->all();
		
		$tenant->update($request->all());
		return redirect()->route('tenants.index')->with('status',$tenant->full_name . " updated successfully.  Nice work!");
	}
}
