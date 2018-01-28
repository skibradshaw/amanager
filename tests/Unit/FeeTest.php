<?php
use App\Apartment;
use App\Fee;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class FeeTest extends TestCase
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
    function amount_is_required_to_create_fee()
    {

        // $this->disableExceptionHandling();

        $lease = $this->getLease();

        $this->response = $this->json('POST', '/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees', [
                'item_name' => collect(Fee::$types)->random(),
                'note' => 'Fake Fee is assessed',
                // 'amount' => 5000,
                'due_date' => '5/1/17'
            ]);
        
        // dd($this->response);
        $data = json_decode($this->response->getContent(), true);
        // dd($data);

        $fee = $lease->fees()->where('amount', 5000)->where('due_date', Carbon::parse('5/1/17'))->first();
        
        $this->assertArrayHasKey('amount', $data['errors']);
        $this->assertNull($fee);
        $this->response->assertStatus(422);
    }

    /** @test */
    function due_date_is_required_to_create_fee()
    {

        // $this->disableExceptionHandling();

        $lease = $this->getLease();

        $this->response = $this->json('POST', '/properties/'.$lease->apartment->property_id.'/apartments/'.$lease->apartment_id.'/leases/'.$lease->id . '/fees', [
                'item_name' => collect(Fee::$types)->random(),
                'note' => 'Fake Fee is assessed',
                'amount' => 5000,
                // 'due_date' => '5/1/17'
            ]);
        
        // dd($this->response);
        $data = json_decode($this->response->getContent(), true);
        // dd($data);

        $fee = $lease->fees()->where('amount', 5000)->where('due_date', Carbon::parse('5/1/17'))->first();
        
        $this->assertArrayHasKey('due_date', $data['errors']);
        $this->assertNull($fee);
        $this->response->assertStatus(422);
    }
}
