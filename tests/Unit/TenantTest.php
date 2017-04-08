<?php
use App\Tenant;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class TenantTest extends TestCase
{

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
    
}