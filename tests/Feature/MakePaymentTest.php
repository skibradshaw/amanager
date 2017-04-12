<?php
use App\Apartment;
use App\Lease;
use App\Payment;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class MakePaymentsTest extends TestCase
{
	use DatabaseMigrations;

	function getLease()
	{
		
		$apartment = factory(Apartment::class)->create(); 
		$start = Carbon::parse('first day of last month')->format('n/j/Y');
		$end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

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
		
		return $lease;

	}

	/** @test */
	function user_can_view_form_to_create_payment()
	{
		// $this->disableExceptionHandling();

	    $lease = $this->getLease();
		$tenant = factory(Tenant::class)->create();
	    $lease->tenants()->attach($tenant->id);			    

	    $response = $this->get('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/payments/create');

	    $response->assertStatus(200);
	    $response->assertViewHas('lease');
	    $response->assertViewHas('payment_types');
	    $response->assertSee($lease->apartment->name);
	    

	}

	/** @test */
	function user_can_make_a_payment_on_lease()
	{
		// $this->disableExceptionHandling();

	    $lease = $this->getLease();
		$tenant = factory(Tenant::class)->create();
	    $lease->tenants()->attach($tenant->id);

	    $response = $this->post('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/payments',[
	    		'tenant_id' => $tenant->id,
	    		'payment_type' => 'Rent',
	    		'amount' => 800.50,
	    		'check_no' => 1234,
	    		'paid_date' => Carbon::now()->format('n/j/Y'),	    		
	    	]);

	    $payment = $lease->payments()->where('paid_date',Carbon::parse(Carbon::now()->format('n/j/Y')))
	    	->where('tenant_id',$tenant->id)
	    	->where('amount',80050)
	    	->where('payment_type','Rent')
	    	->first();

	    $this->assertNotNull($payment);
	    $response->assertSessionHas('status','Added a $' . $payment->amount . ' Payment for ' . $tenant->full_name . '!');
	    $response->assertStatus(302);
	    $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);


	}

	/** @test */
	function user_can_view_form_to_edit_payment()
	{
		// $this->disableExceptionHandling();

	    $lease = $this->getLease();
		$tenant = factory(Tenant::class)->create();
	    $lease->tenants()->attach($tenant->id);		
	    $payment = factory(Payment::class)->create([
	    		'lease_id' => $lease->id,
	    		'tenant_id' => $tenant->id,
	    		'amount' => 50000,
	    		'check_no' => '123456789'
	    	]);	    

	    $response = $this->get('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/payments/'.$payment->id.'/edit');

	    $response->assertStatus(200);
	    $response->assertViewHas('lease');
	    $response->assertViewHas('payment_types');
	    $response->assertSee($lease->apartment->name);	    
	}

	/** @test */
	function user_can_update_payment()
	{
	    // $this->disableExceptionHandling();

	    $lease = $this->getLease();
		$tenant = factory(Tenant::class)->create();
	    $lease->tenants()->attach($tenant->id);		
	    $payment = factory(Payment::class)->create([
	    		'lease_id' => $lease->id,
	    		'tenant_id' => $tenant->id,
	    		'amount' => 50000,
	    		'paid_date' => $lease->start->addDays(10)
	    	]);	    

	    $orgPaidDate = $lease->start->addDays(10);
	    //Change Amount and Paid Date
	    $response = $this->put('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/payments/'.$payment->id, [
	    		'amount' => 1000.00,
	    		'paid_date' => $lease->start->addDays(30)
	    	]);

	    $newPayment = Payment::find($payment->id);

	    $this->assertEquals($lease->start->addDays(30)->format('n/j/Y'),$newPayment->paid_date->format('n/j/Y'));
	    $this->assertEquals(100000,$newPayment->amount);

	    $response->assertStatus(302);
	    $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);
	    $response->assertSessionHas('status','Payment Updated!');
	    
	}

	/** @test */
	function user_can_delete_a_payment()
	{
	    $this->disableExceptionHandling();

	    $lease = $this->getLease();
;
		$tenant = factory(Tenant::class)->create();
	    $lease->tenants()->attach($tenant->id);		
	    $payment = factory(Payment::class)->create([
	    		'lease_id' => $lease->id,
	    		'tenant_id' => $tenant->id,
	    		'amount' => 50000,
	    		'paid_date' => $lease->start->addDays(10)
	    	]);

	    $response = $this->delete('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/payments/'.$payment->id);

	    $newPayment = Payment::find($payment->id);

	    $this->assertNull($newPayment);

	    $response->assertStatus(302);
	    $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);
	    $response->assertSessionHas('status','Payment Deleted!');	
	        
	}    
}