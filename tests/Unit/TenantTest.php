<?php
use App\Lease;
use App\Tenant;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class TenantTest extends TestCase
{
	use DatabaseMigrations;

	/** @test */
	function can_get_tenant_full_name()
	{
	    $tenant = factory(Tenant::class)->make([
	    		'firstname' => 'Scott',
	    		'lastname' => 'Roop'
	    	]);

	    $fullname = $tenant->full_name;

	    $this->assertEquals('Scott Roop',$fullname);
	}

	/** @test */
	function can_get_formatted_phone()
	{
	    $tenant = factory(Tenant::class)->make([
	    		'phone' => '3076904269'
	    	]);

	    $formatted_phone = $tenant->phone;

	    $this->assertEquals('(307) 690-4269',$formatted_phone);
	}

	/** @test */
	function can_set_formatted_phone()
	{
		$tenant = factory(Tenant::class)->make([
    		'phone' => '(307) 690-4269'
    	]);	    

    	$formatted_phone = $tenant['attributes']['phone'];
		
		$this->assertEquals('3076904269',$formatted_phone);
	}

	/** @test */
	function can_get_active_tenants()
	{
		$this->disableExceptionHandling();

	    $lease = factory(Lease::class)->create([
    		'start' => Carbon::parse('-6 months'),
    		'end' => Carbon::parse('+1 month')
    	]);
		$tenantsWithLease = factory(Tenant::class,4)->create();
		$tenantsWithoutLease = factory(Tenant::class,20)->create();

		$lease->tenants()->attach($tenantsWithLease->pluck('id'));

		$tenantCount = Tenant::active()->get()->count();

		$this->assertEquals(4,$tenantCount);
	}	

	/** @test */
	function can_get_active_lease()
	{
		$this->disableExceptionHandling();

	    $lease = factory(Lease::class)->create([
    		'start' => Carbon::parse('-6 months'),
    		'end' => Carbon::parse('+1 month')
    	]);
	    $tenant = factory(Tenant::class)->create();

	    $lease->tenants()->attach($tenant->id);

	    $activeLease = $tenant->active_lease;

	    $this->assertEquals($lease->id, $activeLease->id);


	}
    
}