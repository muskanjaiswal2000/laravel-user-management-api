<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminSessionAuth
{
    public function handle(Request $request, Closure $next)
    {
        $user = session('user');

        if (!session('api_token') || !$user || ($user['role'] ?? null) !== 'admin') {
            return redirect('/login');
        }

        return $next($request);
    }
}
