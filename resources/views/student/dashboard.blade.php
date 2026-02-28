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
<x-student-navigation/>

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <x-student-header title="Dashboard" trustScore="3" />

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
                    <div class="p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                    <span class="text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded">Lost</span>
                                    <span class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-0.5 rounded">Has Match</span>
                                </div>
                                <h3 class="font-medium text-gray-900 text-sm sm:text-base mb-1">iPhone 15 Pro Max</h3>
                                <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">Black case with student ID inside. Lost at Library 3rd floor.</p>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            Library
                                        </span>
                                    <span>Feb 20</span>
                                    <span class="text-amber-600 font-medium">Candidate ready</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 2 -->
                    <div class="p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                    <span class="text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded">Lost</span>
                                    <span class="text-xs font-medium bg-gray-100 text-gray-600 px-2 py-0.5 rounded">Active</span>
                                </div>
                                <h3 class="font-medium text-gray-900 text-sm sm:text-base mb-1">Chemistry Textbook</h3>
                                <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">4th edition, has my name on first page. Lost in Engineering Block.</p>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            Eng. Block
                                        </span>
                                    <span>Feb 18</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Item 3 -->
                    <div class="p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start gap-3">
                            <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                                <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                    <span class="text-xs font-medium bg-green-50 text-green-700 px-2 py-0.5 rounded">Found</span>
                                    <span class="text-xs font-medium bg-primary-50 text-primary-700 px-2 py-0.5 rounded">Claimed</span>
                                </div>
                                <h3 class="font-medium text-gray-900 text-sm sm:text-base mb-1">Set of Keys</h3>
                                <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">3 keys on a blue keychain. Found near cafeteria entrance.</p>
                                <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 text-xs text-gray-400">
                                        <span class="flex items-center gap-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                            </svg>
                                            Cafeteria
                                        </span>
                                    <span>Feb 21</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    
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
                <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-medium bg-primary-50 text-primary-700 px-2 py-0.5 rounded">Strong Candidate</span>
                        <span class="text-xs text-gray-400">Found 2h ago</span>
                    </div>
                    <div class="flex gap-3">
                        <div class="w-14 h-14 sm:w-16 sm:h-16 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-7 h-7 sm:w-8 sm:h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h4 class="font-medium text-gray-900 text-sm sm:text-base mb-1">iPhone 15 Pro Max</h4>
                            <p class="text-xs sm:text-sm text-gray-500 mb-3">Black case. Found at Cafeteria.</p>
                            <div class="flex gap-2">
                                <button class="flex-1 py-2 text-xs font-medium bg-primary-600 text-white hover:bg-primary-700 rounded transition-colors">
                                    Yes, It's Mine!
                                </button>
                                <button class="flex-1 py-2 text-xs font-medium border border-gray-300 text-gray-700 hover:bg-gray-50 rounded transition-colors">
                                    Not Mine
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<script src="{{asset('js/index.js')}}"></script>
</body>
</html>
