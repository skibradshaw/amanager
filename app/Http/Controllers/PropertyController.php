<?php

namespace App\Http\Controllers;

use App\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    //
	public function show($property_id)
	{
		$property = Property::find($property_id);
		return view('properties.show',['property' => $property]);
	}

}
