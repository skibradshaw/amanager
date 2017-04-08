<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Property;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{

	/**
	 * Display a listing of apartments
	 *
	 * @return Response
	 */
	public function index()
	{
		//$apartments = Apartment::all();
		$apartments = Apartment::where('active','=', 1)->get();
		//return var_dump($apartments);
		$properties = Property::all();
		//$current_lease_id = $apartments->currentLease();
		
		return view('apartments.index', ['apartments' => $apartments,'properties' => $properties,'title' => 'Apartments']);
	}    


	/**
	 * Show the form for creating a new apartment
	 *
	 * @return Response
	 */
	public function create(Property $property_id)
	{
		return view('apartments.create');
	}

	/**
	 * Store a newly created apartment in storage.
	 *
	 * @return Response
	 */
	public function store(Property $property, Request $request)
	{
		$this->validate($request,[
				'name' => 'required',
				'number' => 'required'
			]);

		Apartment::create($request->all());

		return redirect()->route('apartments.index',$property);
	}

    public function show(Property $property,Apartment $apartment)
    {
    	// $apartment = Apartment::find($apartment_id);
    	// $property = Property::find($property_id);

    	return view('apartments.show',[
    			'apartment' => $apartment,
    			'property' => $property,
    		]);
    }

	/**
	 * Show the form for editing the specified apartment.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(Property $property,Apartment $apartment)
	{

		return view('apartments.edit',['property' => $property,'apartment' => $apartment]);
	}

	/**
	 * Update the specified apartment in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(Property $property, Apartment $apartment,Request $request)
	{
		$apartment->update($request->all());

		return redirect()->route('apartments.index',$property);
	}

	/**
	 * Remove the specified apartment from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		Apartment::destroy($id);

		return redirect()->route('apartments.index');
	}

	public function currentLease($id) {
		$apartment = Apartment::find($id);
		//echo $apartment->name;
		//var_dump($apartment->leases);

		foreach($apartment->leases as $lease) {
			echo $lease->created_at;
		}

		
	}
}
