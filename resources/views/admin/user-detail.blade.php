@extends('layouts.app')

@section('title', 'User Details')

@section('content')
<div class="max-w-2xl mx-auto">
    <h2 class="text-3xl font-bold mb-6">User Details</h2>

    <div class="bg-white rounded-lg shadow-md p-8">
        <div class="flex items-center space-x-4 mb-6">
            @if($user['avatar'])
                <img src="{{ $user['avatar'] }}" alt="Avatar" class="w-24 h-24 rounded-full object-cover">
            @else
                <div class="w-24 h-24 rounded-full bg-gray-300 flex items-center justify-center">
                    <span class="text-gray-600 text-3xl">{{ substr($user['name'], 0, 1) }}</span>
                </div>
            @endif
            <div>
                <h3 class="text-xl font-semibold">{{ $user['name'] }}</h3>
                <p class="text-gray-600">{{ $user['email'] }}</p>
            </div>
        </div>

        <div class="space-y-2">
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">ID:</span>
                <span>{{ $user['id'] }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Name:</span>
                <span>{{ $user['name'] }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Email:</span>
                <span>{{ $user['email'] }}</span>
            </div>
            <div class="flex justify-between py-2 border-b">
                <span class="text-gray-600">Created At:</span>
                <span>{{ $user['created_at'] }}</span>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="/admin/users" class="text-blue-600 hover:underline">Back to Users</a>
    </div>
</div>
@endsection
