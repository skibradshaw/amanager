<?php

namespace Tests;

use App\Exceptions\Handler;
use App\User;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    function getAdminUser()
    {
        return factory(User::class)->create(['is_admin' => 1]);
    }

    function createLease($apartment, $params)
    {
        
        $admin = $this->getAdminUser();
        $this->response = $this->actingAs($admin)->json('POST', '/properties/'.$apartment->property_id.'/apartments/'.$apartment->id.'/leases', $params);
    }

    protected function disableExceptionHandling()
    {
        $this->app->instance(ExceptionHandler::class, new class extends Handler {
            public function __construct()
            {
            }
            
            public function report(\Exception $e)
            {
                // no-op
            }
            
            public function render($request, \Exception $e)
            {
                throw $e;
            }
        });
    }
}
