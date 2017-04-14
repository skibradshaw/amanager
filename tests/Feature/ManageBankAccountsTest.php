<?php
use App\BankAccount;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;


class ManageBankAccountsTest extends TestCase
{
	use DatabaseMigrations;


	/** @test */
	function user_can_view_all_bank_accounts()
	{
	    $this->disableExceptionHandling();

	    $admin = $this->getAdminUser();
	    $bankAccounts = factory(BankAccount::class,5)->create();

	    $response = $this->actingAs($admin)->get('/admin/bank_accounts');

	    // dd($response);
	    // dd($bankAccounts->toArray());
	    // dd($bankAccounts->pluck('name')->toArray());
	    // $data = json_decode($this->response->getContent(),true);
	    // $response->assertJson($bankAccounts->pluck('name')->toArray());
	    $response->assertStatus(200);
	    $response->assertViewHas('bankAccounts');

	}

	/** @test */
	function user_can_view_a_form_to_create_a_bank_account()
	{
	    // $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
	    $response = $this->actingAs($admin)->get('/admin/bank_accounts/create');

	    $response->assertStatus(200);

	}

	/** @test */
	function user_can_create_bank_account()
	{

		$this->disableExceptionHandling();

		$admin = $this->getAdminUser();
		$response = $this->actingAs($admin)->post('/admin/bank_accounts',[
				'name' => 'Bank Account 1',
			]);
	    
	    // dd($response);
	    $bankAccount = BankAccount::where('name','Bank Account 1')->first();
	    $this->assertNotNull($bankAccount);
	    $response->assertStatus(302);
	    $response->assertRedirect('/admin/bank_accounts');
	}

	/** @test */
	function user_can_view_form_to_edit_bank_account()
	{
	    
		// $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
	    $bankAccount = factory(BankAccount::class)->create();

	    $response = $this->actingAs($admin)->get('/admin/bank_accounts/'.$bankAccount->id.'/edit');

	    $response->assertStatus(200);
	}

	/** @test */
	function user_can_modify_bank_account_name()
	{
	    // $this->disableExceptionHandling();
	    $admin = $this->getAdminUser();

	    $bankAccount = factory(BankAccount::class)->create();

	    $response = $this->actingAs($admin)->put('/admin/bank_accounts/'.$bankAccount->id,[
	    		'name' => 'First Interstate Bank Account'
	    	]);

	    $newBankAccount = BankAccount::find($bankAccount->id);
	    $this->assertEquals('First Interstate Bank Account',$newBankAccount->name);
	    $response->assertStatus(302);
	    $response->assertRedirect('/admin/bank_accounts');

	}

	/** @test */
	function user_can_delete_a_bank_account()
	{
	    // $this->disableExceptionHandling();
		$admin = $this->getAdminUser();
	    $bankAccount = factory(BankAccount::class)->create();

	    $response = $this->actingAs($admin)->delete('/admin/bank_accounts/'.$bankAccount->id);

	    $newBankAccount = BankAccount::find($bankAccount->id);

	    $this->assertNull($newBankAccount);
	    $response->assertStatus(302);
	    $response->assertSessionHas('status','Bank Account Deleted!');
	    $response->assertRedirect('/admin/bank_accounts');
	}
    
}