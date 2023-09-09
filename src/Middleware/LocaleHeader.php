<?php

namespace Dwikipeddos\PeddosLaravelTools\Middleware;

use Closure;
use Illuminate\Http\Request;

class LocaleHeader
{
    public function handle(Request $request, Closure $next)
    {
        app()->setLocale($request->header('Accept-Language', 'en'));
        return $next($request);
    }
}
