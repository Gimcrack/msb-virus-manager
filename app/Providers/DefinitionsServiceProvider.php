<?php

namespace App\Providers;

use App\Definitions\Experiant;
use Illuminate\Support\ServiceProvider;
use App\Definitions\Contracts\Definitions;

class DefinitionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton( 'definitions', function($app) {
            return new Experiant;
        });
    }
}
