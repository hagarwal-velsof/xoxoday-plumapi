<?php

namespace Xoxoday\Plumapi;

use Illuminate\Support\ServiceProvider;

class PlumAPIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
        $this->loadMigrationsFrom(__DIR__.'/Database/Migrations');
    }
}
