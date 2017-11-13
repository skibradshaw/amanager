<?php
use App\Apartment;
use App\BankAccount;
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

		$response = $this->get('/reports/undeposited');

		$response->assertStatus(200);
		$response->assertViewHas('payments');
		$response->assertSee((string) money_format('%.2n',$undeposited->sum('amount')/100));
	    
	}


	/** @test */
	function user_can_review_and_select_rent_and_fee_payments_to_deposit()
	{
		$this->disableExceptionHandling();

		$user = factory(App\User::class)->create(['is_admin' => 1]);
		$lease = $this->getLease();
		$undeposited = factory(App\Payment::class,20)->states('undeposited')->create([
			'lease_id' => $lease->id,
			'payment_type' => 'Rent'

			]);
		$bankAccount = factory(App\BankAccount::class)->create(['property_id' => $lease->apartment->property_id]);	

		$response = $this->actingAs($user)->get('/reports/undeposited/'.$bankAccount->property_id.'/confirm?type=1');
		// dd($lease->apartment);
		$response->assertStatus(200);

		$response->assertViewHas('payments');	    
		$response->assertViewHas('bankAccounts');		
	}

	/** @test */
	function user_can_review_and_select_security_deposit_payments_to_deposit()
	{
		$this->disableExceptionHandling();

		$user = factory(App\User::class)->create(['is_admin' => 1]);
		$lease = $this->getLease();
		$undeposited = factory(App\Payment::class,20)->states('undeposited')->create([
			'lease_id' => $lease->id,
			'payment_type' => 'Security Deposit'

			]);
		$bankAccount = factory(App\BankAccount::class)->create(['property_id' => $lease->apartment->property_id]);	

		$response = $this->actingAs($user)->get('/reports/undeposited/'.$bankAccount->property_id.'/confirm?type=2');

		$response->assertStatus(200);

		$response->assertViewHas('payments');	    

		$response->assertViewHas('bankAccounts');		
	}
	/** @test */
	function user_can_deposit_undeposited_rent_and_fee_payments()
	{
	    $this->disableExceptionHandling();
	    $faker = Faker\Factory::create();;
	    $user = factory(App\User::class)->create(['is_admin' => 1]);
	    $lease = $this->getLease();
		$undeposited = factory(App\Payment::class,10)
			->states('undeposited')
			->create([
				'lease_id' => $lease->id,
				'amount' => $faker->randomNumber(4),
				]);
		$undepositedTotal = $undeposited->sum('amount');
		$bankAccount = factory(App\BankAccount::class)->create(['property_id' => $lease->apartment->property_id]);	

		$response = $this->actingAs($user)->post('/reports/undeposited/'.$bankAccount->property_id.'/confirm',[
				'user_id' => $user->id,
				'deposit_date' => Carbon::now()->format('n/j/Y'),
				'type' => 1,
				'amount' => $undeposited->sum('amount')/100,
				'payment_id' => $undeposited->pluck('id'),
				'bank_account_id' => $bankAccount->id
			]);	

		// dd($undeposited->sum('amount'));
		$deposit = BankDeposit::where('deposit_date',Carbon::parse(Carbon::now()->format('n/j/Y')))
			->where('amount',$undeposited->sum('amount'))
			->where('user_id',$user->id)
			->first();
		// $deposit = BankDeposit::first();
		// dd($response);
		// $data = json_decode($response->getContent(),true);

		// dd($data);		

		//Assert that this Deposit was created for the correct amount and the correct type.
		$this->assertNotNull($deposit);
		// $this->assertEquals(1,$deposit->deposit_type);
		$this->assertEquals('Rent & Fee Payments',$deposit->deposit_type);

		//Assert that all payments were deposited.
		$this->assertEquals(10,$deposit->payments->count());
		$this->assertEquals($undepositedTotal,$deposit->amount);
		$response->assertStatus(302);
		$response->assertRedirect('/admin/bank_accounts/'.$bankAccount->id);
		$response->assertSessionHas('status','Deposit Added!');	    
	}

	/** @test */
	function user_cannot_make_a_deposit_with_no_items()
	{
	    $this->disableExceptionHandling();

	    $user = factory(App\User::class)->create(['is_admin' => 1]);
	    $lease = $this->getLease();
		$undeposited = factory(App\Payment::class,10)
			->states('undeposited')
			->create([
				'lease_id' => $lease->id,
				'amount' => 10000
				]);
		$bankAccount = factory(App\BankAccount::class)->create();	

		$response = $this->actingAs($user)->post('/reports/undeposited/'.$bankAccount->property_id.'/confirm',[
				'user_id' => $user->id,
				'deposit_date' => Carbon::now()->format('n/j/Y'),
				'type' => 1,
				'amount' => 0,
				'payment_id' => $undeposited->pluck('id'),
				'bank_account_id' => $bankAccount->id
			]);		    

		$response->assertSessionHas('error','The total payments must match the Deposit Total.  Please try again!');
	}

	/** @test */
	function use_can_vew_all_payments_in_a_deposit()
	{
	    $this->disableExceptionHandling();
	    
	    $user = factory(App\User::class)->create(['is_admin' => 1]);
	    $lease = $this->getLease();
		$bankAccount = factory(App\BankAccount::class)->create();

	    $deposit = factory(App\BankDeposit::class)->create();
		$payments = factory(App\Payment::class,10)
			->create([
					'bank_deposit_id' => $deposit->id,
					'lease_id' => $lease->id
				]);
		$response = $this->get('/admin/bank_accounts/'.$bankAccount->id.'/deposits/'.$deposit->id);

		$response->assertStatus(200);
		$response->assertViewHas('payments');
	}

}