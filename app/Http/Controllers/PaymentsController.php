<?php

namespace App\Http\Controllers;

use App\Apartment;
use App\Lease;
use App\Payment;
use App\Property;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        //
      
        $tenants = $lease->tenants->pluck('fullname','id');
        ($request->input('tenant_id')) ? $tenant = Tenant::find($request->input('tenant_id')) : $tenant = new Tenant;
        (!empty($request->input('type'))) ? $type = $request->input('type') : $type = '';
        // if($lease->depositBalance() <> 0)
        // {
        //    $payment_types = ['Rent' => 'Rent','Fee' => 'Fee','Deposit' => 'Deposit'];        
        // } else {
        //    $payment_types = ['Rent' => 'Rent','Fee' => 'Fee'];
        // } 
        $payment_types = Payment::$types;       
        //return $tenant;
        return view('payments.edit',[
            'title' => 'Record a Payment: ' . $lease->apartment->name . ' Lease: ' . $lease->start->format('n/j/y') . ' - ' . $lease->end->format('n/j/y')  ,
            'apartment' => $apartment, 'lease' => $lease, 
            'tenants' => $tenants, 
            'tenant' => $tenant,
            'payment_types' => $payment_types,
            'payment_type' => $type
            ]);
    }	
    //
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Property $property, Apartment $apartment, Lease $lease, Request $request)
    {
        //
         $this->validate($request,[
                'amount' => 'required | numeric'        
            ]);  
        $input = $request->all();
        $input['paid_date'] = Carbon::parse($input['paid_date']);
        $input['lease_id'] = $lease->id;
        $payment = Payment::create($input);
        // PaymentAllocation::create(['amount' => $input['amount'], 'month' => Carbon::parse($input['paid_date'])->month, 'year' => Carbon::parse($input['paid_date'])->year, 'payment_id' => $payment->id]);
        return redirect()->route('leases.show',[$property,$apartment,$lease])
        	->with('status','Added a $' . $payment->amount . ' Payment for ' . $payment->tenant->full_name . '!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Property $property, Apartment $apartment, Lease $lease, Payment $payment)
    {
       
        //
       $tenants = $lease->tenants->pluck('fullname','id');
       $tenant = $payment->tenant;
       // if($lease->depositBalance() <> 0)
       // {
       //     $payment_types = ['Rent' => 'Rent','Fee' => 'Fee','Deposit' => 'Deposit'];        
       // } else {
       //             $payment_types = ['Rent' => 'Rent','Fee' => 'Fee'];
       // }
       $payment_types = Payment::$types; 
       return view('payments.edit',[
            'title' => 'Edit a Payment: ' . $lease->apartment->name . ' Lease: ' . $lease->start->format('n/j/y') . ' - ' . $lease->end->format('n/j/y')  ,
            'apartment' => $apartment, 
            'lease' => $lease, 
            'tenants' => $tenants,
            'tenant' => $tenant,
            'payment' => $payment,
            'payment_types' => $payment_types,
            'payment_type' => $payment->payment_type
            ]);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Property $property, Apartment $apartment, Lease $lease, Payment $payment, Request $request)
    {
        //
        // return $request->all();
        $this->validate($request,[
            'amount' => 'required | numeric'        
        ]);  
        $input = $request->all();
        $input['paid_date'] = Carbon::parse($input['paid_date']);
        $payment->update($input);
        //Remove Current Allocations for a Payment and Create 1 Allocation for the Edited Payment
        // \App\PaymentAllocation::destroy($payment->allocations()->lists('id')->toArray());
        // PaymentAllocation::create(['amount' => $input['amount'], 'month' => Carbon::parse($input['paid_date'])->month, 'year' => Carbon::parse($input['paid_date'])->year, 'payment_id' => $payment->id]);
        return redirect()->route('leases.show',[$property,$apartment,$lease])->with('status','Payment Updated!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $property, Apartment $apartment, Lease $lease, Payment $payment)
    {
        //Business Rule - Do not allow delete of Payment that has been deposited
        if(!empty($payment->bank_deposit_id))
        {
            return redirect()->back()->with('alert','This payment cannot be deleted.  It has already been deposited.');
        }
        // \App\PaymentAllocation::destroy($payment->allocations()->lists('id')->toArray());
        $payment->delete();
        return redirect()->route('leases.show',[$property,$apartment,$lease])->with('status','Payment Deleted!');
    }
}
