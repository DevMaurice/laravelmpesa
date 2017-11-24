<?php

namespace Maurice\Mpesa;

use Maurice\Mpesa\Mpesa;
use Illuminate\Support\ServiceProvider;
use Laravel\Lumen\Application as LumenApplication;
use Illuminate\Foundation\Application as LaravelApplication;

class MpesaServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        $source = realpath(__DIR__.'/../config/mpesa.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('mpesa.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('mpesa');
        }

        $this->mergeConfigFrom($source, 'mpesa');
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mpesa', function ($app) {
            $config = $app->make('config')->get('mpesa');
            return new Mpesa();
        });

        $this->app->bind('mpesa', function () {
            return $this->app->make(\Maurice\Mpesa\Mpesa::class);
        });

    }
}
