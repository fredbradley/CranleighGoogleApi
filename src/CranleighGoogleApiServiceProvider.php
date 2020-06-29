<?php

namespace fredbradley\CranleighGoogleApi;

use fredbradley\CranleighGoogleApi\Commands\RemoveAndRefreshGoogleToken;
use Illuminate\Support\ServiceProvider;

class CranleighGoogleApiServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'fredbradley');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'fredbradley');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        // Publishing is only necessary when using the CLI.
        if ($this->app->runningInConsole()) {
            $this->bootForConsole();
        }
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/cranleighgoogleapi.php', 'cranleighgoogleapi');

        // Register the service the package provides.
        $this->app->singleton('cranleighgoogleapi', function ($app) {
            return new CranleighGoogleApi;
        });
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return ['cranleighgoogleapi'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole()
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../config/cranleighgoogleapi.php' => config_path('cranleighgoogleapi.php'),
        ], 'cranleighgoogleapi.config');

        // Publishing the views.
        /*$this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/fredbradley'),
        ], 'cranleighgoogleapi.views');*/

        // Publishing assets.
        /*$this->publishes([
            __DIR__.'/../resources/assets' => public_path('vendor/fredbradley'),
        ], 'cranleighgoogleapi.views');*/

        // Publishing the translation files.
        /*$this->publishes([
            __DIR__.'/../resources/lang' => resource_path('lang/vendor/fredbradley'),
        ], 'cranleighgoogleapi.views');*/

        // Registering package commands.
        $this->commands([
            RemoveAndRefreshGoogleToken::class,
        ]);
    }
}
