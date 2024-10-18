<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Dwikipeddos\PeddosLaravelTools\Actions\GenerateFileFromStubAction;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;


/**
 * @deprecated
 */
class GenerateCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:crud {name} {--domain=} {--api}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all with default CRUD function';

    /**
     * Define the domain that will be used.
     */
    protected string $domainPath;

    /**
     * Action for generating the file.
     */
    protected GenerateFileFromStubAction $action;

    /**
     * Execute the console command.
     */
    public function handle(GenerateFileFromStubAction $action)
    {
        $this->action = $action;
        $domain = $this->option('domain');
        $this->domainPath = $domain ? app_path("Domains/$domain/"):app_path("");
        try {
            $name = $this->argument('name');
            $this->generateModel($name);
            $this->generatePolicy($name);
            $this->generateRequests($name);
            $this->generateQuery($name);
            $this->generateController($name);
            $this->generateMigration($name);
            $this->generateModel($name);
            $this->generateFactory($name);
            $this->generateRoute($name);
            $this->info("$name has been fully generated!");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    function generateModel(string $name): void //need more logic
    {
        $this->action->execute("Model","", $this->domainPath."Models/", $name);   
    }

    function generateFactory(string $name) :void {
        $path = $this->domainPath=="" ? "database/factories/" : $this->domainPath."factories/";
        $this->action->execute("Factory", "Factory", $path, $name);
    }

    function generateMigration(string $name): void {
        $timestamp = date('Y_m_d_His');
        $filename = $timestamp . '_' . strtolower(Str::plural($name)) . '_table';
        $path = $this->domainPath=="" ? "database/migrations/" : $this->domainPath."Migrations/";
        $this->action->execute("Migration", "Migration", $path, $name, $filename);
    }

    function generateController(string $name): void
    {
        $path = $this->domainPath=="" ? "Http/" : $this->domainPath;
        $this->action->execute("Controller", "Controller", $path."Controllers/", $name);
    }

    function generateRequests(string $name): void
    {
        $path = $this->domainPath=="" ? $this->domainPath :"Http/";
        $path = "$path/Requests/";
        $this->action->execute("StoreRequest", "StoreRequest", $path, $name);
        $this->action->execute("UpdateRequest", "UpdateRequest", $path, $name);
    }

    function generatePolicy(string $name): void
    {
        $this->action->execute("Policy", "Policy", $this->domainPath."Policies/", $name);
    }

    function generateQuery(string $name): void
    {
        $this->action->execute("Query", "Query", $this->domainPath."Queries/", $name);
    }

    function generateRoute(string $name): void
    {
        $route = "Route::apiResource('" . Str::lower($name) . "', " . ucfirst($name) . "Controller::class);";
        $apiRoutesPath = base_path('routes/api.php'); // Path to the API routes file
        if(File::exists($apiRoutesPath)){
            $content = File::get($apiRoutesPath); // Read the content of the API routes file
            $newContent = $content . "\n" . $route; // Append the custom route to the existing content
            File::put($apiRoutesPath, $newContent); // Write the updated content back to the API routes file
            $this->info('Route created');
        }else{
            $this->error('API routes file not found');
        }
    }

    function generateFileFromStub(string $stub, string $filenameSuffix, string $fileLocation, string $name): void
    {
        $this->info($fileLocation);

        // $className = ucfirst($name) . $filenameSuffix;

        // $stubPath = dirname(__DIR__, 2) . '/stubs/';
        // $content = File::get($stubPath . "$stub.stub");

        // $content = str_replace('{name}', Str::lower($name), $content); // replace {name} with lowercase name
        // $content = str_replace('{Name}', ucfirst($name), $content); // replace {Name} with uppercase name
        // $content = str_replace('{Path}', $fileLocation, $content); // replace {Path} with file location
        // $content = str_replace('{namep}', Str::plural($name), $content); // replace {namep} with plural name

        // File::ensureDirectoryExists(app_path($fileLocation));
        // File::put(app_path("$fileLocation" . $className . '.php'), $content);
        // $this->info($className . "generated $className successfully!");
    }
}
