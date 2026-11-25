<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AdminWebController extends Controller
{
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!session('user') || session('user')['role'] !== 'admin') {
                return redirect('/login')->with('error', 'Access denied');
            }
            return $next($request);
        });
    }

    public function dashboard()
    {
        $token = session('api_token');

        $response = Http::withToken($token)->get(config('app.url') . '/api/admin/dashboard');

        if ($response->successful()) {
            $stats = $response->json('data');
            return view('admin.dashboard', compact('stats'));
        }

        return back()->with('error', 'Failed to load dashboard');
    }

    public function users(Request $request)
    {
        $token = session('api_token');

        $queryParams = [
            'per_page' => $request->get('per_page', 10)
        ];

        if ($request->has('search')) {
            $queryParams['search'] = $request->search;
        }

        $response = Http::withToken($token)->get(config('app.url') . '/api/admin/users', $queryParams);

        if ($response->successful()) {
            $users = $response->json('data');
            return view('admin.users', compact('users'));
        }

        return back()->with('error', 'Failed to load users');
    }

    public function showUser($id)
    {
        $token = session('api_token');

        $response = Http::withToken($token)->get(config('app.url') . '/api/admin/users/' . $id);

        if ($response->successful()) {
            $user = $response->json('data');
            return view('admin.user-detail', compact('user'));
        }

        return back()->with('error', 'User not found');
    }
}
