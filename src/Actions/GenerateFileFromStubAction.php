<?php

namespace Dwikipeddos\PeddosLaravelTools\Actions;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class GenerateFileFromStubAction
{
    public function execute(string $stub, string $filenameSuffix, string $fileLocation, string $name, string|null $filename = null): void
    {
        $className = ucfirst($name) . $filenameSuffix;

        $stubPath = dirname(__DIR__, 2) . '/stubs/';
        $content = File::get($stubPath . "$stub.stub");

        $modelpath = "App\\Models";
        if(strpos($fileLocation, '\\Models\\') !== false) {
            $pos = strpos($fileLocation, "\\Domains\\") + strlen("\\Domains\\"); // Find the position after "Domains\"
            $modelpath = "App\\".substr($fileLocation, $pos)."\\Models";
        }

        $content = str_replace('{name}', Str::lower($name), $content); // replace {name} with lowercase name
        $content = str_replace('{Name}', ucfirst($name), $content); // replace {Name} with uppercase name
        $content = str_replace('{Path}', rtrim(str_replace('/','\\',$fileLocation), '\\'), $content); // replace {Path} with file location
        $content = str_replace('{namep}', Str::plural($name), $content); // replace {namep} with plural name
        $content = str_replace('{ModelPath}', $modelpath, $content); // replace {namef} with singular name

        File::ensureDirectoryExists($fileLocation);
        $name = $filename ? $filename : $className;
        File::put($fileLocation . $name . '.php', $content);
    }
}
