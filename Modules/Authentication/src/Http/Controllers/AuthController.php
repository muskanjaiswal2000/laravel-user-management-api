<?php

namespace Modules\Authentication\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\DB;
use Modules\Authentication\Services\AuthService;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $result = $this->authService->register($validated);

        return response()->json([
            'success' => true,
            'message' => 'User registered successfully',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token']
            ]
        ], 201);
    }

    public function login(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        $key = 'login:' . strtolower($validated['email']) . ':' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many login attempts. Please try again later.',
                'errors' => []
            ], 429);
        }

        $result = $this->authService->login($validated['email'], $validated['password']);

        if (!$result) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                ]
            ], 401);
        }

        RateLimiter::clear($key);
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'data' => [
                'user' => $result['user'],
                'token' => $result['token']
            ]
        ], 200);
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request->user());

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ], 200);
    }

    public function forgotPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email'
        ]);

        $key = 'forgot-password:' . $validated['email'];

        if (RateLimiter::tooManyAttempts($key, 3)) {
            return response()->json([
                'success' => false,
                'message' => 'Too many attempts. Please try again later.',
                'errors' => []
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $exists = DB::table('users')->where('email', $validated['email'])->exists();
        if ($exists) {
            $otp = $this->authService->generateOTP($validated['email']);

            Mail::raw("Your OTP is: {$otp}. It expires in 5 minutes.", function ($message) use ($validated) {
                $message->to($validated['email'])
                    ->subject('Password Reset OTP');
            });
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP sent to your email'
        ], 200);
    }

    public function verifyOTP(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'otp' => 'required|string|size:6'
        ]);

        $resetToken = $this->authService->verifyOTP($validated['email'], $validated['otp']);

        if (!$resetToken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired OTP',
                'errors' => [
                    'otp' => ['The OTP is invalid or has expired.']
                ]
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'OTP verified successfully',
            'data' => [
                'reset_token' => $resetToken
            ]
        ], 200);
    }

    public function resetPassword(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|string|email',
            'reset_token' => 'required|string',
            'password' => 'required|string|min:8|confirmed'
        ]);

        $success = $this->authService->resetPassword(
            $validated['email'],
            $validated['reset_token'],
            $validated['password']
        );

        if (!$success) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid or expired reset token',
                'errors' => [
                    'reset_token' => ['The reset token is invalid or has expired.']
                ]
            ], 422);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully'
        ], 200);
    }
}
