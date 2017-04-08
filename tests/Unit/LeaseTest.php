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

	function createLease($apartment,$params)
	{
		$this->response = $this->json('POST','/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases',$params);
	}

	/** @test */
	function tenants_are_required_to_create_lease()
	{
		// $this->disableExceptionHandling();
		//Apartment
		$apartment = factory(Apartment::class)->create();		

	    $this->createLease($apartment,[
	    		'start' => Carbon::parse('first day of next month'),
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
		$this->assertArrayHasKey('tenants',$data);	    
		$this->response->assertStatus(422);

	}

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
}