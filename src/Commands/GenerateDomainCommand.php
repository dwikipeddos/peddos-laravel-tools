<?php


namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Dwikipeddos\PeddosLaravelTools\Actions\AutoloadDomainAction;
use Dwikipeddos\PeddosLaravelTools\Exceptions\DomainAlreadyExistsException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class GenerateDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:domain {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate an empty domain';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $domainName = $this->argument('name');
        $basePath = app_path("Domains/{$domainName}");

        // Check if the domain already exists
        if (File::exists($basePath)) {
            $this->error("Domain {$domainName} already exists!");
            return;
        }

        // Create the base directory for the domain
        File::makeDirectory($basePath, 0755, true);
        $this->info("Domain {$domainName} created successfully!");

        // Create default subdirectories
        $subdirectories = ['Models', 'Controllers', 'Queries', 'Requests', 'Routes','Action','Enum'];
        foreach ($subdirectories as $subdir) {
            File::makeDirectory("{$basePath}/{$subdir}", 0755, true);
            $this->info("Created: {$basePath}/{$subdir}");
        }

        // Add autoloading to composer.json
        $this->addAutoloading($domainName);
    }

    function addAutoLoading($domainName): void {
        try{
            (new AutoloadDomainAction())->execute("App\\domains\\$domainName\\","app/domains/$domainName/");
            $this->info("Autoloading for domain $domainName added to composer.json");
        }catch(DomainAlreadyExistsException $de){
            $this->info("Domain Already Exists");
        }catch(\Exception $e){
            $this->error("Failed to add autoloading for domain $domainName ".$e->getMessage());
        }
    }
}