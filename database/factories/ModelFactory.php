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
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Property::class,function (Faker\Generator $faker){
	return [
		'name' => 'Carlton Scott',
		'abbreviation' => 'CS'
	];
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

	return [
		'apartment_id' => factory(App\Apartment::class)->create()->id,
		'start' => Carbon::parse('first day of next month'),
		'end' => Carbon::parse('first day of next month')->addYear()->subDay(),
		'monthly_rent' => 80000;
		'pet_rent' => 10000;
		'deposit' => 80000;
		'pet_deposit' => 10000;
		// 'tenants' => factory(App\Tenant::class,3)->create()->toArray()
	];
});

	