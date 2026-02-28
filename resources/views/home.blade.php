<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ASTU Lost & Found - Home</title>
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
<body class="bg-gray-50 text-gray-900 antialiased">

<!-- Navigation -->
<nav class="bg-white border-b border-gray-200 sticky top-0 z-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        <div class="flex justify-between items-center h-16">
            <!-- Logo -->
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <span class="font-semibold text-lg text-gray-900">ASTU Lost & Found</span>
            </div>

            <!-- Nav Links -->
            <div class="hidden sm:flex items-center gap-6">
                <a href="#how-it-works" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">How It Works</a>
                <a href="#features" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Features</a>
                <a href="/login" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">Login</a>
                <a href="/register" class="text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-lg transition-colors">
                    Get Started
                </a>
            </div>

            <!-- Mobile Menu Button -->
            <button id="mobile-menu-btn" class="sm:hidden p-2 text-gray-600 hover:text-gray-900">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>
    </div>

    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden sm:hidden border-t border-gray-200 bg-white">
        <div class="px-4 py-3 space-y-2">
            <a href="#how-it-works" class="block py-2 text-gray-600">How It Works</a>
            <a href="#features" class="block py-2 text-gray-600">Features</a>
            <a href="/login" class="block py-2 text-gray-600">Login</a>
            <a href="/register" class="block py-2 text-primary-600 font-medium">Get Started</a>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="pattern-bg">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-24">
        <div class="grid lg:grid-cols-2 gap-12 items-center">
            <div>
                <div class="inline-flex items-center gap-2 bg-primary-50 text-primary-700 text-sm font-medium px-3 py-1 rounded-full mb-6">
                    <span class="w-2 h-2 bg-primary-500 rounded-full"></span>
                    ASTU Campus Platform
                </div>
                <h1 class="text-4xl sm:text-5xl font-bold text-gray-900 leading-tight mb-6">
                    Lost Something?<br>
                    <span class="text-primary-600">We'll Help Find It.</span>
                </h1>
                <p class="text-lg text-gray-600 mb-8 max-w-lg">
                    Report lost items, discover found belongings, and connect with fellow students. Our smart matching system helps reunite you with your possessions quickly and securely.
                </p>
                <div class="flex flex-col sm:flex-row gap-4">
                    <a href="/register" class="inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white font-medium px-6 py-3 rounded-lg transition-colors">
                        Report Lost Item
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                    <a href="/register" class="inline-flex items-center justify-center gap-2 bg-white hover:bg-gray-50 text-gray-700 font-medium px-6 py-3 rounded-lg border border-gray-300 transition-colors">
                        Report Found Item
                    </a>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($itemsRecovered) }}</div>
                    <div class="text-sm text-gray-500">Items Recovered</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="text-3xl font-bold text-primary-600 mb-1">{{ $matchSuccessRate }}%</div>
                    <div class="text-sm text-gray-500">Match Success Rate</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ number_format($activeStudents) }}</div>
                    <div class="text-sm text-gray-500">Active Students</div>
                </div>
                <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm">
                    <div class="text-3xl font-bold text-gray-900 mb-1">{{ $avgResponseTimeLabel }}</div>
                    <div class="text-sm text-gray-500">Avg. Response Time</div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section id="how-it-works" class="bg-white border-y border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">How It Works</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">Three simple steps to recover your lost belongings or help a fellow student find theirs.</p>
        </div>

        <div class="grid md:grid-cols-3 gap-8">
            <!-- Step 1 -->
            <div class="relative">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-semibold">
                        1
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Report Your Item</h3>
                </div>
                <p class="text-gray-600 pl-14">
                    Submit details about your lost or found item including photos, location, and description. Our system verifies submissions for authenticity.
                </p>
            </div>

            <!-- Step 2 -->
            <div class="relative">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-semibold">
                        2
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Smart Matching</h3>
                </div>
                <p class="text-gray-600 pl-14">
                    An algorithm automatically matches lost items with found ones using category, location, date, and description similarity analysis.
                </p>
            </div>

            <!-- Step 3 -->
            <div class="relative">
                <div class="flex items-center gap-4 mb-4">
                    <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-semibold">
                        3
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900">Recover & Connect</h3>
                </div>
                <p class="text-gray-600 pl-14">
                    Once matched, verify ownership and arrange pickup. The system protects both parties throughout the process.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Features -->
