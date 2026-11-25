<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('api_token')) {
            return redirect('/login');
        }
        return $next($request);
    }
}
