<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;

class ActionGenerateable extends Generateable
{
    public string $baseNamespace = 'App\Actions';
    public string $domainNamespace = 'App\Domains\{DomainName}\Actions';

    function getFileContent(): string
    {
        $content = File::get(dirname(__DIR__, 2) . '/stubs/Action.stub');
        $content = str_replace(
            ['{Name}','{name}','{Namespace}'],
            [$this->name, strtolower($this->name),$this->getNamespace()],
            $content);     

        return $content;
    }    

    function generate() : void
    {
        $this->generateFile($this->name.'Action');
    }
}