<?php

namespace Dwikipeddos\PeddosLaravelTools\Traits;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;

trait LoginThrottle
{
    public function ensureNotRateLimited()
    {
        if (RateLimiter::tooManyAttempts($this->throttleKey(), config('peddoslaraveltools.max_login_attempt'))) {
            throw ValidationException::withMessages([
                'email' => __('auth.throttle', ['seconds' => RateLimiter::availableIn($this->throttleKey())]),
            ]);
        }
        RateLimiter::hit($this->throttleKey());
    }
}
