<?php

namespace App\Http\Controllers;

use App\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
	public function index()
	{
		$tenants = Tenant::all();
		return view('tenants.index',['tenants' => $tenants]);
	}

	public function create()
	{
		return view('tenants.create');
	}

    //
	public function store(Request $request)
	{
		$this->validate($request,[
				'firstname' => 'required',
				'lastname' => 'required',
				'email' => 'required | email'
			]);
		$input = $request->all();
		$input['type'] = 'Tenant';
		$tenant = Tenant::create($input);

		return redirect()->route('tenants.show',$tenant)->with('status',$tenant->fullname . " successfully added..nice work!"); 

	}

	public function edit(Tenant $tenant)
	{
		return view('tenants.edit',['tenant' => $tenant]);
	}

	public function update(Tenant $tenant, Request $request)
	{
		// return $request->all();
		
		$tenant->update($request->all());
		return redirect()->route('tenants.index')->with('status',$tenant->full_name . " updated successfully.  Nice work!");
	}
}
