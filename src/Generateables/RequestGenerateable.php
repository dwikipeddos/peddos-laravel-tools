<?php

namespace Dwikipeddos\PeddosLaravelTools\Generateables;

use Dwikipeddos\PeddosLaravelTools\abstracts\Generateable;
use Illuminate\Support\Facades\File;    

class RequestGenerateable extends Generateable
{
    public string $baseNamespace = 'App\Http\Requests';
    public string $domainNamespace = 'App\Domains\{DomainName}\Requests';
    public string $requestType;
    public array $allowedRequestType = [
        'store',
        'update',
        'delete',
        'query',
        'show',
        'none',
    ];

    function __construct(string $name, string $domainName = null, ?string $requestType = null) {
        parent::__construct($name, $domainName);
        $this->requestType = $requestType;
    }

    function getStubFileName() : string{
        if($this->requestType == null) {
            return 'Request.stub';
        } else if(in_array($this->requestType, $this->allowedRequestType)){
            if($this->requestType == 'none'){
                return 'Request.stub';
            }else{
                return ucfirst($this->requestType).'Request.stub';
            }
        }else{
            throw new \Exception('Invalid request type');
        }
    }

    function getFileContent(): string
    {

        $content = File::get(dirname(__DIR__, 2) . '/stubs/'. $this->getStubFileName());
        $content = str_replace(
            ['{Name}','{name}','{Namespace}','{ModelPath}'],
            [$this->name, strtolower($this->name),$this->getNamespace(), $this->getModelPath()],
            $content);

        return $content;
    }

    function generate() : void
    {
        $this->generateFile($this->name.ucfirst($this->requestType).'Request');
    }
}