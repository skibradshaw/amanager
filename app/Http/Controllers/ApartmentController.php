<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ApartmentController extends Controller
{

    /**
     * Display a listing of apartments
     *
     * @return Response
     */
    public function index(Property $property)
    {
        $apartments = Apartment::with('property')->notVacant()->where('property_id', $property->id)
        ->orderBy('number')
        ->get();
        $vacantApartments = Apartment::with('property')->vacant()
            ->where('property_id', $property->id)
            ->orderBy('number')
            ->get();
         $allApartments = $apartments->merge($vacantApartments);
        //$apartments = $apartments->property()->orderBy('properties.name')->get();
        return view('apartments.index', [
            'title' => $property->name . ' Apartments',
            'property' => $property,
            'apartments' => $apartments,
            'vacantApartments' => $vacantApartments,
            'allApartments' => $allApartments
            ]);
    }


    /**
     * Show the form for creating a new apartment
     *
     * @return Response
     */
    public function create(Property $property)
    {
        $properties = Property::pluck('abbreviation', 'id');
        
        return view('apartments.edit', ['title' => 'Create Apartment: ' . $property->name,'property' => $property, 'properties' => $properties]);
    }

    /**
     * Store a newly created apartment in storage.
     *
     * @return Response
     */
    public function store(Property $property, Request $request)
    {
        $this->validate($request, [
                'name' => 'required',
                'number' => 'required|unique:apartments,number,property_id,'.$property->id
            ]);

        $input = $request->all();
        $input['name'] = $property->abbreviation . $input['number'];
        $property->apartments()->create($input);
        return redirect()->route('apartments.index', ['id' => $property->id]);
    }

    public function show(Property $property, Apartment $apartment)
    {
        // $apartment = Apartment::find($apartment_id);
        // $property = Property::find($property_id);
        $currentLease = $apartment->currentLease();
        return view('apartments.show', [
                'title' => $property->name . " " . $apartment->name,
                'apartment' => $apartment,
                'property' => $property,
                'currentLease' => $currentLease
            ]);
    }

    /**
     * Show the form for editing the specified apartment.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Property $property, Apartment $apartment)
    {

        $properties = Property::active()->pluck('abbreviation', 'id');
        return view('apartments.edit', ['title' => 'Edit '.$apartment->name,'properties' => $properties, 'apartment' => $apartment]);
    }

    /**
     * Update the specified apartment in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Property $property, Apartment $apartment, Request $request)
    {
        $apartment->update($request->all());

        return redirect()->route('apartments.index', $property);
    }

    /**
     * Remove the specified apartment from storage.
     *
     * @param  int  $id
     * @return Response
     */
    // public function destroy($id)
    // {
    // 	Apartment::destroy($id);

    // 	return redirect()->route('apartments.index');
    // }
}
