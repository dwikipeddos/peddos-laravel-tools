<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;

class QueryGenerateable extends Generateable
{
    public string $baseNamespace = 'App\Queries';
    public string $domainNamespace = 'App\Domains\{DomainName}\Queries';

    function getFileContent(): string
    {
        $content = File::get(dirname(__DIR__, 2) . '/stubs/Query.stub');
        $content = str_replace(
            ['{Name}','{name}','{Namespace}','{ModelPath}'],
            [$this->name, strtolower($this->name),$this->getNamespace(), $this->getModelPath()],
            $content);     

        return $content;
    }    


    function generate() : void
    {
        $this->generateFile($this->name . 'Query');
    }
}