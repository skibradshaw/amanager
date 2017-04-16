<?php
use App\Apartment;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class PaymentsTest extends TestCase
{
	use DatabaseMigrations;
	/** @test */
	function amount_is_required_to_create_payment()
	{
		// $this->disableExceptionHandling();

		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of next month')->format('n/j/Y');
		$end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

		$tenant = factory(Tenant::class)->create();

	    $this->createLease($apartment,[
	    		'start' => $start,
	    		'end' => $end,
	    		'apartment_id' => $apartment->id,
                'monthly_rent' => 1000,
                'pet_rent' => 150,
                'deposit' => 2000,
                'pet_deposit' => 150	    		
	    	]);

		$lease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();
		$lease->tenants()->attach($tenant->id);		

	    $this->response = $this->json('POST','/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/payments',[
	    		'tenant_id' => $tenant->id,
	    		'payment_type' => 'Rent',
	    		// 'amount' => 80000,
	    		'check_no' => 1234,
	    		'paid_date' => Carbon::now()->format('n/j/Y'),	    		
	    	]);
		$data = json_decode($this->response->getContent(),true);
	    $payment = $lease->payments()->where('paid_date',Carbon::parse(Carbon::now()->format('n/j/Y')))
	    	->where('tenant_id',$tenant->id)
	    	->where('amount',80000)
	    	->where('payment_type','Rent')
	    	->first();

	    $this->assertNull($payment);
	    $this->assertArrayHasKey('amount',$data);	    
		$this->response->assertStatus(422);	
	    
	}
    
}