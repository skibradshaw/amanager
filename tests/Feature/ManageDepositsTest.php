<?php
use App\Apartment;
use App\BankDeposit;
use App\Payment;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class ManageDepositsTest extends TestCase
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
                'monthly_rent' => 100000,
                'pet_rent' => 15000,
                'deposit' => 200000,
                'pet_deposit' => 15000	    		
	    	]);

		$lease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();
		
		return $lease;

	}

	/** @test */
	function user_can_view_undeposited_payments()
	{
	    $this->disableExceptionHandling();

	    $lease = $this->getLease();
		$undeposited = factory(App\Payment::class,20)->states('undeposited')->create(['lease_id' => $lease->id]);

		$response = $this->get('/deposits/undeposited');

		$response->assertStatus(200);
		$response->assertViewHas('payments');
		$response->assertSee((string) $undeposited->sum('amount'));
	    
	}

	/** @test */
	function user_can_view_historical_bank_deposits()
	{
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();

		$deposits = factory(App\BankDeposit::class,50)->create();

		$response = $this->actingAs($admin)->get('/deposits/undeposited');

		$response->assertStatus(200);
	}


	/** @test */
	function user_can_deposit_undeposited_payments()
	{
	    // $this->disableExceptionHandling();

	    $user = factory(App\User::class)->create(['is_admin' => 1]);
	    $lease = $this->getLease();
		$undeposited = factory(App\Payment::class,10)->states('undeposited')->create(['lease_id' => $lease->id]);	

		$response = $this->post('/deposits',[
				'user_id' => $user->id,
				'deposit_date' => Carbon::now()->format('n/j/Y'),
				'amount' => $undeposited->sum('amount'),
				'payment_id' => $undeposited->pluck('id')
			]);	

		$deposit = BankDeposit::whereRaw("DATE(deposit_date) = DATE('".Carbon::now(). "')")
			->where('amount',$undeposited->sum('amount'))
			->where('user_id',$user->id)
			->first();
		
		//Assert that this Deposit was created for the correct amount.
		$this->assertNotNull($deposit);

		//Assert that all payments were deposited.
		$this->assertEquals(10,$deposit->payments->count());

		$response->assertStatus(302);
		$response->assertRedirect('/deposits');
		$response->assertSessionHas('status','Deposit Added!');
	    
	}
}