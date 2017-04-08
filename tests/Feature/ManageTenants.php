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
	function user_can_view_create_form()
	{
		$this->disableExceptionHandling();

	    $response = $this->get('/tenants/create');

	    $response->assertStatus(200);
	    

	}

	/** @test */
	function user_can_create_a_tenant()
	{
		// $this->disableExceptionHandling();

		$response = $this->post('/tenants',[
				'firstname' => 'Mary Lynn',
				'lastname' => 'Bradshaw',
				'email' => 'skibradshaw@example.com',
				'password' => bcrypt('jackass70'),
				'phone' => '3076904269'
			]);	    

		$tenant = Tenant::where('email','skibradshaw@example.com')->first();

		$this->assertNotNull($tenant);
		$response->assertStatus(302);
		$response->assertRedirect('/tenants/'.$tenant->id);
	}    

	/** @test */
	function user_can_view_edit_form()
	{
		
		// $this->disableExceptionHandling();
		$tenant = factory(Tenant::class)->create();

		$response = $this->get('/tenants/'.$tenant->id.'/edit');

		$response->assertStatus(200);
		$response->assertViewHas('tenant');
		$response->assertSee($tenant->full_name);

	}

	/** @test */
	function user_can_update_tenant()
	{
		$this->disableExceptionHandling();
		$tenant = factory(Tenant::class)->create(['phone' => '3076904269']);

		$response = $this->put('/tenants/'.$tenant->id,['phone' => '3077335681']);
		// dd($response);
		$newTenant = Tenant::find($tenant->id);
		// dd($this->app['session.store']);
		$this->assertEquals('(307) 733-5681',$newTenant->phone);
		$response->assertStatus(302);
		$response->assertRedirect('/tenants');

	}

}