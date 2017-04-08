<?php
use App\Apartment;
use App\Property;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ApartmentTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function user_can_view_a_property()
	{
	    $this->disableExceptionHandling();
	    $property = factory(Property::class)->create();

	    $response = $this->get('properties/'.$property->id);

	    $response->assertStatus(200);
	    $response->assertSee($property->name);
	}


	/** @test */
	function user_can_view_an_apartment()
	{
	    $this->disableExceptionHandling();
	    $apartment = factory(Apartment::class)->create();
	    $response = $this->get('/properties/'.$apartment->property_id . '/apartments/'. $apartment->id);

	    $response->assertStatus(200);
	    $response->assertSee($apartment->name);

	}

	/** @test */
	function user_can_view_all_apartments()
	{
	    $property = factory(Property::class)->create();
	    $apartments = factory(Apartment::class,50)->create(['property_id' => $property->id]);

	    $response = $this->get('/properties/'.$property->id .'/apartments');

	    $response->assertStatus(200);
	    $response->assertViewHas('apartments');
	}

	/** @test */
	function user_can_create_an_apartment()
	{
		$this->disableExceptionHandling();
		$property = factory(Property::class)->create();

		$response = $this->post('/properties/'.$property->id.'/apartments',[
				'name' => $property->abbreviation . '10',
				'number' => 10,
				'property_id' => $property->id
			]);

		$newApartment = Apartment::where('property_id',$property->id)->where('number',10)->firstOrFail();
		$this->assertNotNull($newApartment);
		$response->assertStatus(302);
		$response->assertRedirect('properties/'.$property->id.'/apartments');
	    
	}

	/** @test */
	function user_can_edit_apartment()
	{
		$this->disableExceptionHandling();
		$apartment = factory(Apartment::class)->create();

		$response = $this->get('/properties/'.$apartment->property_id . '/apartments/'.$apartment->id . '/edit');

		$response->assertStatus(200);
		$response->assertSee($apartment->name);
	    
	}
	/** @test */
	function user_can_update_an_apartment()
	{
	    $this->disableExceptionHandling();
	    $property = factory(Property::class)->create();
	    $apartment = factory(Apartment::class)->create();

	    $response = $this->put('/properties/'.$property->id.'/apartments/'.$apartment->id,[
	    		'name' => $property->abbreviation . "5000",
	    		'number' => '5000'
	    	]);
	    // dd($this->app['session.store']);
	    // dd($response);
	    $newApartment = Apartment::find($apartment->id);
	    $this->assertEquals($property->abbreviation . "5000",$newApartment->name);
	    $this->assertEquals('5000',$newApartment->number);
	    $response->assertStatus(302);
	    $response->assertRedirect('properties/'.$property->id.'/apartments');

	}
    
}