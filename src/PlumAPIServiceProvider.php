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
        $this->loadMigrationsFrom(__DIR__ . '/Database/Migrations');
        if ($this->app->runningInConsole()) {
            // Publish assets
            $this->publishes([
                __DIR__ . '/Database/Seeders/XoplumApiSeeder.php' => database_path('Seeders/XoplumApiSeeder.php'),
                __DIR__ . '/config/xoplum.php' => config_path('xoplum.php'),
                __DIR__ . '/Jobs/PlumOrder.php' => app_path('Jobs\PlumOrder.php')
            ], 'Plum_files');
        }
    }
}
