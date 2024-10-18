<?php

namespace Dwikipeddos\PeddosLaravelTools;

use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateActionCommand as CrudsGenerateActionCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateControllerCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateEnumCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateFactoryCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateMigrationCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateModelCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GeneratePolicyCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateQueryCommand as CrudsGenerateQueryCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\cruds\GenerateRequestCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\GenerateCrudDomainCommand;
use Dwikipeddos\PeddosLaravelTools\Commands\GenerateDomainCommand;
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
                // GenerateCrudCommand::class,
                // GenerateActionCommand::class,
                // GenerateQueryCommand::class,
                // GenerateEnumCommand::class,

                //new
                UpdatePermissionRoleCommand::class,

                CrudsGenerateActionCommand::class,
                GenerateControllerCommand::class,
                GenerateEnumCommand::class,
                GenerateFactoryCommand::class,
                GenerateMigrationCommand::class,
                GenerateModelCommand::class,
                GeneratePolicyCommand::class,
                CrudsGenerateQueryCommand::class,
                GenerateRequestCommand::class,
                
                GenerateDomainCommand::class,
                GenerateCrudDomainCommand::class
            ]);
        }
    }
}
