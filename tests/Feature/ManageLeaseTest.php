<?php
use App\Apartment;
use App\Lease;
use App\LeaseDetail;
use App\Payment;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Spatie\Activitylog\Models\Activity;
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

	    // dd(Activity::all()->last());
	    // dd($admin->activity);
	    
	}
	/** @test */
	function user_can_view_form_to_edit_lease()
	{
		$this->disableExceptionHandling();
		$admin = $this->getAdminUser();
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

		$response = $this->actingAs($admin)->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/' . $lease->id . '/edit');

		$response->assertViewHas('lease');
		$response->assertStatus(200);
	}

	/** @test */
	function user_can_update_dates_and_rent_on_lease()
	{
		$this->disableExceptionHandling();
		$admin = $this->getAdminUser();
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

		$newStart = Carbon::parse('first day of next month')->addMonth()->format('n/j/Y');
		$newEnd = Carbon::parse('first day of next month')->addYear()->subDay()->addMonth()->format('n/j/Y');

	    $response = $this->actingAs($admin)->put('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/' . $lease->id, [
    		'start' => $newStart,
    		'end' => $newEnd,
            'monthly_rent' => 2000.00,
            'pet_rent' => 150.00,
            'deposit' => 4000.00,
            'pet_deposit' => 150.00		    	
	    ]);  

		$newLease = $apartment->leases()->where('start',Carbon::parse($newStart))->where('end',Carbon::parse($newEnd))->first();

		$this->assertNotEquals(100000,$newLease->monthly_rent);
		$this->assertEquals(200000, $newLease->monthly_rent);
		$response->assertStatus(302);

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
	function user_can_remove_tenant_from_lease()
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
		$lease->tenants()->attach($tenant->id);

		$this->assertEquals(1,$lease->tenants->count());
		
		$response = $this->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id . '/remove_tenant/'. $tenant->id);
		// $data = json_decode($this->response->getContent(),true);
		// dd($data);
		$response->assertStatus(302);
		$response->assertSessionHas('status',$tenant->fullname . ' was successfully removed from this Lease.');
		$lease = $lease->fresh();
		$this->assertEquals(0,$lease->tenants->count());
	    
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
		$start =  '4/1/2017'; //Carbon::parse('first day of last month')->format('n/j/Y');
		$end = '3/31/2018';//Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

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

		$terminationEnd = '5/1/2017';

		$response = $this->post('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id . '/terminate',[
				'end' => $terminationEnd,
			]);

	    $newLease = Lease::find($lease->id);
		//Assert the new Lease End date equals today
		$this->assertEquals($terminationEnd,$newLease->end->format('n/j/Y'));
		//Assert the correct number of months is in the Lease Details
		$expectedMonths = $helper->fractionalMonths($start,$terminationEnd);
		$this->assertEquals($expectedMonths,$newLease->details->sum('multiplier'));

		//Assert the New Total is the expected total
		$expectedRentTotal = ($expectedMonths*1050.50)*100;
		$this->assertEquals($expectedRentTotal,$newLease->rent_total);
		

		$response->assertStatus(302);
		$response->assertRedirect('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id);

	}

	/** @test */
	function user_can_delete_a_lease()
	{

		$this->disableExceptionHandling();
		$helper = new App\Repositories\HelperRepository;
		//Apartment
		$apartment = factory(Apartment::class)->create(); 
		$start =  '4/1/2017'; //Carbon::parse('first day of last month')->format('n/j/Y');
		$end = '3/31/2018';//Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

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
		$undeposited = factory(Payment::class)->states('undeposited')->create([
				'lease_id' => $lease->id
			]);

		
		$response = $this->delete('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id);

		$newLease = Lease::find($lease->id);
		$details = LeaseDetail::where('lease_id',$lease->id)->get();
		$payments = $lease->payments;

		$this->assertNull($newLease);
		$this->assertEmpty($payments->toArray());
		$this->assertEmpty($details->toArray());

		$response->assertStatus(302);
		$response->assertRedirect('/');
		$response->assertSessionHas('status','Lease Deleted!');

	    
	}	
    
}