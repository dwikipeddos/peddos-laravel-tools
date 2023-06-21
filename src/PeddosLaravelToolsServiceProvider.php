<?php

namespace Dwikipeddos\PeddosLaravelTools;

use Illuminate\Support\ServiceProvider;

class PeddosLaravelToolsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->publishes([__DIR__ . '/../config/laraveltoolspermission.php' => config_path('laraveltoolspermission.php')], 'peddos-laravel-tools-config');
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'peddos-laravel-tools-migration');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
