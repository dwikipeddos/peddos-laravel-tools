<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateCrudCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:all {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all with default CRUD function';

    /**
     * Execute the console command.
     */

    public function handle()
    {
        try {
            $name = $this->argument('name');
            $this->generateModel($name);
            $this->generatePolicy($name);
            $this->generateRequests($name);
            $this->generateController($name);
            $this->info("$name has been fully generated!");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }

    function generateModel(string $name): void
    {
        Artisan::call('make:model', ['name' => ucfirst($name), '-m' => true, '-f' => true]);
    }

    function generateController(string $name): void
    {
        $this->generateFileFromStub("Controller", "Controller", "Http/Controllers/", $name);
    }

    function generateRequests(string $name): void
    {
        $this->generateFileFromStub("StoreRequest", "StoreRequest", "Http/Requests/", $name);
        $this->generateFileFromStub("UpdateRequest", "UpdateRequest", "Http/Requests/", $name);
    }

    function generatePolicy(string $name): void
    {
        $this->generateFileFromStub("Policy", "Policy", "Policies/", $name);
    }


    function generateFileFromStub(string $stub, string $filenameSuffix, string $fileLocation, string $name): void
    {
        $className = ucfirst($name) . $filenameSuffix;

        $stubPath = dirname(__DIR__, 2) . '/stubs/';
        // $content = File::get(base_path("stubs/$stub.stub"));
        // dd(['stub' => $stubPath, 'full' => $stubPath . "$stub.stub"]);
        $content = File::get($stubPath . "$stub.stub");


        $content = str_replace('{name}', Str::lower($name), $content);
        $content = str_replace('{Name}', ucfirst($name), $content);

        File::put(app_path("$fileLocation" . $className . '.php'), $content);
        $this->info($className . "generated $className successfully!");
    }
}
