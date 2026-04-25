<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Redirect user if not authenticated
     */
    protected function redirectTo(Request $request): ?string
    {
       if (! $request->expectsJson()) {
        return route('login'); // "/" route
    }

        return null;
    }
}
