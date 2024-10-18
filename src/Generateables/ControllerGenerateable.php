<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;

class ControllerGenerateable extends Generateable
{
    public string $baseNamespace = 'App\Http\Controllers';
    public string $domainNamespace = 'App\Domains\{DomainName}\Controllers';

    function getFileContent(): string
    {
        $content = File::get(dirname(__DIR__, 2) . '/stubs/Controller.stub');
        $content = str_replace(
            ['{Name}','{name}','{Namespace}','{RequestPath}','{ModelPath}','{QueryPath}'],
            [$this->name, strtolower($this->name),$this->getNamespace(), $this->getRequestPath(), $this->getModelPath(), $this->getQueryPath()],
            $content);     

        return $content;
    }    

    function generate() : void
    {
        $this->generateFile($this->name . 'Controller');
    }
}