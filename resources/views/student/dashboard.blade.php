<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
@include('student.layouts.navigation')

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="h-14 sm:h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
        <div class="flex items-center gap-2 sm:gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <div>
                <h1 class="text-base sm:text-lg font-semibold text-gray-900">Welcome, Dawit</h1>
            </div>
        </div>
        <div class="flex items-center gap-2 sm:gap-4">
            <!-- Trust Score Badge -->
            <div class="hidden sm:flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-sm font-medium">Trust: +3</span>
            </div>
            <!-- Notifications -->
            <button class="relative text-gray-400 hover:text-gray-600 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">2</span>
            </button>
        </div>
    </header>

    <!-- Dashboard Content -->
    <main class="flex-1 p-4 sm:p-6">
        <!-- Stats Cards -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">My Lost Items</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">3</p>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">My Found Items</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">2</p>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Items Returned</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">1</p>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs sm:text-sm text-gray-500">Active Matches</p>
                        <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">2</p>
                    </div>
                    <div class="w-9 h-9 sm:w-10 sm:h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

    
        <!-- Main Grid -->
        <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
            <!-- My Items -->
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base">My Items</h2>
                    <div class="flex gap-2">
                        <button class="text-xs font-medium text-primary-600 hover:text-primary-700">All</button>
                        <button class="text-xs font-medium text-gray-400 hover:text-gray-600">Lost</button>
                        <button class="text-xs font-medium text-gray-400 hover:text-gray-600">Found</button>
                    </div>
                </div>
                <div class="divide-y divide-gray-100">
                    
                </div>
            </div>

            <!-- Right Column -->
            <div class="space-y-4 sm:space-y-6">
                <!-- Recent Notifications -->
                <div class="bg-white rounded-xl border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Notifications</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        <div class="p-3 sm:p-4 bg-primary-50">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Potential Match Found</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Review and verify ownership details in My Matches.</p>
                                    <p class="text-xs text-gray-400 mt-1">2 hours ago</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 bg-green-50 text-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Item Verified</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Your found item "Keys" is now active</p>
                                    <p class="text-xs text-gray-400 mt-1">Yesterday</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-3 sm:p-4">
                            <div class="flex gap-3">
                                <div class="w-8 h-8 bg-blue-50 text-blue-600 rounded-full flex items-center justify-center flex-shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Claim Received</p>
                                    <p class="text-xs text-gray-600 mt-0.5">Someone claimed your found item</p>
                                    <p class="text-xs text-gray-400 mt-1">2 days ago</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Trust Score -->
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base mb-4">Trust Score</h2>
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-green-50 text-green-600 rounded-full flex items-center justify-center flex-shrink-0">
                            <span class="text-xl sm:text-2xl font-bold">+3</span>
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Trusted User</p>
                            <p class="text-xs text-gray-500">Good standing in community</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Matched Items Section -->
        <div class="mt-4 sm:mt-6 bg-white rounded-xl border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Potential Matches for Your Items</h2>
            </div>
            <div class="p-3 sm:p-4 grid sm:grid-cols-2 gap-3 sm:gap-4">
                
            </div>
        </div>
    </main>
</div>

<script src="{{asset('js/index.js')}}"></script>
</body>
</html>
