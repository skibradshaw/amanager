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
//Properties
Route::get('properties/{property_id}','PropertyController@show');

//Apartments
Route::get('properties/{propoerty_id}/apartments','ApartmentController@index');
Route::get('properties/{property_id}/apartments/{apartment_id}','ApartmentController@show');

