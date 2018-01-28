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
        $allBankAccounts = BankAccount::with('property')->get();

        $bankAccounts = $allBankAccounts->groupBy('property.name');
        $payments = $allPayments->groupBy('lease.apartment.property.name');

        $paymentTypes = Payment::$types;
        $depositTypes = BankDeposit::$types;

        return view('deposits.undeposited', [
            'title' => 'Undeposited Payments',
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
            'allPayments' => $allPayments,
            'bankAccounts' => $bankAccounts,
            'depositTypes' => collect($depositTypes)
        ]);
    }

    public function confirm(Property $property, Request $request)
    {
        $bankAccounts = $property->bank_accounts;
        if (is_null($bankAccounts)) {
            return redirct()->back()->with('error', 'There are no bank accounts setup.  Please Setup a Bank Account!');
        }

        $query = Payment::whereHas('lease.apartment', function ($q) use ($property) {
            $q->where('property_id', $property->id);
        })->undeposited()->select();


        //Types of payments are grouped as Rent & Fee Payments = Deposit Type ID 1; Security Deposit Payments = Deposit Type 2
        switch ($request->input('type')) {
            case 1:
                $query = $query->where(function ($q) {
                    $q->where('payment_type', 'Rent')
                        ->orWhere('payment_type', 'Fee');
                });
                $typeMessage = 'Rent & Fee Payments';
                $type = 1;
                break;
            case 2:
                $query = $query->where('payment_type', 'Security Deposit');
                $typeMessage = 'Security Deposit Payments';
                $type = 2;
                break;
            default:
                # code...
                break;
        }
        

        $payments = $query->get();
        // return "The Count is " . count($payments);
        if (count($payments) == 0) {
            return redirect()->back();
        }

        return view('deposits.confirm', [
                'title' => 'Confirm Deposit for '.$property->name,
                'property' => $property,
                'payments' => $payments,
                'bankAccounts' => $bankAccounts,
                'type' => $type
            ]);
    }

    public function storeConfirm(Property $property, Request $request)
    {
        // dd($request->all());
        // return $request->all();
        $input = $request->except(['payment_id','all','payment_amount','type']);
        $payments = Payment::whereIn('id', $request->input('payment_id'))->get();
        $bank = BankAccount::find($request->input('bank_account_id'));
        $input['user_id'] = \Auth::user()->id;
        $input['deposit_date'] = Carbon::parse($input['deposit_date']);
        $input['deposit_type'] = BankDeposit::$types[$request->input('type')];
        $input['amount'] = ($input['amount']*100);
        // $input['deposit_type'] = $request->input('type');
        // $input['user_id'] = \Auth::user()->id;
        // return $input['amount'] . " " . $payments->sum('amount');
        //Amount submitted must equal payments total
        if ($input['amount'] != $payments->sum('amount')) {
            return redirect()->back()->with('error', 'The total payments must match the Deposit Total.  Please try again!');
        }

        if (count($payments) == 0) {
            return redirect()->back()->with('error', 'There are no payments in this deposit.  Please try again!');
        }
        //Bank Transaction ID is currently a placeholder for a future need/feature
        // $deposit = Deposit::create($input);
        $deposit = $bank->deposits()->create($input);

        foreach ($payments as $p) {
            $p->update(['bank_deposit_id' => $deposit->id]);
        }
        $deposit->amount = $deposit->payments->sum('amount');
        $deposit->save();
        // return $deposit;
        return redirect()->route('bank_accounts.show', [$deposit->bank_account_id])->with('status', 'Deposit Added!');
    }

    public function create(BankAccount $bank, Request $request)
    {
        
        if (is_null($request->input('type'))) {
            return redirect()->back()->with('error', 'Payment Types must be deposited separately');
        }

        $query = Payment::with('lease.apartment.property')->undeposited()->select();
        //Types of payments are grouped as Rent & Fee Payments = Deposit Type ID 1; Security Deposit Payments = Deposit Type 2
        switch ($request->input('type')) {
            case 1:
                $query = $query->where(function ($q) {
                    $q->where('payment_type', 'Rent')
                        ->orWhere('payment_type', 'Fee');
                });
                $typeMessage = 'Rent & Fee Payments';
                $type = 1;
                break;
            case 2:
                $query = $query->where('payment_type', 'Security Deposit');
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
        if (is_null($bankAccounts)) {
            return redirct()->back()->with('error', 'There are no bank accounts setup.  Please Setup a Bank Account!');
        }

        return view('deposits.create', [
            'title' => 'Make a ' . $typeMessage . ' Deposit',
            'payments' => $payments,
            'paymentTypes' => $paymentTypes,
            'bankAccounts' => $bankAccounts,
            'bank' => $bank,
            'type' => $type
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BankAccount $bank, Request $request)
    {
        // dd($request->all());
        // return $request->all();
        $input = $request->except(['payment_id','all','payment_amount','type']);
        $payments = Payment::whereIn('id', $request->input('payment_id'))->get();
        $input['user_id'] = \Auth::user()->id;
        $input['deposit_date'] = Carbon::parse($input['deposit_date']);
        $input['deposit_type'] = BankDeposit::$types[$request->input('type')];
        // $input['deposit_type'] = $request->input('type');
        // $input['user_id'] = \Auth::user()->id;

        //Amount submitted must equal payments total
        if ($request->input('amount') != $payments->sum('amount')) {
            return redirect()->back()->with('error', 'The total payments must be more than 0 (zero).  Please try again!');
        }

        if (count($payments) == 0) {
            return redirect()->back()->with('error', 'There are no payments in this deposit.  Please try again!');
        }
        //Bank Transaction ID is currently a placeholder for a future need/feature
        // $deposit = Deposit::create($input);
        $deposit = $bank->deposits()->create($input);

        foreach ($payments as $p) {
            $p->update(['bank_deposit_id' => $deposit->id]);
        }
        $deposit->amount = $deposit->payments->sum('amount');
        $deposit->save();

        return redirect()->route('bank_accounts.show', [$deposit->bank_account_id])->with('status', 'Deposit Added!');
    }

    public function show(BankAccount $bank, BankDeposit $deposit)
    {
        $payments = $deposit->payments;
        return view('deposits.show', [
            'title' => 'Bank Deposit: ' . $deposit->deposit_date->format('Y-m-d'),
            'deposit' => $deposit,
            'payments' => $payments
            ]);
    }
}
