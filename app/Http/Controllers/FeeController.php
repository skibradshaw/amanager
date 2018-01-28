<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Fee;
use App\Lease;
use App\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FeeController extends Controller
{
    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Property $property, Apartment $apartment, Lease $lease)
    {
        //
        $fees = $lease->fees;
        return view('fees.index', [
            'title' => 'All Fees For: ' . $lease->apartment->name . ' Lease: ' . $lease->start->format('n/j/y') . ' - ' . $lease->end->format('n/j/y'),
            'fees' => $fees,
            'property' => $property,
            'apartment' => $apartment,
            'lease' => $lease
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        //
        $fee_types = Fee::$types;
        
        return view('leases.partials.add_fee', [
            'title' => 'Assess Fee: ' . $lease->apartment->name . ' Lease: ' . $lease->start->format('n/j/y') . ' - ' . $lease->end->format('n/j/y'),
            'lease' => $lease,
            'property' => $property,
            'apartment' => $apartment,
            'fee_types' => $fee_types
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        $this->validate($request, [
                'amount' => 'required | numeric',
                'due_date' => 'required | date'
            ]);

        // return $request->all();
        $input = $request->all();
        $input['due_date'] = Carbon::parse($input['due_date']);
        $input['lease_id'] = $lease->id;
        $input['detail_id'] = $lease->details()->whereRaw("'".$input['due_date'] . "' BETWEEN start AND end")->first()->id;
        //Convert Dollars to Cents
        $input['amount'] = round(preg_replace('/[^0-9\.\-]/i', '', $input['amount'])*100, 0);
        $due_date = $input['due_date'];
        if ($due_date->lt($lease->start) || $due_date->gt($lease->end)) {
            return redirect()->back()->with('status', 'Fee Due Date Must be within Lease Dates ('.$lease->start->format('n/j/Y'). '-' . $lease->end->format('n/j/Y').')')->withInput();
        }
        $fee = Fee::create($input);
        return redirect()->route('leases.show', [$property,$apartment,$lease])->with('status', 'New fee has been assessed for $'.$fee->amount_in_dollars . ' due on ' .$fee->due_date->format('n/j/Y') . '!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property, Apartment $apartment, Lease $lease, Fee $fee)
    {
        //
        $fee_types = Fee::$types;
        // return $id;
        
        return view('fees.edit', [
            'title' => 'Edit Fee: ' . $lease->apartment->name . ' Lease: ' . $lease->start->format('n/j/y') . ' - ' . $lease->end->format('n/j/y'),
            'property' => $property,
            'apartment' => $apartment,
            'lease' => $lease,
            'fee' => $fee,
            'fee_types' => $fee_types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Property $property, Apartment $apartment, Lease $lease, Fee $fee, Request $request)
    {
        
        $input = $request->all();
        $input['due_date'] = Carbon::parse($input['due_date']);
        //Convert Dollars to Cents
        $input['amount'] = round(preg_replace('/[^0-9\.\-]/i', '', $input['amount'])*100);

        $fee->update($input);
        return redirect()->route('leases.show', [$property,$apartment,$lease])->with('status', 'Fee Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property, Apartment $apartment, Lease $lease, Fee $fee, Request $request)
    {
        $fee->delete();
        return redirect()->route('leases.show', [$property,$apartment,$lease])->with('status', 'Fee Deleted!');
    }
}
