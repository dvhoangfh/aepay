<?php

namespace Dvhoangfh\Aepay;

use Dvhoangfh\Aepay\Commands\ReportOrder;
use Dvhoangfh\Aepay\Middleware\VerifyCsrfToken;
use Dvhoangfh\Aepay\Providers\EventServiceProvider;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AepayServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot(Router $router)
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'aepay');
        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'aepay');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__ . './../routes/web.php');
        $this->app->make('config')->set('logging.channels.log-webhook-paypal', [
            'driver'     => 'daily',
            'path'       => storage_path('log-webhook-paypal/laravel.log'),
            'level'      => 'info',
            'days'       => 60,
            'permission' => 0777,
        ]);
        $this->app->make('config')->set('logging.channels.log-webhook-bytepay', [
            'driver'     => 'daily',
            'path'       => storage_path('log-webhook-bytepay/laravel.log'),
            'level'      => 'info',
            'days'       => 60,
            'permission' => 0777,
        ]);
        $this->app->make('config')->set('logging.channels.log-webhook-sellix', [
            'driver'     => 'daily',
            'path'       => storage_path('log-webhook-sellix/laravel.log'),
            'level'      => 'info',
            'days'       => 60,
            'permission' => 0777,
        ]);
        $this->app->make('config')->set('logging.channels.log-webhook-wordpress', [
            'driver'     => 'daily',
            'path'       => storage_path('log-webhook-wordpress/laravel.log'),
            'level'      => 'info',
            'days'       => 60,
            'permission' => 0777,
        ]);
    
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('aepay.php'),
            ], 'config');
            
            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/aepay'),
            ], 'views');*/
            
            // Publishing assets.
            $this->publishes([
                __DIR__ . '/../public' => public_path('vendor/aepay'),
            ], 'assets');
            
            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/aepay'),
            ], 'lang');*/
            
            // Registering package commands.
             $this->commands([
                 ReportOrder::class
             ]);
        }
    }
    
    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->register(EventServiceProvider::class);
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'aepay');
        $this->mergeConfigFrom(
            __DIR__.'/../config/services.php',
            'services'
        );
        $this->mergeConfigFrom(
            __DIR__.'/../config/logging.php',
            'logging'
        );
        
        // Register the main class to use with the facade
        $this->app->singleton('aepay', function () {
            return new Aepay;
        });
    }
}
