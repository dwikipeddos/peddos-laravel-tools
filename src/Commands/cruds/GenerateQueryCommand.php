<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\QueryGenerateable;
use Illuminate\Console\Command;

class GenerateQueryCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:query {name} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate query';

    /**
     * Generateable
     */
    protected QueryGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');

        $this->generateable = new QueryGenerateable($name, $domain);
        $this->generateable->generate();

        $this->info("Query $name has been fully generated!");
    }
}