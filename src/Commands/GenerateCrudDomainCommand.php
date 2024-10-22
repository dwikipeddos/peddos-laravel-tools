<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Illuminate\Console\Command;

class GenerateCrudDomainCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:crud {name} {--domain=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate all with default CRUD function';

    /**
     * Execute the console command.
     * 
     * @return int
     */
    public function handle()
    {              
        try{
            $name = $this->argument('name');
            $domain = $this->option('domain');
            $params['name'] = $name;

            if($domain!=null) {
                $this->call('make:domain', ['name' => $domain]);
                $params['--domain'] = $domain;
            }
            
            $this->info("generating migration");
            $this->call('generate:migration', $params);

            $this->info("generating model");
            $this->call('generate:model', $params);

            $this->info("generating Factory");
            $this->call('generate:factory', $params);

            $this->info("generating query");
            $this->call('generate:query', $params);

            $this->info("generating policy");
            $this->call('generate:policy', $params);

            $this->info("generating store request");
            $this->call('generate:request', $params + ['--type'=>'store']);

            $this->info("generating update request");
            $this->call('generate:request', $params + ['--type'=>'update']);

            $this->info("generating delete request");
            $this->call('generate:request', $params + ['--type'=>'delete']);

            $this->info("generating query request");
            $this->call('generate:request', $params + ['--type'=>'query']);

            $this->info("generating show request");
            $this->call('generate:request', $params + ['--type'=>'show']);

            $this->info("generating controller");
            $this->call('generate:controller', $params);
    
            return Command::SUCCESS;
        }catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
