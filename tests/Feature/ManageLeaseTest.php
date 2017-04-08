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
	    $this->disableExceptionHandling();
	    $apartment = factory(Apartment::class)->create();

	    $response = $this->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/create');
	    $response->assertStatus(200);
	    $response->assertViewHas('apartment');
	    $response->assertSee($apartment->name);
	}

	/** @test */
	function user_can_create_a_lease_with_tenants()
	{
		// $this->disableExceptionHandling();
		//Apartment
		$apartment = factory(Apartment::class)->create(); 
	    //Tenants
	    $tenants = factory(Tenant::class,4)->create();

	    $response = $this->post('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases',[
	    		'start' => Carbon::now(),
	    		'end' => Carbon::now(),
	    		'tenants' => $tenants->toArray(),
	    		'apartment_id' => $apartment->id
	    	]);

	    // dd($response);
	    $lease = Lease::where('apartment_id',$apartment->id)->firstOrFail();
	    $this->assertNotNull($lease);
	    $this->assertEquals(4,$lease->tenants->count());
	    $response->assertStatus(200);

	    
	}

	/** @test */
	function user_can_add_tenant_to_lease()
	{
		$this->disableExceptionHandling();
		$apartment = factory(Apartment::class)->create(); 
		$lease = factory(Lease::class)->create(['apartment_id' => $apartment->id]);
		$tenant = factory(Tenant::class)->create();

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
		// $this->disableExceptionHandling();		
		//Apartment
		$apartment = factory(Apartment::class)->create(); 
		$lease = factory(Lease::class)->create(['apartment_id' => $apartment->id]);
		// dd($lease);
		$response = $this->get('/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases/'.$lease->id);

		$response->assertStatus(200);
		$response->assertSee($lease->start->format('n/j/Y'));


	    
	}
    
}