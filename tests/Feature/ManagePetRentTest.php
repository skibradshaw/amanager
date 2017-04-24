<?php
use App\Apartment;
use App\Lease;
use App\LeaseDetail;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class ManagePetRentTest extends TestCase
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
                'pet_rent' => 0,
                'deposit' => 200000,
                'pet_deposit' => 15000	    		
	    	]);

		$lease = $apartment->leases()->where('start',Carbon::parse($start))->where('end',Carbon::parse($end))->first();
		
		return $lease;

	}

	/** @test */
	function user_can_view_pet_rent()
	{
		// $this->disableExceptionHandling();

		$lease = $this->getLease();

		$response = $this->get('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/pet_rent');

		// dd('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/pet_rent');
		$response->assertStatus(200);
		$response->assertViewHas('lease_details');

	}

	/** @test */
	function user_can_update_pet_rent_for_all_months()
	{
		$this->disableExceptionHandling();

		$lease = $this->getLease();

		$this->assertEquals(0,$lease->petrent_total);

		//Build Array of new pet rents
		$monthly_pet_rent = [];
		foreach($lease->details as $d)
		{
			$monthly_pet_rent[$d->id] = 100.00;
		}

		//Update all Lease Details with new pet rent;
		$response = $this->json('POST', '/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/pet_rent',[
				'details' => $lease->details->pluck('id'),
				'monthly_pet_rent' => $monthly_pet_rent,
				'pet_rent' => 100,
				'pet_deposit' => 500
			],['HTTP_REFERER' => '/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id]);
		// dd($response);
		
		//Assert that the new Pet Rent Total matches expected.
		$details = LeaseDetail::where('lease_id',$lease->id)->get();
		$newLease = Lease::find($lease->id);
		$newPetRentTotal = 10000*$details->sum('multiplier');

		$this->assertEquals($newPetRentTotal,$newLease->petrent_total);
		$this->assertEquals(50000,$newLease->pet_deposit);
		$response->assertStatus(302);
		$response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);

	}
	/** @test */
	function user_can_update_pet_rent_for_individual_month()
	{
		$this->disableExceptionHandling();

		$lease = $this->getLease();

		$detail = $lease->details->random();

		$response = $this->json('POST', '/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/single_pet_rent',[
				'detail_id' => $detail->id,
				'month_pet_rent' => 50000,
				'monthly_pet_rent' => 50000,
				'pet_deposit' => 25000,
			],['HTTP_REFERER' => '/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id]);

		$newDetail = LeaseDetail::find($detail->id);

		$this->assertEquals(50000,$newDetail->monthly_pet_rent);
		// $response->assertStatus(302);
		// $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);

	}	
    
}