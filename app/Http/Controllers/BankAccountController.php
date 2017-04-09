<?php

namespace App\Http\Controllers;

use App\BankAccount;
use Illuminate\Http\Request;

class BankAccountController extends Controller
{
    //
	public function index()
	{
		$banks = s::all();
		return view('admin.bank_accounts.index',['title' => 'Manage Bank Accounts']);
	}

    //
	public function create()
	{
		
		return view('admin.bank_accounts.edit',['title' => 'Create a Bank Account']);
	}

	public function store(Request $request)
	{
		$this->validate($request,[
				'name' => 'required'
			]);
		$bankAccount = BankAccount::create($request->all());
		return redirect()->route('bank_accounts.index')->with('status','New Bank Account Added!');
	}

	public function edit(BankAccount $bank)
	{
		return view('admin.bank_accounts.edit',[
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
		$bank->delete();
		return redirect()->route('bank_accounts.index')->with('status','Bank Account Deleted!');
	}
}
