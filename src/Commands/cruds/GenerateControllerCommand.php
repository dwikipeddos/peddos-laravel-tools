<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\ControllerGenerateable;
use Illuminate\Console\Command;

class GenerateControllerCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:controller {name} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate an empty controller';

    /**
     * Generateable
     */
    protected ControllerGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');

        $this->generateable = new ControllerGenerateable($name, $domain);
        $this->generateable->generate();

        $this->info("Controller $name has been fully generated!");
    }

}