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
                $typeMessage = 'Rent & Fee Payments';
                $type = 1;
                break;
            case 2:
                $query = $query->where('payment_type','Deposit');
                $typeMessage = 'Security Deposit Payments';
                $type = 2;
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
            'title' => 'Make a ' . $typeMessage . ' Deposit',
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
            'bankAccounts' => $bankAccounts,
            'type' => $type
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
        // return $request->all();
        $input = $request->except(['payment_id','all','payment_amount','type']);
        $payments = $request->only(['payment_id']);
        $input['user_id'] = \Auth::user()->id;
        $input['deposit_date'] = Carbon::parse($input['deposit_date']);
        $input['deposit_type'] = BankDeposit::$types[$request->input('type')];
        // $input['deposit_type'] = $request->input('type');
        // $input['user_id'] = \Auth::user()->id;

        //Bank Transaction ID is currently a placeholder for a future need/feature
        // $deposit = Deposit::create($input);
        $deposit = BankDeposit::create($input);

        foreach($payments['payment_id'] as $p)
        {
            Payment::where('id',$p)->update(['bank_deposit_id' => $deposit->id]);   
        }
        $deposit->amount = $deposit->payments->sum('amount');
        $deposit->save();

        return redirect()->route('bank_accounts.show',[$deposit->bank_account_id])->with('status','Deposit Added!');

    }

    public function show(BankDeposit $deposit)
    {
        $payments = $deposit->payments;
        return view('deposits.show',[
            'title' => 'Bank Deposit: ' . $deposit->deposit_date->format('Y-m-d'), 
            'deposit' => $deposit,
            'payments' => $payments
            ]);
    }

}
