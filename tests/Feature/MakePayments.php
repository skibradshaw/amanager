<?php
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class MakePaymentsTest extends TestCase
{
	/** @test */
	function user_can_make_a_payment_on_lease()
	{
	    $lease = factory(Lease::class)->create();
	    $tenant = factory(Tenant::class)->create();
	    $lease->tenants()->attach($tenant->id);

	    $this->post('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/add_payment',[
	    		'tenant_id' => $tenant->id,
	    		'payment_type' => 'Rent',
	    		'amount' => 80000,
	    		'check_num' => 1234,
	    		'paid_date' => Carbon::now(),
	    		
	    	]);

	}

    
}