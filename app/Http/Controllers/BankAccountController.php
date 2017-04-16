<?php

namespace App\Http\Controllers;

use App\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    //
	public function index()
	{
		$bankAccounts = BankAccount::all();
		return view('admin.bank_accounts.index',[
			'title' => 'Manage Bank Accounts',
			'bankAccounts' => $bankAccounts
			]);
	}

    //
	public function create()
	{
		
		return view('admin.bank_accounts.partials.create_modal',['title' => 'Create a Bank Account']);
	}

	public function store(Request $request)
	{
		$this->validate($request,[
				'name' => 'required'
			]);
		$bankAccount = BankAccount::create($request->all());
		return redirect()->back()->with('status','New Bank Account Added!');
	}

	public function show(BankAccount $bank)
	{
		$deposits = $bank->deposits;
		return view('admin.bank_accounts.show',[
			'title' => 'Account History for ' . $bank->name,
			'bankAccount' => $bank,
			'deposits' => $deposits
			]);
	}

	public function edit(BankAccount $bank)
	{
		return view('admin.bank_accounts.partials.create_modal',[
				'title' => 'Edit ' . $bank->name,
				'bank' => $bank
			]);
	}

	public function update(BankAccount $bank,Request $request)
	{
		$bank->update($request->all());
		// return $bank;
		return redirect()->route('bank_accounts.index')->with('status','Bank Account Updated!');
	}

	public function destroy(BankAccount $bank,Request $request)
	{
		if(count($bank->deposits) > 0) return redirect()->back()->with('error','This Bank Account has historical deposits.  It cannot be deleted.');
		$bank->delete();
		return redirect()->route('bank_accounts.index')->with('status','Bank Account Deleted!');
	}
}
