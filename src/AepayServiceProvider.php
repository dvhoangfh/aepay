<?php

namespace Dvhoangfh\Aepay;

use Dvhoangfh\Aepay\Middleware\VerifyCsrfToken;
use Dvhoangfh\Aepay\Providers\EventServiceProvider;
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
    
        $router->middlewareGroup('web', [
            VerifyCsrfToken::class
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
            // $this->commands([]);
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
        
        // Register the main class to use with the facade
        $this->app->singleton('aepay', function () {
            return new Aepay;
        });
    }
}
