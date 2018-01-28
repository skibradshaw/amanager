<?php
use App\Apartment;
use App\Fee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ManageFeesTest extends TestCase
{
    use DatabaseMigrations;

    function getLease()
    {
        $apartment = factory(Apartment::class)->create();
        $start = Carbon::parse('first day of last month')->format('n/j/Y');
        $end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

        $this->createLease($apartment, [
                'start' => $start,
                'end' => $end,
                'apartment_id' => $apartment->id,
                'monthly_rent' => 100000,
                'pet_rent' => 15000,
                'deposit' => 200000,
                'pet_deposit' => 15000
            ]);

        $lease = $apartment->leases()->where('start', Carbon::parse($start))->where('end', Carbon::parse($end))->first();
        
        return $lease;
    }

    /** @test */
    function user_can_view_all_fees_on_a_lease()
    {
        $this->disableExceptionHandling();

        $lease = $this->getLease();
        $fees = factory(Fee::class, 10)->create([
                'lease_id' => $lease->id
            ]);

        $response = $this->get('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees');

        $response->assertStatus(200);
        $response->assertViewHas('fees');
    }

    /** @test */
    function user_can_view_form_to_add_fees()
    {
        $this->disableExceptionHandling();

        $lease = $this->getLease();

        $response = $this->get('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees/create');

        $response->assertStatus(200);
        $response->assertViewHas('lease');
        $response->assertViewHas('fee_types');
        $response->assertSee($lease->apartment->name);
    }

    /** @test */
    function user_can_assess_a_fee_on_a_given_lease()
    {

        $this->disableExceptionHandling();

        $lease = $this->getLease();
        $fee_date = Carbon::parse('first day of this month');

        $response = $this->post('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees', [
                'item_name' => collect(Fee::$types)->random(),
                'note' => 'Fake Fee is assessed',
                'amount' => 50.00,
                'due_date' => $fee_date->format('n/j/Y')
            ]);

        // dd($lease->fees);
        $fee = $lease->fees()->where('amount', 5000)->first();

        $this->assertNotNull($fee);
        $response->assertSessionHas('status', 'New fee has been assessed for $'.$fee->amount_in_dollars . ' due on ' .$fee->due_date->format('n/j/Y') . '!');
        $response->assertStatus(302);
        $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);
    }

    /** @test */
    function user_can_view_a_form_to_edit_fees()
    {
        $this->disableExceptionHandling();

        $lease = $this->getLease();
        $fee = factory(Fee::class)->create(['lease_id' => $lease->id]);

        $response = $this->get('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees/'.$fee->id ."/edit");

        $response->assertStatus(200);
        $response->assertViewHas('lease');
        $response->assertViewHas('fee_types');
        $response->assertSee($lease->apartment->name);
    }

    /** @test */
    function user_can_edit_a_fee()
    {
        $this->disableExceptionHandling();

        $lease = $this->getLease();
        $fee = factory(Fee::class)->create([
            'lease_id' => $lease->id,
            'amount' => 10000,
            'due_date' => Carbon::parse('5/1/2017')
            ]);

        //Change Amount and Due Date
        $response = $this->put('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees/'.$fee->id, [
                'amount' => 400.00,
                'due_date' => '4/1/2017'
            ]);

        $newFee = Fee::find($fee->id);

        $this->assertEquals(40000, $newFee->amount);
        $this->assertEquals('4/1/2017', $newFee->due_date->format('n/j/Y'));

        $response->assertStatus(302);
        $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);
        $response->assertSessionHas('status', 'Fee Updated!');
    }

    /** @test */
    function user_can_delete_a_fee()
    {
        $this->disableExceptionHandling();

        $lease = $this->getLease();
        $fee = factory(Fee::class)->create([
            'lease_id' => $lease->id,
            'amount' => 10000,
            'due_date' => Carbon::parse('5/1/2017')
            ]);

        $response = $this->delete('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees/'.$fee->id);

        $newFee = Fee::find($fee->id);

        $this->assertNull($newFee);

        $response->assertStatus(302);
        $response->assertRedirect('/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id);
        $response->assertSessionHas('status', 'Fee Deleted!');
    }
}
