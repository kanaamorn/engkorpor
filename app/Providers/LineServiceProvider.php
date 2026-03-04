<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class LineServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register LINE services as singletons
        $this->app->singleton(\App\Services\Line\LineClientService::class);
        $this->app->singleton(\App\Services\Line\LineValidatorService::class);
        $this->app->singleton(\App\Services\Line\LineEventHandlerService::class);

        // Register message builders
        $this->app->singleton(\App\Services\Line\MessageBuilders\MessageBuilderFactory::class);
    }

    public function boot()
    {
        //
    }
}

