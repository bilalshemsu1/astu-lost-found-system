<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ASTU Lost & Found</title>
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
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active {
            background-color: #f0fdfa;
            color: #0d9488;
            border-right: 2px solid #0d9488;
        }
        @media (max-width: 1023px) {
            .sidebar-link.active {
                border-right: none;
                border-left: 2px solid #0d9488;
            }
        }
        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease;
        }
        .sidebar-panel {
            transition: transform 0.3s ease;
        }
        .sidebar-panel.closed {
            transform: translateX(-100%);
        }
        @media (min-width: 1024px) {
            .sidebar-panel.closed {
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
    
    <!-- Mobile Sidebar Overlay -->
    <div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="sidebar-panel closed fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 lg:translate-x-0 lg:z-auto">
        <!-- Logo -->
        <div class="h-16 flex items-center justify-between px-4 lg:px-6 border-b border-gray-200">
            <a href="index.html" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <span class="font-semibold text-gray-900">ASTU L&F</span>
            </a>
            <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="p-4 space-y-1 overflow-y-auto" style="height: calc(100vh - 8rem);">
            <a href="admin-dashboard.html" class="sidebar-link active flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                Dashboard
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                </svg>
                Pending Items
                <span class="ml-auto bg-amber-100 text-amber-700 text-xs font-medium px-2 py-0.5 rounded-full">12</span>
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
                Matches
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Claims
                <span class="ml-auto bg-green-100 text-green-700 text-xs font-medium px-2 py-0.5 rounded-full">3</span>
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                Users
            </a>
            <a href="#" class="sidebar-link flex items-center gap-3 px-3 py-2.5 text-sm font-medium text-gray-600 hover:bg-gray-50 transition-colors">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
                Statistics
            </a>
        </nav>

        <!-- User Section -->
        <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-semibold text-sm flex-shrink-0">
                    @php
                        $name = auth()->user()->name;  
                        $nameParts = explode(' ', $name);

                        $firstNameLetter = '';
                        $lastNameLetter = '';

                        if (count($nameParts) > 0) {
                            $firstNameLetter = $nameParts[0][0];

                            if (count($nameParts) > 1) {
                                $lastNameLetter = $nameParts[1][0];
                            }
                        }
                    @endphp
                    {{ $firstNameLetter, $lastNameLetter }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">{{auth()->user()->name}}</p>
                    <p class="text-xs text-gray-500 truncate">{{auth()->user()->email}}</p>
                </div>
                <a href="/logout" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <div class="lg:ml-64 min-h-screen flex flex-col">
        <!-- Top Bar -->
        <header class="h-14 sm:h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
            <div class="flex items-center gap-3">
                <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-base sm:text-lg font-semibold text-gray-900">Admin Dashboard</h1>
            </div>
            <button class="relative text-gray-400 hover:text-gray-600 p-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                </svg>
                <span class="absolute top-1 right-1 w-4 h-4 bg-red-500 text-white text-xs rounded-full flex items-center justify-center">5</span>
            </button>
        </header>

        <!-- Dashboard Content -->
        <main class="flex-1 p-4 sm:p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6 sm:mb-8">
                <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-red-50 text-red-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded">+12%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">45</p>
                    <p class="text-xs sm:text-sm text-gray-500">Lost Items</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-green-50 text-green-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded">+8%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">38</p>
                    <p class="text-xs sm:text-sm text-gray-500">Found Items</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-primary-50 text-primary-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline text-xs text-green-600 font-medium bg-green-50 px-2 py-1 rounded">89%</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">28</p>
                    <p class="text-xs sm:text-sm text-gray-500">Returned</p>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-6">
                    <div class="flex items-center justify-between mb-3 sm:mb-4">
                        <div class="w-9 h-9 sm:w-10 sm:h-10 bg-amber-50 text-amber-600 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="hidden sm:inline text-xs text-amber-600 font-medium bg-amber-50 px-2 py-1 rounded">12 new</span>
                    </div>
                    <p class="text-xl sm:text-2xl font-bold text-gray-900">12</p>
                    <p class="text-xs sm:text-sm text-gray-500">Pending</p>
                </div>
            </div>

            <!-- Main Grid -->
            <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
                <!-- Pending Items -->
                <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Items Pending Verification</h2>
                        <a href="#" class="text-xs sm:text-sm text-primary-600 hover:text-primary-700">View all</a>
                    </div>
                    <div class="divide-y divide-gray-100">
                        
                    </div>
                </div>

                <!-- Recent Matches -->
                <div class="bg-white rounded-xl border border-gray-200">
                    <div class="p-4 border-b border-gray-200">
                        <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Recent Matches</h2>
                    </div>
                    <div class="divide-y divide-gray-100">
                        
                    </div>
                </div>
            </div>

            <!-- Pending Claims Table -->
            <div class="mt-4 sm:mt-6 bg-white rounded-xl border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Pending Claims</h2>
                    <span class="text-xs text-gray-500">3 claims awaiting review</span>
                </div>

                <!-- Mobile Cards View -->
                <div class="lg:hidden divide-y divide-gray-100">
                    
                </div>
                <!-- Desktop Table View -->
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Item</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Claimant</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Match %</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Trust Score</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                           
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>

    <script>
        function openSidebar() {
            if (window.innerWidth >= 1024) return;
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.remove('closed');
            overlay.classList.remove('hidden');
            document.body.classList.add('overflow-hidden');
        }

        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');

            sidebar.classList.add('closed');
            overlay.classList.add('hidden');
            document.body.classList.remove('overflow-hidden');
        }

        function toggleSidebar() {
            if (window.innerWidth >= 1024) return;
            const sidebar = document.getElementById('sidebar');

            if (sidebar.classList.contains('closed')) {
                openSidebar();
            } else {
                closeSidebar();
            }
        }

        // Close sidebar when clicking a link on mobile
        document.querySelectorAll('.sidebar-link').forEach(link => {
            link.addEventListener('click', () => {
                if (window.innerWidth < 1024) {
                    closeSidebar();
                }
            });
        });

        window.addEventListener('resize', () => {
            if (window.innerWidth >= 1024) {
                closeSidebar();
            }
        });
    </script>
</body>
</html>
