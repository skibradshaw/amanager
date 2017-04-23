<?php
use App\Apartment;
use App\Property;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ManageApartmentTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function user_can_view_a_property()
	{
	    // $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();
	    $property = factory(Property::class)->create();

	    $response = $this->actingAs($admin)->get('properties/'.$property->id);

	    $response->assertStatus(200);
	    $response->assertSee($property->name);
	}


	/** @test */
	function user_can_view_an_apartment()
	{
	    // $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();
	    $apartment = factory(Apartment::class)->create();
	    $response = $this->actingAs($admin)->get('/properties/'.$apartment->property_id . '/apartments/'. $apartment->id);

	    $response->assertStatus(200);
	    $response->assertSee($apartment->name);

	}

	/** @test */
	function user_can_view_all_apartments()
	{
	    $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();
	    $property = factory(Property::class)->create();
	    $apartments = factory(Apartment::class,50)->create(['property_id' => $property->id]);

	    $response = $this->actingAs($admin)->get('/properties/'.$property->id .'/apartments');

	    $response->assertStatus(200);
	    $response->assertViewHas('apartments');
	}

	/** @test */
	function user_can_view_create_apartment_form()
	{
	    // $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();
	    $property = factory(Property::class)->create();

	    $response = $this->actingAs($admin)->get('/properties/'.$property->id .'/apartments/create');

	    $response->assertStatus(200);
	    $response->assertViewHas('property');
	    $response->assertViewHas('properties');
	    $response->assertSee('Create Apartment: ' . $property->name);

	}

	/** @test */
	function user_can_store_an_apartment()
	{
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		$property = factory(Property::class)->create();

		$response = $this->actingAs($admin)->post('/properties/'.$property->id.'/apartments',[
				'name' => $property->abbreviation . '10',
				'number' => 10
			]);

		$newApartment = Apartment::where('property_id',$property->id)->where('number',10)->firstOrFail();
		$this->assertNotNull($newApartment);
		$response->assertStatus(302);
		$response->assertRedirect('properties/'.$property->id.'/apartments');
	    
	}

	/** @test */
	function user_can_edit_apartment()
	{
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		$apartment = factory(Apartment::class)->create();

		$response = $this->actingAs($admin)->get('/properties/'.$apartment->property_id . '/apartments/'.$apartment->id . '/edit');

		$response->assertStatus(200);
		$response->assertSee($apartment->name);
	    
	}
	/** @test */
	function user_can_update_an_apartment()
	{
	    // $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();
	    $property = factory(Property::class)->create();
	    $apartment = factory(Apartment::class)->create();

	    $response = $this->actingAs($admin)->put('/properties/'.$property->id.'/apartments/'.$apartment->id,[
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

	/** @test */
	function can_get_current_lease_for_apartment()
	{
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of last month')->format('n/j/Y');
		$end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

	    $this->createLease($apartment,[
	    		'start' => $start,
	    		'end' => $end,
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 100000,
                'pet_rent' => 15000,
                'deposit' => 200000,
                'pet_deposit' => 15000	    		
	    	]);

		$lease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();

		$currentLease = $apartment->currentLease();

		$this->assertNotNull($currentLease);
		$this->assertEquals($lease->start,$currentLease->start);
		$this->assertEquals($lease->id,$currentLease->id);

	    
	}
    
}