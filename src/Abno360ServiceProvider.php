<?php

namespace Abno\Abno360;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class Abno360ServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {

        $this->publishes([
            __DIR__.'/abno360.php' => config_path('abno360.php', 'config'),
        ]);
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'Abno360');
        // $this->publishes([
        //     __DIR__.'/resources/assets' => public_path('etims'),
        // ], 'assets');
        $this->loadMigrationsFrom(__DIR__.'/migrations');

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/abno360.php',
            'abno360'
        );
    }

}
