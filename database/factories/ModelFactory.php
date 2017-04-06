<?php

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
		'name' => 'Carlton Scott'
	];
});


$autoIncrement = autoIncrement();


$factory->define(App\Apartment::class, function (Faker\Generator $faker) use ($autoIncrement){


	
	$autoIncrement->next();
	$number = $autoIncrement->current();
	return [
		'name' => 'CS'.$number,
		'number' => $number,
		'property_id' => factory(App\Property::class)->create()->id
	];

});

	function autoIncrement()
	{
	    for ($i = 0; $i < 1000; $i++) {
	        yield $i;
	    }
	}

	