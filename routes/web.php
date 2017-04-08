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

Route::get('/', function () {
    return view('new_page');
});

Auth::routes();
// //Properties


// //Apartments

//Leases
Route::post('properties/{property}/apartments/{apartment}/leases/{lease}/add_tenant',['as' => 'properties.apartements.leases.add_tenant','uses' => 'LeaseController@addTenant']);

//Resource Routes
Route::resource('properties','PropertyController');
Route::resource('properties/{property}/apartments','ApartmentController');
Route::resource('properties/{property}/apartments/{apartment}/leases','LeaseController');
Route::resource('tenants','TenantController');