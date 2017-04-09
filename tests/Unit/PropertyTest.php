<?php
use App\Property;
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
		$active = factory(Property::class,50)->states('active')->create();
		$inactive = factory(Property::class,50)->states('inactive')->create();

	    $properties = Property::active()->get();
	    $this->assertGreaterThan(0,$properties->count());
	    $this->assertEquals($active->count(),$properties->count());
	}
	/** @test */
	// function active_scope_only_returns_active_properties()
	// {
	// 	$property = factory(Property::class)->state('inactive')->create();

	// 	$properties = Property::active()->get();

			    
	// }
    
}