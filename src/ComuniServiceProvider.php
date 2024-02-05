<?php

namespace Axiostudio\Comuni;

use Illuminate\Support\ServiceProvider;

class ComuniServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadRoutesFrom(__DIR__.'/routes.php');

        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('comuni.php'),
            ], 'comuni-config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations'),
            ], 'comuni-migrations');

            $this->commands([
                \Axiostudio\Comuni\Commands\UpdateDatabaseCommand::class,
            ]);
        }
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'comuni');

        $this->app->singleton('comuni', function () {
            return new Comuni();
        });
    }
}
