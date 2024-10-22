<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\ModelGenerateable;
use Illuminate\Console\Command;

class GenerateModelCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:model {name} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate an empty model';

    /**
     * Generateable
     */
    protected ModelGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');

        $this->generateable = new ModelGenerateable($name, $domain);
        $this->generateable->generate();

        $this->info("Model $name has been fully generated!");
    }

}