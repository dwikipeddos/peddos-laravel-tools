<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\ActionGenerateable;
use Illuminate\Console\Command;

class GenerateActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:action {name} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate action';

    /**
     * Generateable
     */
    protected ActionGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');

        $this->generateable = new ActionGenerateable($name, $domain);
        $this->generateable->generate();

        $this->info("Action $name has been fully generated!");
    }
}