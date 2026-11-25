@extends('layouts.app')

@section('title', 'Manage Users')

@section('content')
<div class="max-w-6xl mx-auto">
    <h2 class="text-3xl font-bold mb-6">Manage Users</h2>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="mb-4">
            <form method="GET" action="/admin/users" class="flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or email"
                       class="flex-1 px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition">
                    Search
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-4 py-3 text-left">ID</th>
                        <th class="px-4 py-3 text-left">Avatar</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Created At</th>
                        <th class="px-4 py-3 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr class="border-b">
                            <td class="px-4 py-3">{{ $user->id }}</td>
                            <td class="px-4 py-3">
                                @if($user->avatar)
                                    <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gray-300 flex items-center justify-center">
                                        <span class="text-gray-600">{{ substr($user->name, 0, 1) }}</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">{{ $user->name }}</td>
                            <td class="px-4 py-3">{{ $user->email }}</td>
                            <td class="px-4 py-3">{{ $user->created_at->format('Y-m-d H:i') }}</td>
                            <td class="px-4 py-3">
                                <a href="/admin/users/{{ $user->id }}" class="text-blue-600 hover:underline">View</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500">No users found</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <div class="mt-4">
        <a href="/admin/dashboard" class="text-blue-600 hover:underline">Back to Dashboard</a>
    </div>
</div>
@endsection
