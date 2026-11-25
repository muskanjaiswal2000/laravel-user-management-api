<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'User Management')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white shadow-lg">
        <div class="max-w-7xl mx-auto px-4">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/" class="text-xl font-bold">User Management</a>
                </div>
                <div class="flex items-center space-x-4">
                    @if(session('user'))
                        <span>{{ session('user')['name'] }}</span>
                        @if(session('user')['role'] === 'admin')
                            <a href="/admin/dashboard" class="hover:text-blue-200">Admin</a>
                        @endif
                        <a href="/profile" class="hover:text-blue-200">Profile</a>
                        <form method="POST" action="/logout" class="inline">
                            @csrf
                            <button type="submit" class="hover:text-blue-200">Logout</button>
                        </form>
                    @else
                        <a href="/login" class="hover:text-blue-200">Login</a>
                        <a href="/register" class="hover:text-blue-200">Register</a>
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p>&copy; 2026 User Management System</p>
        </div>
    </footer>

    @stack('scripts')
</body>
</html>
