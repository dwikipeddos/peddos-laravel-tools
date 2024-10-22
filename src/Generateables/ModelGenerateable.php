<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;

class ModelGenerateable extends Generateable
{
    public string $baseNamespace = 'App\Models';
    public string $domainNamespace = 'App\Domains\{DomainName}\Models';

    function getFileContent(): string
    {
        $content = File::get(dirname(__DIR__, 2) . '/stubs/Model.stub');
        $content = str_replace(
            ['{Name}','{name}','{Namespace}','{FactoryPath}'],
            [$this->name, strtolower($this->name),$this->getNamespace(), $this->getFactoryPath()],
            $content);     

        return $content;
    }    

    function generate() : void
    {
        $this->generateFile($this->name);
    }
}