<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Str;

class GenerateMigrationCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:migration {name} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Migration';

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = Str::lower($this->argument('name'));
        try{
            $params['name'] = "create_".Str::plural($name)."_table";
            // $params['name'] = $name;
            // $path = $this->getParameterPath();
            // if($path != null) $params['--path'] = $path;
            Artisan::call('make:migration', $params);
            // $this->registerMigration($path);
            // $this->addToAutoload();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    // function getParameterPath() : ?string {
    //     $domain = $this->option('domain');

    //     return $domain == null ? 
    //     null : 
    //     "/app/Domains/$domain/database/migrations";
    // }

    // function registerMigration(?string $path):void {
    //     $domain = $this->option('domain');
    //     if($domain == null) return;
    // }

    // function addToAutoload() : void {
    //     try{
    //         $domainName = $this->option('domain');
    //         if($domainName == null) return;
    //         (new AutoloadDomainAction())->execute("App\\domains\\$domainName\\Migrations","app/domains/$domainName/migrations");
    //         shell_exec("composer dumpautoload");
    //     }catch (\Exception $e) {
    //         Log::info("trying to create autoload for $domainName : ".$e->getMessage());
    //     }
    // }
}