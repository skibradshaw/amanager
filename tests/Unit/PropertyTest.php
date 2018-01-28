<?php
use App\Apartment;
use App\Payment;
use App\Property;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PropertyTest extends TestCase
{
    use DatabaseMigrations;
    /** @test */
    function can_get_active_properties()
    {
        $active = factory(Property::class, 50)->states('active')->create();
        $inactive = factory(Property::class, 50)->states('inactive')->create();

        $properties = Property::active()->get();
        $this->assertGreaterThan(0, $properties->count());
        $this->assertEquals($active->count(), $properties->count());
    }

    /** @test */
    function can_get_total_unpaid_rents_for_property()
    {
        $property = factory(Property::class)->create();
        for ($i = 0; $i < 5; $i++) {
            $apartment = factory(Apartment::class)->create(['property_id' => $property->id]);
            $start = Carbon::parse('first day of last month')->format('n/j/Y');
            $end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

            $this->createLease($apartment, [
                    'start' => $start,
                    'end' => $end,
                    'monthly_rent' => 1000,
                    'pet_rent' => 150,
                    'deposit' => 2000,
                    'pet_deposit' => 150
                ]);


             // . $property->unpaidRent() . "\n";
        }
        //There are no payments so Unpaid rent is monthly_rent + pet_rent X 2 months
        //There are 5 leases created
        //100000+15000 X 2 = 230000 X 5 = 1150000
        $this->assertEquals(1150000, $property->unpaidRent());
        $this->assertEquals('$11,500.00', $property->unpaid_rent_in_dollars);
    }

    /** @test */
    function can_get_total_unpaid_security_deposits_for_property()
    {
        $property = factory(Property::class)->create();
        for ($i = 0; $i < 5; $i++) {
            $apartment = factory(Apartment::class)->create(['property_id' => $property->id]);
            $start = Carbon::parse('first day of last month')->format('n/j/Y');
            $end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

            $this->createLease($apartment, [
                    'start' => $start,
                    'end' => $end,
                    'monthly_rent' => 1000,
                    'pet_rent' => 150,
                    'deposit' => 2000,
                    'pet_deposit' => 150
                ]);


             // . $property->unpaidRent() . "\n";
        }
        //There are no payments so Unpaid Deposits is deposit + pet_deposit
        //There are 5 leases created
        //200000 + 15000 X 5 = 1075000
        $this->assertEquals(1075000, $property->unpaidDeposits());
        $this->assertEquals('$10,750.00', $property->unpaid_deposits_in_dollars);
    }

    /** @test */
    function can_get_the_total_undeposited_funds_for_the_property()
    {
        $property = factory(Property::class)->create();
        for ($i = 0; $i < 5; $i++) {
            $apartment = factory(Apartment::class)->create(['property_id' => $property->id]);
            $start = Carbon::parse('first day of last month')->format('n/j/Y');
            $end = Carbon::parse('first day of next month')->addYear()->subDay()->format('n/j/Y');

            $tenant = factory(Tenant::class)->create();

            $this->createLease($apartment, [
                    'start' => $start,
                    'end' => $end,
                    'apartment_id' => $apartment->id,
                    'monthly_rent' => 1000,
                    'pet_rent' => 150,
                    'deposit' => 2000,
                    'pet_deposit' => 150
                ]);

            $lease = $apartment->leases()->where('start', Carbon::parse($start))->where('end', Carbon::parse($end))->first();
            $lease->tenants()->attach($tenant->id);
            $payments = factory(Payment::class, 5)
            ->states('undeposited')
            ->create([
                'lease_id' => $lease->id,
                'tenant_id' => $tenant->id,
                'amount' => 10000
                ]);
        }
        //Assert that undeposited funds = 5 Leases with 5 Payments of $100.00
        $this->assertEquals(250000, $property->undepositedFunds());
        $this->assertEquals('$2,500.00', $property->undeposited_funds_in_dollars);
    }

    /** @test */
    // function active_scope_only_returns_active_properties()
    // {
    // 	$property = factory(Property::class)->state('inactive')->create();

    // 	$properties = Property::active()->get();

                
    // }
}
