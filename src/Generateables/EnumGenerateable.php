<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;

class EnumGenerateable extends Generateable
{
    public string $baseNamespace = 'App\Enums';
    public string $domainNamespace = 'App\Domains\{DomainName}\Enums';

    function getFileContent(): string
    {
        $content = File::get(dirname(__DIR__, 2) . '/stubs/Enum.stub');
        $content = str_replace(
            ['{Name}','{name}','{Namespace}'],
            [$this->name, strtolower($this->name),$this->getNamespace()],
            $content);     

        return $content;
    }    

    function generate() : void
    {
        $this->generateFile($this->name);
    }
}