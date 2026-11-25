@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-6">Admin Dashboard</h2>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-600">Total Users</h3>
            <p class="text-4xl font-bold text-blue-600">{{ $stats['total_users'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-600">Total Admins</h3>
            <p class="text-4xl font-bold text-purple-600">{{ $stats['total_admins'] }}</p>
        </div>

        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-600">Normal Users</h3>
            <p class="text-4xl font-bold text-green-600">{{ $stats['total_normal_users'] }}</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Quick Actions</h3>
        </div>

        <div class="space-y-2">
            <a href="/admin/users" class="block bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 transition text-center">
                View All Users
            </a>
        </div>
    </div>
</div>
@endsection
