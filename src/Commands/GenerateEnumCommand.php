<?php

namespace Dwikipeddos\PeddosLaravelTools\Commands;

use Dwikipeddos\PeddosLaravelTools\Actions\GenerateFileFromStubAction;
use Exception;
use Illuminate\Console\Command;

class GenerateEnumCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:enum {name}';

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
            $action->execute("Enum", "Enum", "Enums/", $name);
            $this->info("$name Enum has been fully generated!");
            return Command::SUCCESS;
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
