<?php

use Carbon\Carbon;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
		'firstname' => $faker->firstName,
		'lastname' => $faker->lastName,
		'email' => $faker->email,
		'password' => bcrypt($faker->sentence),
		'phone' => $faker->phoneNumber,
		'is_admin' => 1
    ];
});

$factory->define(App\Property::class,function (Faker\Generator $faker){
	return [
		'name' => 'Carlton Scott',
		'abbreviation' => 'CS'
	];
});

$factory->state(App\Property::class,'active',function($faker){
	return ['active' => 1];
});

$factory->state(App\Property::class,'inactive',function($faker){
	return ['active' => 0];
});


$factory->define(App\Apartment::class, function (Faker\Generator $faker) {

	$number = App\Apartment::max('number')+1;
	return [
		'name' => 'CS'.$number,
		'number' => $number,
		'property_id' => factory(App\Property::class)->create()->id
	];

});

$factory->define(App\Tenant::class, function (Faker\Generator $faker){
	return [
		'firstname' => $faker->firstName,
		'lastname' => $faker->lastName,
		'email' => $faker->email,
		'password' => bcrypt($faker->sentence),
		'phone' => $faker->phoneNumber,
	];
});

$factory->define(App\Lease::class,function (Faker\Generator $faker){
	App\Lease::flushEventListeners();
	return [
		'apartment_id' => factory(App\Apartment::class)->create()->id,
		'start' =>  '3/1/2017', //Carbon::parse('first day of last month')->format('n/j/Y'),
		'end' => '4/30/2018', //Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y'),
		'monthly_rent' => 80000,
		'pet_rent' => 10000,
		'deposit' => 80000,
		'pet_deposit' => 10000,
		'created_by' => factory(App\User::class)->create()->id,
		// 'tenants' => factory(App\Tenant::class,3)->create()->toArray()
	];
});

$factory->define(App\Payment::class,function (Faker\Generator $faker){
	return [
		'paid_date' => Carbon::parse('-1 week'),
		// 'lease_id' => factory(App\Lease::class)->create()->id,
		'tenant_id' => factory(App\Tenant::class)->create()->id,
		'amount' => 50000,
		'payment_type' => collect(App\Payment::$types)->random(),
		'check_no' => '123456789'
	];
});

$factory->state(App\Payment::class,'undeposited',function($faker){
	return ['bank_deposit_id' => null];
});

$factory->state(App\Payment::class,'deposited',function($faker){
	return ['bank_deposit_id' => factory(App\BankDeposit::class)->create()->id];
});

$factory->define(App\Fee::class,function(Faker\Generator $faker){
	return [
		'item_name' => collect(App\Fee::$types)->random(),
		'note' => $faker->sentence,
		'due_date' => Carbon::parse('+1 week'),
		'amount' => 5000
	];

});

$factory->define(App\BankDeposit::class, function(Faker\Generator $faker) {
	$depositDate = Carbon::parse('-1 week');
	(is_null(App\BankDeposit::min('deposit_date'))) ?: $depositDate = App\BankDeposit::min('deposit_date')->subWeeks(2);
	return [
		'user_id' => factory(App\User::class)->create(['is_admin' => 1]),
		'bank_account_id' => factory(App\BankAccount::class)->create()->id,
		'deposit_date' => $depositDate,
		'deposit_type' => collect(App\BankDeposit::$types)->random(),
		'transaction_id' => $faker->randomNumber(4),
		'amount' => 500000
	];
});

$factory->define(App\BankAccount::class,function(Faker\Generator $faker){
	return [
		'name' => $faker->bankAccountNumber
	];
});