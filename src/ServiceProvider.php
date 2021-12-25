<?php

namespace AdDirector;

use AdDirector\GPT\AdGPT;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/ad-director.php' => config_path('ad-director.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/ad-director'),
            ], 'views');


            $this->commands([
                //
            ]);
        }

        $this->loadViewsFrom(__DIR__.'/../resources/views', 'ad-director');

        $this->app->singleton('ad-gpt-manager', function ($app) {
            return new AdGPT();
        });

        $this->app->singleton('ad-director', function ($app) {
            return new AdDirector();
        });
    }

    /**
     * @inheritDoc
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/ad-director.php', 'ad-director');
    }
}
