@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-8">
    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    <form method="POST" action="/login">
        @csrf

        <div class="mb-4">
            <label class="block text-gray-700 mb-2">Email</label>
            <input type="email" name="email" value="{{ old('email') }}" required
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('email')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2">Password</label>
            <input type="password" name="password" required
                   class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
            @error('password')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
            Login
        </button>
    </form>

    <div class="mt-4 text-center">
        <a href="/forgot-password" class="text-blue-600 hover:underline">Forgot Password?</a>
    </div>

    <div class="mt-4 text-center">
        <p>Don't have an account? <a href="/register" class="text-blue-600 hover:underline">Register</a></p>
    </div>
</div>
@endsection
