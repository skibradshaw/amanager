<?php

namespace App\Http\Controllers;

use App\BankAccount;
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
		$allPayments = Payment::with('lease.apartment.property')->undeposited()->get();
        $payments = $allPayments->groupBy('lease.apartment.property.name');
        $paymentTypes = Payment::$types;
        $depositTypes = BankDeposit::$types;
		$bankAccounts = BankAccount::all();
        return view('deposits.undeposited',[
				'title' => 'Undeposited Payments',
				'payments' => $payments,
                'paymentTypes' => $paymentTypes,
                'allPayments' => $allPayments,
                'bankAccounts' => $bankAccounts,
                'depositTypes' => collect($depositTypes)
			]);
	}

    public function create(Request $request)
    {
        
        if(is_null($request->input('type'))) return redirect()->back()->with('error','Payment Types must be deposited separately');

        $query = Payment::with('lease.apartment.property')->undeposited()->select();
        //Types of payments are grouped as Rent & Fee Payments = Deposit Type ID 1; Security Deposit Payments = Deposit Type 2
        switch ($request->input('type')) {
            case 1:
                $query = $query->where(function($q){
                    $q->where('payment_type','Rent')
                        ->orWhere('payment_type','Fee');
                });
                $type = 'Rent & Fee Payments';
                break;
            case 2:
                $query = $query->where('payment_type','Deposit');
                $type = 'Security Deposit Payments';
                break;                        
            default:
                # code...
                break;
        }
        

        $payments = $query->get();
        $bankAccounts = BankAccount::all();
        $paymentTypes = Payment::$types;
        if(is_null($bankAccounts)) return redirct()->back()->with('error','There are no bank accounts setup.  Please Setup a Bank Account!');

        return view('deposits.create',[
            'title' => 'Make a ' . $type . ' Deposit',
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
            'bankAccounts' => $bankAccounts
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
        // dd($request->all());
        $input = $request->except(['payment_id','all']);
        $payments = $request->only(['payment_id']);
        $input['deposit_date'] = Carbon::parse($input['deposit_date']);
        // $input['deposit_type'] = $request->input('type');
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