<section id="features" class="bg-gray-50">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Built for ASTU Students</h2>
            <p class="text-gray-600 max-w-2xl mx-auto">A secure and trusted platform designed specifically for our campus community.</p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-200 transition-colors">
                <div class="w-11 h-11 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Verified Submissions</h3>
                <p class="text-gray-600 text-sm">Admin verification ensures all posts are legitimate, preventing fake claims and protecting users.</p>
            </div>

            <!-- Feature 2 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-200 transition-colors">
                <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Smart Matching</h3>
                <p class="text-gray-600 text-sm">80%+ similarity threshold with detailed breakdown by category, location, and description matching.</p>
            </div>

            <!-- Feature 3 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-200 transition-colors">
                <div class="w-11 h-11 bg-green-50 text-green-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Real-time Alerts</h3>
                <p class="text-gray-600 text-sm">Get notified instantly when a matching item is found or someone claims your found item.</p>
            </div>

            <!-- Feature 4 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-200 transition-colors">
                <div class="w-11 h-11 bg-purple-50 text-purple-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Trust Score System</h3>
                <p class="text-gray-600 text-sm">Reputation-based system rewards honest users and flags suspicious behavior automatically.</p>
            </div>

            <!-- Feature 5 -->
            <div class="bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-200 transition-colors">
                <div class="w-11 h-11 bg-rose-50 text-rose-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Photo Evidence</h3>
                <p class="text-gray-600 text-sm">Upload photos of found items for easy identification and faster matching with lost reports.</p>
            </div>

            <!-- Feature 6 -->
            {{-- <div class="bg-white rounded-xl border border-gray-200 p-6 hover:border-primary-200 transition-colors">
                <div class="w-11 h-11 bg-cyan-50 text-cyan-600 rounded-lg flex items-center justify-center mb-4">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 mb-2">Statistics Dashboard</h3>
                <p class="text-gray-600 text-sm">Track recovery rates, view trends, and monitor your item submissions all in one place.</p>
            </div> --}}
        </div>
    </div>
</section>

<!-- Recent Activity -->
<section class="bg-white border-t border-gray-200">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 mb-2">Recent Activity</h2>
                <p class="text-gray-600">Latest found items waiting for their owners</p>
            </div>
            <a href="{{ route('student.items') }}" class="text-primary-600 hover:text-primary-700 text-sm font-medium mt-4 sm:mt-0">
                View all items &rarr;
            </a>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($recentFoundItems as $item)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors">
                    <div class="aspect-video bg-gray-100 flex items-center justify-center overflow-hidden">
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-center gap-2 mb-2">
                            <span class="text-xs font-medium bg-green-50 text-green-700 px-2 py-0.5 rounded">Found</span>
                            <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                        <h3 class="font-semibold text-gray-900 mb-1">{{ $item->title }}</h3>
                        <p class="text-sm text-gray-500 mb-3">{{ \Illuminate\Support\Str::limit($item->description ?: ('Found at ' . $item->location . '.'), 90) }}</p>
                        <div class="flex items-center text-xs text-gray-400 gap-3">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                {{ $item->location }}
                            </span>
                            <span>{{ $item->category_label }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="sm:col-span-2 lg:col-span-3 rounded-xl border border-dashed border-gray-300 p-8 text-center">
                    <p class="text-sm text-gray-500">No active found items yet.</p>
                    <a href="/register" class="inline-flex mt-3 text-sm font-medium text-primary-600 hover:text-primary-700">Post the first found item</a>
                </div>
            @endforelse
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="bg-gray-900">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 py-16 sm:py-20">
        <div class="text-center">
            <h2 class="text-3xl font-bold text-white mb-4">Ready to Get Started?</h2>
            <p class="text-gray-400 mb-8 max-w-xl mx-auto">Join thousands of ASTU students who trust our platform to help recover lost items quickly and securely.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/register" class="inline-flex items-center justify-center gap-2 bg-primary-500 hover:bg-primary-600 text-white font-medium px-8 py-3 rounded-lg transition-colors">
                    Create Account
                </a>
                <a href="/login" class="inline-flex items-center justify-center gap-2 bg-gray-800 hover:bg-gray-700 text-white font-medium px-8 py-3 rounded-lg border border-gray-700 transition-colors">
                    Sign In
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="bg-white border-t border-gray-200 py-4">
    <div class="max-w-6xl mx-auto px-4 text-center text-sm text-gray-400">
        &copy; {{ date('Y') }} ASTU Lost & Found. All rights reserved.
    </div>
</footer>

<script>
    // Mobile menu toggle
    const mobileMenuBtn = document.getElementById('mobile-menu-btn');
    const mobileMenu = document.getElementById('mobile-menu');

    mobileMenuBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // Close mobile menu when clicking on links
    const mobileLinks = mobileMenu.querySelectorAll('a');
    mobileLinks.forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
        });
    });
</script>
</body>
</html>
