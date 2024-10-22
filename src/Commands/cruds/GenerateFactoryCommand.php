<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands\cruds;

use Dwikipeddos\PeddosLaravelTools\Generateables\FactoryGenerateable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GenerateFactoryCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:factory {name} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Factory';

    /**
     * FactoryGenerateable instance
     * @var FactoryGenerateable
     */
    private FactoryGenerateable $generateable;

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name');   
        $domain = $this->option('domain'); 
        try{
            $this->generateable = new FactoryGenerateable($name, $domain);
            $this->generateable->generate();
        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }

    function getParameterPath() : ?string {
        $domain = $this->option('domain');

        return $domain == null ? 
        null : 
        "/app/domains/$domain/database/factories";
    }
}