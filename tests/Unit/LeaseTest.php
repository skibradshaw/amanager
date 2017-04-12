<?php
use App\Apartment;
use App\Lease;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class LeaseTest extends TestCase
{
	use DatabaseMigrations;


	/** @test */
	function lease_details_are_created_for_each_month_of_the_lease_when_lease_is_created()
	{
	    $this->disableExceptionHandling();
	    //Create a Lease
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
	    

	    // dd($this->response);
	    // dd($this->app['session.store']);
	    // dd($apartment->leases);
	    $newLease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();
	    //Assert Details were created
	    $this->assertGreaterThan(0,$newLease->details->count());
	    
	    //Assert the number of days on the lease matches the number of days in the lease details
	    $daysOnLease = $newLease->end->diff($newLease->start)->days;

	    $daysOnDetails = $newLease->details()->orderby('end','desc')->first()->end->diff($newLease->details()->orderby('start')->first()->start)->days;
	    $this->assertEquals($daysOnLease,$daysOnDetails);	    

	}

	/** @test */
	function can_get_lease_totals_from_lease_details()
	{

		$apartment = factory(Apartment::class)->create(); 
		$start = '5/15/17';
		$end = '10/31/17';
		$helper = new App\Repositories\HelperRepository;
		$numMonths = $helper->fractionalMonths($start,$end);
		// dd($numMonths . " " . $start . "-" . $end);

	    $this->createLease($apartment,[
	    		'start' => $start,
	    		'end' => $end,
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 1000.00,
                'pet_rent' => 150.00,
                'deposit' => 2000.00,
                'pet_deposit' => 150.00	    		
	    	]);
	    

	    // dd($this->response);
	    // dd($this->app['session.store']);
	    // dd($apartment->leases);
	    $newLease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();
	    //Assert Lease Total Rent equals expected Total
	    $expectedRentTotal = ($numMonths*1000.00)*100;
		$this->assertEquals($expectedRentTotal,$newLease->rent_total);	    

		// Assert Lease Total Pet Rent equals expected Total
		$expectedPetRentTotal = ($numMonths*150.00)*100;
		$this->assertEquals($expectedPetRentTotal,$newLease->petrent_total);

	}	

	/** @test */
	function can_get_open_balance_for_lease()
	{
		$this->disableExceptionHandling();
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of this month')->subMonth()->format('n/j/Y'); 
		$end = Carbon::parse('last day of this month')->addMonths(6)->format('n/j/Y');

	    $this->createLease($apartment,[
	    		'start' => $start,
	    		'end' => $end,
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 1050.00,
                'pet_rent' => 150.50,
                'deposit' => 2000.00,
                'pet_deposit' => 150.00	    		
	    	]);

	    $newLease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();

	    $tenant = factory(App\Tenant::class)->create();
	    $newLease->tenants()->attach($tenant->id);

	    $payments = factory(App\Payment::class,3)->create([
	    		'lease_id' => $newLease->id,
	    		'tenant_id' => $tenant->id,
	    		'amount' => 25000
	    	]);

	    // dd($newLease->openBalance());


	    //Assert open balance matches expected.  
	    //Rent is 1050.00/mo and Pet Rent is 150.50/mo and 2 months are due: 240100 cents.  
	    //Payments are 25000 X 3 payments.  Expected = 240100-75000 = 165100 cents.
	    $this->assertEquals(165100,$newLease->openBalance());
	    
	}
	// /** @test */
	// function tenants_are_required_to_create_lease()
	// {
	// 	// $this->disableExceptionHandling();
	// 	//Apartment
	// 	$apartment = factory(Apartment::class)->create();		

	//     $this->createLease($apartment,[
	//     		'start' => Carbon::parse('first day of next month'),
	//     		'end' => Carbon::parse('first day of next month')->addYear()->subDay(),
	//     		'apartment_id' => $apartment->id
	//     	]);
	//     // dd($response);
	// 	$data = json_decode($this->response->getContent(),true);

	//     $lease = Lease::where('apartment_id',$apartment->id)->where('start',Carbon::parse('first day of next month'))->where('end',Carbon::parse('first day of next month')->addYear()->subDay())->first();
	//     $this->assertNull($lease);
	// 	// dd($data);
	// 	// dd($this->response->getContent());
	// 	// dd($this->app['session.store']);
	// 	// dd($this->dump());

	// 	//http://stackoverflow.com/questions/35025347/phpunit-test-returns-302-for-bad-validation-why-not-422
	// 	$this->assertArrayHasKey('tenants',$data);	    
	// 	$this->response->assertStatus(422);

	// }

	/** @test */
	function start_date_is_required_to_create_lease()
	{
		// $this->disableExceptionHandling();
		//Apartment
		$apartment = factory(Apartment::class)->create();	
		$tenants = factory(Tenant::class,4)->create();	

	    $this->createLease($apartment,[
	    		// 'start' => Carbon::parse('first day of next month'),
	    		'end' => Carbon::parse('first day of next month')->addYear()->subDay(),
	    		'apartment_id' => $apartment->id
	    	]);
	    // dd($response);
		$data = json_decode($this->response->getContent(),true);

	    $lease = Lease::where('apartment_id',$apartment->id)->where('start',Carbon::parse('first day of next month'))->where('end',Carbon::parse('first day of next month')->addYear()->subDay())->first();
	    $this->assertNull($lease);
		// dd($data);
		// dd($this->response->getContent());
		// dd($this->app['session.store']);
		// dd($this->dump());

		//http://stackoverflow.com/questions/35025347/phpunit-test-returns-302-for-bad-validation-why-not-422
		$this->assertArrayHasKey('start',$data);	    
		$this->response->assertStatus(422);	    
	}


	/** @test */
	function end_date_is_required_to_create_lease()
	{
		// $this->disableExceptionHandling();
		//Apartment
		$apartment = factory(Apartment::class)->create();	
		$tenants = factory(Tenant::class,4)->create();	

	    $this->createLease($apartment,[
	    		'start' => Carbon::parse('first day of next month'),
	    		// 'end' => Carbon::parse('first day of next month')->addYear()->subDay(),
	    		'tenants' => $tenants->toArray(),
	    		'apartment_id' => $apartment->id
	    	]);
	    // dd($response);
		$data = json_decode($this->response->getContent(),true);

	    $lease = Lease::where('apartment_id',$apartment->id)->where('start',Carbon::parse('first day of next month'))->where('end',Carbon::parse('first day of next month')->addYear()->subDay())->first();
	    $this->assertNull($lease);
		// dd($data);
		// dd($this->response->getContent());
		// dd($this->app['session.store']);
		// dd($this->dump());

		//http://stackoverflow.com/questions/35025347/phpunit-test-returns-302-for-bad-validation-why-not-422
		$this->assertArrayHasKey('start',$data);	    
		$this->response->assertStatus(422);	    
	}

	/** @test */
	function can_check_apartment_availability_for_given_dates()
	{
		$apartment = factory(Apartment::class)->create();
		//Creaate a Lease for Given Dates and Apartment
		$lease = factory(Lease::class)->create([
			'apartment_id' => $apartment->id,
			'start' => '2017-04-01',
			'end' => '2018-03-31'
			]);

		//Check Availability of the given apartment on the given dates that overlap existing lease
		$available = $apartment->checkAvailability('5/1/2017','10/31/2017');
		//Assert that the Apartment is not available
		$this->assertEquals(false,$available);

		//Check Availability of the given apartment on the given dates that start before and end after the current lease;
		$available = $apartment->checkAvailability('3/1/2017','4/30/2018');
		//Assert that the Apartment is not available
		$this->assertEquals(false,$available);

		//Check Availability of the given apartment when the start date overlaps a current lease
		$available = $apartment->checkAvailability('3/1/2018','10/31/2018');
		//Assert that the apartment is not available.
		$this->assertEquals(false,$available);

		$available = $apartment->checkAvailability('1/1/2017','4/30/2017');
		//Assert that the apartment is available.	    
		$this->assertEquals(false,$available);

		//Check availability outside of the current lease dates
		$available = $apartment->checkAvailability('4/1/2018','10/31/2018');
		//Assert that the apartment is available.	    
		$this->assertEquals(true,$available);
		$available = $apartment->checkAvailability('1/1/2017','3/31/2017');
		//Assert that the apartment is available.	    
		$this->assertEquals(true,$available);

	}

	/** @test */
	function can_get_lease_values_in_dollars()
	{
		//Create a Lease
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of this month')->subMonth()->format('n/j/Y'); 
		$end = Carbon::parse('last day of this month')->addMonths(6)->format('n/j/Y');

	    $this->createLease($apartment,[
	    		'start' => $start,
	    		'end' => $end,
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 1000.00,
                'pet_rent' => 150.00,
                'deposit' => 2000.00,
                'pet_deposit' => 150.00	    		
	    	]);

	    $lease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();
		//Assert format is as expected.	  
		$this->assertEquals('$1,000.00',$lease->monthly_rent_in_dollars);  
		$this->assertEquals('$150.00',$lease->pet_rent_in_dollars);
		$this->assertEquals('$2,000.00',$lease->deposit_in_dollars);
		$this->assertEquals('$150.00',$lease->pet_deposit_in_dollars); 

	}

}