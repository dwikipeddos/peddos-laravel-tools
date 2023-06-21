<?php

namespace Dwikipeddos\PeddosLaravelTools;

use Illuminate\Support\ServiceProvider;

class PeddosLaravelTools extends ServiceProvider
{
    public function boot()
    {
        $this->publishes([__DIR__ . '/../config/PeddosPermissionConfig.php' => config_path('PeddosPermissionConfig.php')]);
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'migration');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
