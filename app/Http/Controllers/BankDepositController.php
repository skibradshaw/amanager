<?php

namespace App\Http\Controllers;

use App\BankDeposit;
use App\Payment;
use App\Property;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BankDepositController extends Controller
{
    //
	public function undeposited(Property $property, Request $request)
	{
		$payments = Payment::undeposited()->get();

		return view('deposits.undeposited',[
				'title' => 'Undeposited Payments',
				'payments' => $payments
			]);
	}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Property $property, Request $request)
    {
        //
        $input = $request->except(['payment_id']);
        $payments = $request->only(['payment_id']);
        $input['deposit_date'] = Carbon::parse($input['deposit_date']);
        // $input['user_id'] = \Auth::user()->id;

        //Bank Transaction ID is currently a placeholder for a future need/feature
        // $deposit = Deposit::create($input);
        $deposit = BankDeposit::create($input);

        foreach($payments['payment_id'] as $p)
        {
            Payment::where('id',$p)->update(['bank_deposit_id' => $deposit->id]);   
        }

        return redirect()->route('deposits.index')->with('status','Deposit Added!');

    }

}
