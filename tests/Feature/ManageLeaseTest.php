<?php
use App\Apartment;
use App\Lease;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ManageLeaseTest extends TestCase
{
	use DatabaseMigrations;


	/** @test */
	function user_can_view_form_to_create_lease()
	{
	    // $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();
	    $apartment = factory(Apartment::class)->create();

	    $response = $this->actingAs($admin)->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/create');
	    $response->assertStatus(200);
	    $response->assertViewHas('apartment');
	    $response->assertSee($apartment->name);
	}

	/** @test */
	function user_can_create_a_lease()
	{
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		//Apartment
		$apartment = factory(Apartment::class)->create(); 

	    $response = $this->actingAs($admin)->post('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases',[
	    		'start' => Carbon::parse('first day of next month'),
	    		'end' => Carbon::parse('first day of next month')->addYear()->subDay(),
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 100000,
                'pet_rent' => 15000,
                'deposit' => 200000,
                'pet_deposit' => 15000	    		
	    	]);

	    // dd($response);
	    $lease = Lease::where('apartment_id',$apartment->id)->firstOrFail();
	    $this->assertNotNull($lease);
	    $response->assertStatus(302);
	    $response->assertRedirect('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id);

	    
	}

	/** @test */
	function user_cannot_create_a_lease_that_overlaps_another_lease_dates()
	{
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		$apartment = factory(Apartment::class)->create();
		$lease = factory(Lease::class)->create([
			'apartment_id' => $apartment->id,
			'start' => '2017-04-01',
			'end' => '2018-03-31'
			]);

	    $response = $this->actingAs($admin)->post('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases',[
	    		'start' => '2017-05-01',
	    		'end' => '2017-10-01',
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 100000,
                'pet_rent' => 15000,
                'deposit' => 200000,
                'pet_deposit' => 15000	    		
	    	],['HTTP_REFERER' => '/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/create']);

	    $newLease = Lease::where('apartment_id',$apartment->id)->where('start',Carbon::parse('2017-05-01'))->where('end',Carbon::parse('2017-10-01'))->first();

	    $this->assertNull($newLease);
	    $response->assertStatus(302);
	    $response->assertSessionHas('error','These dates are not available!');
	    $response->assertRedirect('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/create');

	}

	/** @test */
	function user_can_add_tenant_to_lease()
	{
		$this->disableExceptionHandling();
		$apartment = factory(Apartment::class)->create(); 
		$tenant = factory(Tenant::class)->create();
		
		
		$start = Carbon::parse('first day of next month')->format('n/j/Y');
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

		$response = $this->post('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id . '/add_tenant',[
				'tenant_id' => $tenant->id
			]);

		$newTenant = $lease->tenants()->where('tenant_id',$tenant->id)->first();
		$this->assertNotNull($newTenant);
		$response->assertStatus(200);


	}

	/** @test */
	function user_can_view_lease()
	{
		$this->disableExceptionHandling();		
		//Apartment
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of next month')->format('n/j/Y');
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
		// dd($lease);
		$response = $this->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id);

		$response->assertStatus(200);
		$response->assertViewHas('lease');
		$response->assertSee($lease->start->format('n/j/Y'));
		$response->assertSee($lease->end->format('n/j/Y'));
	    
	}

	/** @test */
	function user_can_view_a_form_to_terminate_lease()
	{
		$this->disableExceptionHandling();
		//Apartment
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of next month')->format('n/j/Y');
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

		$response = $this->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id . '/terminate');
		// dd($response);
		$response->assertStatus(200);
		$response->assertViewHas('lease');
		$response->assertSee($lease->apartment->name);
	    
	}

	/** @test */
	function user_can_terminate_lease()
	{
		$this->disableExceptionHandling();
		$helper = new App\Repositories\HelperRepository;
		//Apartment
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of last month')->format('n/j/Y');
		$end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

	    $this->createLease($apartment,[
	    		'start' => $start,
	    		'end' => $end,
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 1050.50,
                'pet_rent' => 150.00,
                'deposit' => 2000.00,
                'pet_deposit' => 150.00	    		
	    	]);

		$lease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();

		$response = $this->post('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id . '/terminate',[
				'end' => Carbon::now()->format('n/j/Y'),
			]);
	    
	    $newLease = Lease::find($lease->id);
		//Assert the new Lease End date equals today
		$this->assertEquals(Carbon::now()->format('n/j/Y'),$newLease->end->format('n/j/Y'));
		//Assert the correct number of months is in the Lease Details
		$expectedMonths = $helper->fractionalMonths($start,Carbon::now()->format('n/j/Y'));
		$this->assertEquals($expectedMonths,$newLease->details->sum('multiplier'));

		//Assert the New Total is the expected total
		$expectedRentTotal = ($expectedMonths*1050.50)*100;
		$this->assertEquals($expectedRentTotal,$newLease->rent_total);
		

		$response->assertStatus(302);
		$response->assertRedirect('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id);

	}
	
    
}