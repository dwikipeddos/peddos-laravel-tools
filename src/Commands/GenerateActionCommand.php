<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Dwikipeddos\PeddosLaravelTools\Actions\GenerateFileFromStubAction;
use Exception;
use Illuminate\Console\Command;

class GenerateActionCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:action {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate action';

    /**
     * Execute the console command.
     */
    public function handle(GenerateFileFromStubAction $action)
    {
        try {
            $name = $this->argument('name');
            $action->execute("Action", "Action", "Actions/", $name);
            $this->info("$name has been fully generated!");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
