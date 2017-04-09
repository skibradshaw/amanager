<?php
use Illuminate\Database\Seeder;
use App\Property;


class PropertiesTableSeeder extends Seeder {

	public function run()
	{
			Property::create([
				'name' => 'Carlton Scott',
				'abbreviation' => 'CS'
			]);
			
			Property::create([
				'name' => 'Stonegate',
				'abbreviation' => 'SG'
			]);
			// Property::create([
			// 	'name' => '1807 House',
			// 	'abbreviation' => '1807H'
			// ]);
	}

}