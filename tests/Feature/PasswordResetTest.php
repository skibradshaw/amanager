<?php

namespace Tests\Feature;

use App\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use DatabaseMigrations;


    public function sendNotification($user)
    {
        Notification::fake();

        $reset = $this->call('POST', '/password/email',[
                'email' => $user->email,
            ],[], [], ['HTTP_REFERER' => '/password/email']);

        $token = '';

        Notification::assertSentTo(
            $user,
            ResetPassword::class,
            function ($notification, $channels) use (&$token) {
                $token = $notification->token;

                return true;
            });

        return $token;
    }


    /** @test */
    public function user_can_view_send_password_reset_link_form()
    {
        $response = $this->call('GET','/password/reset');

        $response->assertSee('Reset Password');        
    }

    /** @test */
    public function user_can_send_password_reset_link()
    {
        // $this->seed('DatabaseSeeder');
        $user = factory(\App\User::class)->create([
                'email' => 'tim@alltrips.com'
            ]);

        // Notification::fake();

        $response = $this->call('POST', '/password/email',[
                'email' => $user->email,
            ],[], [], ['HTTP_REFERER' => '/password/email']);
        // dd($response);
        // dd($this->app['session.store']);
        // Notification::assertSentTo($user, ResetPasswordNotification::class);
        $response->assertStatus(302);
        $response->assertRedirect('/password/email');
        // $response->assertSessionHasErrors('email');
        $response->assertSessionHas('status','We have e-mailed your password reset link!');
        
        
    }

    /** @test */
    public function user_can_view_password_reset_form()
    {
        $user = factory(\App\User::class)->create([
                'email' => 'tim@alltrips.com'
            ]);

        $token = $this->sendNotification($user);

        $user->fresh();

        $response = $this->get('/password/reset/'.$token);
        $response->assertStatus(200);
        // $response->assertSee('')

        
    }

    /** @test */
    public function user_can_reset_password()
    {

        $this->disableExceptionHandling();
        
        $user = factory(\App\User::class)->create([
                'email' => 'tim@alltrips.com',
                'password' => 'jackass'
            ]);

        $token = $this->sendNotification($user);
        
        $new_password = 'testingpassword';
        // dd($user);
        $response = $this->post('/password/reset',[
                'email' => $user->email,
                'token' => $token,
                'password' => $new_password,
                'password_confirmation' => $new_password

            ]);

        // $response->dump();
        $response->assertSessionHas('status','Your password has been reset!');
        $response->assertStatus(302);
        $response->assertRedirect('/');
        // dd($response);
        // dd($this->app['session.store']);
        // $this->assertTrue(true);
        // $refresh_user = User::where('email',$user->email)->first();
        // // $user->fresh();
        // $response->assertStatus(302);
        // // $response->assertEqual(\Hash::make($new_password),$refresh_user->password);
        // $response->assertRedirect('/');

    }    

}
