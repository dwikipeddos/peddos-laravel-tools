<?php

namespace Dwikipeddos\PeddosLaravelTools\abstracts;

use Illuminate\Support\Facades\File;

abstract class Generateable {
    public string $name;
    public string $baseNamespace;
    public string $domainNamespace;
    // public string $className;
    public ?string $domainName;

    function __construct(string $name, string $domainName = null) 
    {
        $this->domainName = $domainName;
        $this->name = $name;
    }

    function getNamespace() : string {
        if($this->domainName == null) {
            return $this->baseNamespace;
        } else {
            return str_replace('{DomainName}', $this->domainName, $this->domainNamespace);
        }
    }

    function getFactoryPath() : string {
        if($this->domainName == null) {
            return 'Database\Factories';
        } else {
            return str_replace('{DomainName}', $this->domainName, 'App\Domains\{DomainName}\database\factories');
        }
    }

    function getModelPath() : string {
        if($this->domainName == null) {
            return 'App\Models';
        } else {
            return str_replace('{DomainName}', $this->domainName, 'App\Domains\{DomainName}\Models');
        }
    }

    function getQueryPath():string {
        if($this->domainName == null) {
            return 'App\Queries';
        } else {
            return str_replace('{DomainName}', $this->domainName, 'App\Domains\{DomainName}\Queries');
        }
    }
    function getRequestPath():string {
        if($this->domainName == null) {
            return 'App\Http\Requests';
        } else {
            return str_replace('{DomainName}', $this->domainName, 'App\Domains\{DomainName}\Requests');
        }
    }

    function generateFile(?string $filename = null, ?string $fileLocation = null) : void {
        if($fileLocation == null) {
            $fileLocation = str_replace(['\\','App'],['/','app'],$this->getNamespace());
        }
        File::ensureDirectoryExists($fileLocation);
        $name = $filename ? $filename : $this->name;
        File::put($fileLocation .'/'. $name . '.php', $this->getFileContent());
    }

    abstract function getFileContent() : string;
    abstract function generate() : void;
}