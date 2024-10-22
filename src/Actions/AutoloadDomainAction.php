<?php

namespace Dwikipeddos\PeddosLaravelTools\Actions;

use Dwikipeddos\PeddosLaravelTools\Exceptions\DomainAlreadyExistsException;

class AutoloadDomainAction {
    public function execute($key,$value)
    {
        $composerPath = base_path('composer.json');
        $composerJson = json_decode(file_get_contents($composerPath), true);

        // Check if the domain already exists
        throw_If(isset($composerJson['autoload']['psr-4'][$key]), new DomainAlreadyExistsException("Domain $key already exists!"));

        // Add the new domain namespace
        $composerJson['autoload']['psr-4'][$key] = $value;

        file_put_contents($composerPath, json_encode($composerJson, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT));
        
        // Run composer dump-autoload
        exec('composer dump-autoload');
    }
}