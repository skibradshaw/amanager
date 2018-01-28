<?php
use App\Apartment;
use App\Property;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ApartmentsTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function user_cannot_create_duplicate_apartments_on_same_property()
    {
        $property = factory(Property::class)->create();
        $apartment = factory(Apartment::class)->create();

        $response = $this->post('/properties/'.$property->id.'/apartments', [
                'name' => $property->abbreviation . '10',
                'number' => $apartment->number,
            ]);

        $newApartment = Apartment::where('property_id', $property->id)->where('number', $apartment->number)->where('id', '!=', $apartment->id)->first();

        $this->assertNull($newApartment);
    }

    /** @test */
    // function apartment_name_is_always_set_to_property_abbreviation_and_apartment_number()
    // {
    // 	$property = factory(Property::class)->create();
    // 	$response = $this->post('/properties/'.$property->id.'/apartments/create',[
    // 			'name' => 'ExampleApartment10',
    // 			'number' => '10'
    // 		]);

    // 	$newApartment = Apartment::where('number',10)->where('property_id',$property->id)->first();

    // 	$this->assertEquals($property->abbreviation.'10',$newApartment->name);
    // 	$this->assertNotEquals('ExampleApartment10',$newApartment->name);

    // }
}
