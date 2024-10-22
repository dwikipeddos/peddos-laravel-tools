<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Illuminate\Console\Command;
use Dwikipeddos\PeddosLaravelTools\Generateables\EnumGenerateable;

class GenerateEnumCommand extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'generate:enum {name} {--domain=}';

    /**
     * The console command description.
     */
    protected $description = 'Generate enum';

    /**
     * Generateable
     */
    protected EnumGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle() 
    {
        $name = $this->argument('name');
        $domain = $this->option('domain');

        $this->generateable = new EnumGenerateable($name, $domain);
        $this->generateable->generate();

        $this->info("Enum $name has been fully generated!");
    }
}