@extends('layouts.app')

@section('title', 'Profile')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold mb-6">My Profile</h2>

        <div class="mb-8">
            <div class="flex items-center space-x-4">
                @if(session('user')['avatar'])
                    <img src="{{ asset('storage/' . session('user')['avatar']) }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover">
                @else
                    <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center">
                        <span class="text-gray-600 text-3xl">{{ substr(session('user')['name'], 0, 1) }}</span>
                    </div>
                @endif
                <div>
                    <h3 class="text-xl font-semibold">{{ session('user')['name'] }}</h3>
                    <p class="text-gray-600">{{ session('user')['email'] }}</p>
                    <span class="inline-block px-2 py-1 text-xs rounded-full {{ session('user')['role'] === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800' }}">
                        {{ ucfirst(session('user')['role']) }}
                    </span>
                </div>
            </div>
        </div>

        <hr class="my-6">

        <form method="POST" action="/profile" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Name</label>
                <input type="text" name="name" value="{{ session('user')['name'] }}" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 mb-2">Email</label>
                <input type="email" name="email" value="{{ session('user')['email'] }}" required
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('email')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 mb-2">Avatar</label>
                <input type="file" name="avatar" accept="image/*"
                       class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                @error('avatar')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700 transition">
                Update Profile
            </button>
        </form>

        @if(session('user')['avatar'])
            <form method="POST" action="/profile/avatar" class="mt-4">
                @csrf
                @method('DELETE')
                <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700 transition">
                    Remove Avatar
                </button>
            </form>
        @endif
    </div>
</div>
@endsection
