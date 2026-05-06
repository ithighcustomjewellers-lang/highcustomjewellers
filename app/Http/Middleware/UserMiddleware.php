<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            $user = auth()->user();

            // 🔥 STATUS CHECK (MOST IMPORTANT)
            if ($user->status !== 'active') {

                Auth::logout();

                return redirect()->route('login')
                    ->with('error', 'Your account is inactive');
            }

            // 🔥 USER ROLE CHECK
            if ($user->is_admin == 0) {
                return $next($request);
            }

            // admin ko yaha allow nahi karna
            return redirect()->route('admin-dashboard');
        }

        return redirect()->route('login');
    }
}
