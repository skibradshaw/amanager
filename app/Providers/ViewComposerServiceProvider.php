<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        // Load Undeposited Payments to Nav Bar
        
        view()->composer('*', function ($view) {
            // $view->with('undepositedfunds',\App\Payment::whereRaw('bank_deposits_id IS NULL')->get()->sum('amount'));
            $navProperties = \App\Property::all();
            // $deposits_due = 0;
            // foreach ($properties as $p) {
            //     $rents_due += $p->rentBalance();
            //     $deposits_due += $p->depositBalance();
            // }
            
            // $view->with('rents_due',$rents_due);
            // $view->with('deposits_due',$deposits_due);
            $view->with('navProperties', $navProperties);
        });
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
