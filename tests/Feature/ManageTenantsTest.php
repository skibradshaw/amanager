<?php
use App\Tenant;
use Illuminate\Database\Eloquent\Faker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class ManageTenantsTest extends TestCase
{
    use DatabaseMigrations;

	/** @test */
	function user_can_view_all_tenants()
	{
	    $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();

	    $response = $this->actingAs($admin)->get('/tenants');

	    $response->assertStatus(200);
	    $response->assertViewHas('tenants');

	}

	/** @test */
	function user_can_view_create_form()
	{
		$this->disableExceptionHandling();
		$admin = $this->getAdminUser();

	    $response = $this->actingAs($admin)->get('/tenants/create');

	    $response->assertStatus(200);
	    

	}

	/** @test */
	function user_can_create_a_tenant()
	{
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		$response = $this->actingAs($admin)->post('/tenants',[
				'firstname' => 'Mary Lynn',
				'lastname' => 'Bradshaw',
				'email' => 'skibradshaw@example.com',
				'password' => bcrypt('jackass70'),
				'phone' => '3076904269'
			]);	    

		$tenant = Tenant::where('email','skibradshaw@example.com')->first();

		$this->assertNotNull($tenant);
		$response->assertStatus(302);
		$response->assertRedirect('/tenants/'.$tenant->id .'/edit');
	}    

	/** @test */
	function user_can_view_edit_form()
	{
		
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		$tenant = factory(Tenant::class)->create();

		$response = $this->actingAs($admin)->get('/tenants/'.$tenant->id.'/edit');

		$response->assertStatus(200);
		$response->assertViewHas('tenant');
		$response->assertSee($tenant->full_name);

	}

	/** @test */
	function user_can_update_tenant()
	{
		$this->disableExceptionHandling();
		$admin = $this->getAdminUser();
		
		$tenant = factory(Tenant::class)->create(['phone' => '3076904269']);

		$response = $this->actingAs($admin)->put('/tenants/'.$tenant->id,['phone' => '3077335681']);
		// dd($response);
		$newTenant = Tenant::find($tenant->id);
		// dd($this->app['session.store']);
		$this->assertEquals('(307) 733-5681',$newTenant->phone);
		$response->assertStatus(302);

	}

}