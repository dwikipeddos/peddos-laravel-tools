<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;

class FactoryGenerateable extends Generateable
{
    public string $baseNamespace = 'database\factories';
    public string $domainNamespace = 'App\Domains\{DomainName}\database\factories';
    function getFileContent(): string
    {
        $content = File::get(dirname(__DIR__, 2) . '/stubs/Factory.stub');
        $content = str_replace(
            ['{Name}','{name}','{Namespace}','{ModelPath}'],
            [$this->name, strtolower($this->name), $this->getNamespace(), $this->getModelPath()],
            $content);     
        return $content;
    }

    function generate() : void
    {
        $this->generateFile($this->name.'Factory');
    }
}