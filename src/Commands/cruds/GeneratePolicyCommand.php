<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\PolicyGenerateable;
use Illuminate\Console\Command;

class GeneratePolicyCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:policy {name} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate policy';

    /**
     * Generateable
     */
    protected PolicyGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');

        $this->generateable = new PolicyGenerateable($name, $domain);
        $this->generateable->generate();

        $this->info("Policy $name has been fully generated!");
    }

}