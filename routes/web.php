<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/



// Auth::routes();
    // Auth::routes();
    Route::get('login',['as' => 'login', 'uses' => 'Auth\LoginController@showLoginForm']);
    Route::post('login', 'Auth\LoginController@login');
    Route::post('logout',['as' => 'logout', 'uses' => 'Auth\LoginController@logout']);

    // Password Reset Routes...
    Route::get('password/reset',['as' => 'password.request','uses' => 'Auth\ForgotPasswordController@showLinkRequestForm']);
    Route::post('password/reset', 'Auth\ResetPasswordController@reset');
    Route::get('password/reset/{token?}',['as' => 'password.reset','uses' => 'Auth\ResetPasswordController@showResetForm']);
    Route::post('password/email',['as' => 'password.email','uses' => 'Auth\ForgotPasswordController@sendResetLinkEmail']);
    /*
    |------------------------------------
    | Login Required Routes 
    |------------------------------------
     */
    Route::group(['middleware' => 'auth'],function() {
		//Homepage
		Route::get('/',['as' => 'home', 'uses' => 'DashboardController@index']);   	
		// //Properties


		// //Apartments

		//Leases
		Route::post('properties/{property}/apartments/{apartment}/leases/{lease}/add_tenant',['as' => 'leases.add_tenant','uses' => 'LeaseController@addTenant']);
		Route::get('properties/{property}/apartments/{apartment}/leases/{lease}/remove_tenant/{tenant}',['as' => 'leases.remove_tenant','uses' => 'LeaseController@removeTenant']);
		Route::get('properties/{property}/apartments/{apartment}/leases/{lease}/add_tenant',['as' => 'leases.add_tenant.show','uses' => 'LeaseController@showAddTenant']);
		Route::get('properties/{property}/apartments/{apartment}/leases/{lease}/terminate',['as' => 'leases.terminate.show','uses' => 'LeaseController@showTerminate']);
		Route::post('properties/{property}/apartments/{apartment}/leases/{lease}/terminate',['as' => 'leases.terminate','uses' => 'LeaseController@terminate']);
		Route::get('properties/{property}/apartments/{apartment}/leases/{lease}/pet_rent',['as' => 'leases.pet.show','uses' => 'LeaseDetailController@showPetRent']);
		Route::post('properties/{property}/apartments/{apartment}/leases/{lease}/pet_rent',['as' => 'leases.pet.store','uses' => 'LeaseDetailController@storePetRent']);
		Route::post('properties/{property}/apartments/{apartment}/leases/{lease}/single_pet_rent',['as' => 'leases.pet.storesingle','uses' => 'LeaseDetailController@storeSinglePetRent']);


		//Deposits
		Route::get('reports/undeposited/{property}/confirm',['as' => 'properties.deposits','uses' => 'BankDepositController@confirm']);
		Route::post('reports/undeposited/{property}/confirm',['as' => 'properties.deposits.confirm','uses' => 'BankDepositController@storeConfirm']);

		//Reports
		Route::get('reports/unpaid_balances/{property?}',['as' => 'unpaid.balances','uses' => 'ReportController@unpaidBalances']);
		Route::get('reports/undeposited/{property?}',['as' => 'undeposited','uses' => 'BankDepositController@undeposited']);
		Route::get('properties/{property}/apartments/{apartment}/leases/{lease}/statement',['as' => 'properties.apartments.leases.statement','uses' => 'ReportController@statement']);
		

		//Resource Routes
		Route::resource('users','UserController');
		Route::resource('properties','PropertyController');
		Route::resource('properties/{property}/apartments','ApartmentController');
		Route::resource('properties/{property}/apartments/{apartment}/leases','LeaseController');
		Route::resource('properties/{property}/apartments/{apartment}/leases/{lease}/payments','PaymentsController');
		Route::resource('properties/{property}/apartments/{apartment}/leases/{lease}/fees','FeeController');
		Route::resource('tenants','TenantController');
		Route::resource('admin/bank_accounts','BankAccountController'); 
		Route::resource('admin/bank_accounts/{bank_account}/deposits','BankDepositController');

    });


		