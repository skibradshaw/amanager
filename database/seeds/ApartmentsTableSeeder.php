<?php

// Composer: "fzaninotto/faker": "v1.3.0"
//use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use App\Apartment;

class ApartmentsTableSeeder extends Seeder
{

    public function run()
    {
        

        foreach (range(1, 32) as $index) {
            Apartment::create([
                'name' => 'CS' . $index,
                'number' => $index,
                'property_id' => 1

            ]);
        }
        foreach (range(1, 60) as $index) {
            Apartment::create([
                'name' => 'SG' . $index,
                'number' => $index,
                'property_id' => 2

            ]);
        }
        Apartment::create([
            'name' => '1807H1',
            'number' => 1,
            'property_id' => 3

        ]);
    }
}
