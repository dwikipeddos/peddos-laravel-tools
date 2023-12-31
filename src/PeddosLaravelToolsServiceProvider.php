<?php

namespace Dwikipeddos\PeddosLaravelTools;

use Dwikipeddos\PeddosLaravelTools\Commands\GenerateActionCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\GenerateCrudCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\GenerateEnumCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\GenerateQueryCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\UpdatePermissionRoleCommand;
use Illuminate\Support\ServiceProvider;

class PeddosLaravelToolsServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        //config
        $this->publishes([__DIR__ . '/../config/peddoslaraveltools.php' => config_path('peddoslaraveltools.php')], 'peddos-laravel-tools-config');

        //migrations
        $this->publishes([
            __DIR__ . '/../database/migrations/' => database_path('migrations')
        ], 'peddos-laravel-tools-migration');
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCrudCommand::class,
                UpdatePermissionRoleCommand::class,
                GenerateQueryCommand::class,
                GenerateActionCommand::class,
                GenerateEnumCommand::class,
            ]);
        }
    }
}
