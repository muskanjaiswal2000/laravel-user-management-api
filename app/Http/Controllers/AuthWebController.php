<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class AuthWebController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $response = Http::post(config('app.url') . '/api/login', [
            'email' => $request->email,
            'password' => $request->password
        ]);

        if ($response->successful()) {
            $data = $response->json();
            session(['api_token' => $data['data']['token']]);
            session(['user' => $data['data']['user']]);

            if ($data['data']['user']['role'] === 'admin') {
                return redirect('/admin/dashboard')->with('success', 'Login successful');
            }

            return redirect('/profile')->with('success', 'Login successful');
        }

        return back()->withErrors([
            'email' => 'Invalid credentials'
        ])->withInput();
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $response = Http::post(config('app.url') . '/api/register', [
            'name' => $request->name,
            'email' => $request->email,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation
        ]);

        if ($response->successful()) {
            return redirect('/login')->with('success', 'Registration successful. Please login.');
        }

        return back()->withErrors($response->json('errors'))->withInput();
    }

    public function logout(Request $request)
    {
        $token = session('api_token');

        if ($token) {
            Http::withToken($token)->post(config('app.url') . '/api/logout');
        }

        session()->forget(['api_token', 'user']);
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Logged out successfully');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $response = Http::post(config('app.url') . '/api/forgot-password', [
            'email' => $request->email
        ]);

        if ($response->successful()) {
            return redirect('/verify-otp')->with('success', 'OTP sent to your email')->with('email', $request->email);
        }

        return back()->withErrors($response->json('errors'))->withInput();
    }

    public function showVerifyOTP()
    {
        return view('auth.verify-otp');
    }

    public function verifyOTP(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string|size:6'
        ]);

        $response = Http::post(config('app.url') . '/api/verify-otp', [
            'email' => $request->email,
            'otp' => $request->otp
        ]);

        if ($response->successful()) {
            $data = $response->json();
            return redirect('/reset-password')->with('reset_token', $data['data']['reset_token'])->with('email', $request->email);
        }

        return back()->withErrors($response->json('errors'))->withInput();
    }

    public function showResetPassword()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $response = Http::post(config('app.url') . '/api/reset-password', [
            'email' => $request->email,
            'reset_token' => $request->reset_token,
            'password' => $request->password,
            'password_confirmation' => $request->password_confirmation
        ]);

        if ($response->successful()) {
            return redirect('/login')->with('success', 'Password reset successfully. Please login.');
        }

        return back()->withErrors($response->json('errors'))->withInput();
    }
}
