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

	    $response = $this->get('properties/'.$apartment->property_id . "/apartments/". $apartment->id);

	    $response->assertStatus(200);
	    $response->assertSee($apartment->name);

	}

	/** @test */
	function user_can_view_all_apartments()
	{
	    $property = factory(Property::class)->create();
	    $apartments = factory(Apartment::class,50)->create(['property_id' => $property->id]);

	    $response = $this->get('properties/'.$property->id .'/apartments');

	    $response->assertStatus(200);
	    $response->assertViewHas('apartments');
	}

	/** @test */
	function user_can_create_an_apartment()
	{
		
	    
	}

    
}