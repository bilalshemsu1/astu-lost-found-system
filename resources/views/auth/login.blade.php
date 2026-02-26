<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body {
            font-family: 'Inter', sans-serif;
        }
        .pattern-bg {
            background-color: #fafafa;
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23e5e7eb' fill-opacity='0.4'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased min-h-screen flex flex-col">

<!-- Navigation -->
<nav class="bg-white border-b border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">
            <a href="/" class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <span class="font-semibold text-lg text-gray-900">ASTU Lost & Found</span>
            </a>

            <a href="/register" class="text-sm font-medium text-primary-600 hover:text-primary-700">
                Create an account
            </a>
        </div>
    </div>
</nav>

<!-- Main Content -->
<main class="flex-1 flex items-center justify-center py-12 pattern-bg">
    <div class="w-full max-w-md px-4">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Header -->
            <div class="px-6 pt-6 pb-4">
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Welcome Back</h1>
                <p class="text-gray-500 text-sm">Sign in to your account to continue</p>
            </div>

            <!-- Form -->
            <form id="loginForm" class="px-6 pb-6 space-y-5" novalidate action="{{route('login')}}" method="POST">
                @if(session('success'))
                    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                        {{ session('success') }}
                    </div>
                @endif
                
                @csrf
                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Email Address
                    </label>
                    <input
                            type="email"
                            id="email"
                            name="email"
                            class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                            placeholder="your.email@astu.edu.et"
                            required
                    >
                    @error('email')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            Password
                        </label>
                        <a href="#" class="text-sm text-primary-600 hover:text-primary-700">
                            Forgot password?
                        </a>
                    </div>
                    <div class="relative">
                        <input
                                type="password"
                                id="password"
                                name="password"
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors pr-10"
                                placeholder="Enter your password"
                                required
                        >
                        <button type="button" id="togglePassword" class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </button>
                    </div>
                    @error('password')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Submit Button -->
                <button
                        type="submit"
                        id="submitBtn"
                        class="w-full bg-primary-600 hover:bg-primary-700 text-white font-medium py-2.5 px-4 rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                    Sign In
                </button>
            </form>

        </div>

    </div>
</main>

<!-- Footer -->
<footer class="bg-white border-t border-gray-200 py-4">
    <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-400">
        © {{ date('Y') }} ASTU Lost & Found. All rights reserved.
    </div>
</footer>

<script>
    const passwordInput = document.getElementById('password');

    // Password toggle
    const togglePassword = document.getElementById('togglePassword');
    togglePassword.addEventListener('click', () => {
        const type = passwordInput.type === 'password' ? 'text' : 'password';
        passwordInput.type = type;
    });
</script>
</body>
</html>
