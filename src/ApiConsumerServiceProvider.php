<?php

namespace BlackBits\ApiConsumer;

use BlackBits\ApiConsumer\Commands\ApiConsumerEndpointMakeCommand;
use BlackBits\ApiConsumer\Commands\ApiConsumerMakeCommand;
use BlackBits\ApiConsumer\Commands\ApiConsumerShapeMakeCommand;
use Illuminate\Support\ServiceProvider;

class ApiConsumerServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/api-consumers.php' => config_path('api-consumers.php'),
            ], 'config');

            $this->commands([
                ApiConsumerMakeCommand::class,
                ApiConsumerEndpointMakeCommand::class,
                ApiConsumerShapeMakeCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/api-consumers.php', 'api-consumers');
    }
}
