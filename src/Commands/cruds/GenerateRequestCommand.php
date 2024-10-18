<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\RequestGenerateable;
use Illuminate\Console\Command;

class GenerateRequestCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:request {name} {--type=} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate an empty request';

    /**
     * Generateable
     */
    protected RequestGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');
        $type = $this->option('type');  

        $this->generateable = new RequestGenerateable($name, $domain, $type);
        $this->generateable->generate();

        $this->info("Request $name has been fully generated!");
    }
}