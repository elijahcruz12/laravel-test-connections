<?php

namespace Elijahcruz\LaravelTestConnections;

use Elijahcruz\LaravelTestConnections\Console\TestConnectionsCommand;
use Illuminate\Support\ServiceProvider;

class LaravelTestConnectionsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-test-connections');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-test-connections');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('test-connections.php'),
            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-test-connections'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-test-connections'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-test-connections'),
            ], 'lang');*/

            // Registering package commands.
            if ($this->app->runningInConsole()) {
                $this->commands([
                    TestConnectionsCommand::class,
                ]);
            }
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'test-connections');

        // Register the main class to use with the facade
        $this->app->singleton('laravel-test-connections', function () {
            return new LaravelTestConnections;
        });
    }
}
