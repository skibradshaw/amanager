<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function logged_in_user_can_view_all_users()
    {
        $this->disableExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->get('/users');

        $response->assertStatus(200);
        $response->assertViewHas('users');
    }

    /** @test */
    function logged_in_user_can_create_user()
    {
        
        $this->disableExceptionHandling();
        $user = factory(User::class)->create();

        $response = $this->actingAs($user)->post('/users', [
                'firstname' => 'Tim',
                'lastname' => 'Bradshaw',
                'email' => 'john@johndoe.com',
                'password' => 'jackass',
                'phone' => '3076904269'
            ]);

        //Assert that a new user was created with given form fields
        $new_user = User::where('email', 'john@johndoe.com')->first();
        $this->assertNotNull($new_user);
        $this->assertEquals('Tim', $new_user->firstname);
        $this->assertEquals('Bradshaw', $new_user->lastname);
    }

    /** @test */
    function logged_in_user_can_edit_user()
    {
        $this->disableExceptionHandling();
        $authenticatedUser = factory(User::class)->create();

        $userToEdit = factory(User::class)->create(['active' => 1]);

        $this->assertEquals(1, $userToEdit->active);

        //Change Password and Active Status
        $response = $this->actingAs($authenticatedUser)->put('/users/'.$userToEdit->id, [
                'email' => 'johndoe@doe.com',
                'active' => 0
            ]);
        // dd($this->app['session.store']);
        $newUser = User::find($userToEdit->id);
        // dd($response);
        $this->assertEquals('johndoe@doe.com', $newUser->email);
        $this->assertEquals(0, $newUser->active);
        $response->assertSessionHas('status', 'User Updated!');
    }

    /** @test */
    function logged_in_user_can_delete_user()
    {
        $authenticatedUser = factory(User::class)->create();

        $userToDelete = factory(User::class)->create();

        $response = $this->actingAs($authenticatedUser)->delete('users/'.$userToDelete->id);

        $newUser = User::find($userToDelete->id);
        // $deletedUser = User::withTrashed()->find($userToDelete->id);

        //Assert the Deleted User was really soft deleted
        // $this->assertNotNull($deletedUser);
        $this->assertNull($newUser);
        $response->assertStatus(200);
    }
}
