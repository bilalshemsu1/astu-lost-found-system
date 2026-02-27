<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Items - ASTU Lost & Found</title>
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
    <x-student-header title="Items Collection" trustScore="3" />

    <!-- Page Content -->
    <main class="flex-1 p-4 sm:p-6">
        <!-- Search Bar -->
        <div class="bg-white rounded-xl border border-gray-200 p-4 mb-4 sm:mb-6">
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <svg class="w-5 h-5 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input
                            type="text"
                            id="searchInput"
                            placeholder="Search by item name..."
                            class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                    >
                </div>
                <button onclick="toggleFilters()" class="flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    <span class="text-sm font-medium">Filters</span>
                </button>
            </div>

            <!-- Filters Panel -->
            <div id="filtersPanel" class="hidden mt-4 pt-4 border-t border-gray-200">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Type Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Type</label>
                        <div class="flex gap-2">
                            <button class="filter-btn active flex-1 px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg transition-colors" data-type="all">All</button>
                            <button class="filter-btn flex-1 px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors" data-type="lost">Lost</button>
                            <button class="filter-btn flex-1 px-3 py-2 text-xs font-medium border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 transition-colors" data-type="found">Found</button>
                        </div>
                    </div>

                    <!-- Category Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Category</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            <option value="electronics">Electronics</option>
                            <option value="books">Books</option>
                            <option value="keys">Keys</option>
                            <option value="cards">ID Cards</option>
                            <option value="clothing">Clothing</option>
                            <option value="bags">Bags</option>
                            <option value="other">Other</option>
                        </select>
                    </div>

                    <!-- Date Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Date Range</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Any Time</option>
                            <option value="today">Today</option>
                            <option value="week">This Week</option>
                            <option value="month">This Month</option>
                        </select>
                    </div>

                    <!-- Location Filter -->
                    <div>
                        <label class="block text-xs font-medium text-gray-500 uppercase mb-2">Location</label>
                        <select class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Locations</option>
                            <option value="library">Library</option>
                            <option value="cafeteria">Cafeteria</option>
                            <option value="engineering">Engineering Block</option>
                            <option value="admin">Admin Building</option>
                            <option value="sports">Sports Complex</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Header -->
        <div class="flex items-center justify-between mb-4">
            <p class="text-sm text-gray-500">Showing <span class="font-medium text-gray-900">24</span> items</p>
            <select class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option>Most Recent</option>
                <option>Oldest First</option>
            </select>
        </div>

        <!-- Items Grid -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Item Card 1 - Found Item -->
            <div onclick="openItemModal('found', 'iPhone 15 Pro', 'Electronics', 'Campus', '2 hours ago')" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">
                <div class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="absolute top-2 left-2 text-xs font-medium bg-green-50 text-green-700 px-2 py-0.5 rounded">Found</span>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">iPhone 15 Pro</h3>
                    <p class="text-sm text-gray-500 mb-3 line-clamp-2">Electronics</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Campus
                            </span>
                        <span>2h ago</span>
                    </div>
                </div>
            </div>

            <!-- Item Card 2 - Lost Item -->
            <div onclick="openItemModal('lost', 'Chemistry Textbook', 'Books', 'Engineering Block', 'Yesterday')" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">
                <div class="aspect-video bg-gray-100 flex items-center justify-center relative">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span class="absolute top-2 left-2 text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded">Lost</span>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Chemistry Textbook</h3>
                    <p class="text-sm text-gray-500 mb-3">Books</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Eng. Block
                            </span>
                        <span>Yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Item Card 3 - Found Item -->
            <div onclick="openItemModal('found', 'Set of Keys', 'Keys', 'Cafeteria', 'Yesterday')" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">
                <div class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                    </svg>
                    <span class="absolute top-2 left-2 text-xs font-medium bg-green-50 text-green-700 px-2 py-0.5 rounded">Found</span>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Set of Keys</h3>
                    <p class="text-sm text-gray-500 mb-3">Keys</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Cafeteria
                            </span>
                        <span>Yesterday</span>
                    </div>
                </div>
            </div>

            <!-- Item Card 4 - Found Item -->
            <div onclick="openItemModal('found', 'Scientific Calculator', 'Electronics', 'Engineering Block', '2 days ago')" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">
                <div class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                    <span class="absolute top-2 left-2 text-xs font-medium bg-green-50 text-green-700 px-2 py-0.5 rounded">Found</span>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Scientific Calculator</h3>
                    <p class="text-sm text-gray-500 mb-3">Electronics</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Eng. Block
                            </span>
                        <span>2 days ago</span>
                    </div>
                </div>
            </div>

            <!-- Item Card 5 - Lost Item -->
            <div onclick="openItemModal('lost', 'Student ID Card', 'ID Cards', 'Admin Building', '3 days ago')" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">
                <div class="aspect-video bg-gray-100 flex items-center justify-center relative">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.5 3.5 0 00-5 0m5.346-1.837A3.5 3.5 0 0012 12a3.5 3.5 0 00-2.654-1.837"/>
                    </svg>
                    <span class="absolute top-2 left-2 text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded">Lost</span>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Student ID Card</h3>
                    <p class="text-sm text-gray-500 mb-3">ID Cards</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Admin Bldg
                            </span>
                        <span>3 days ago</span>
                    </div>
                </div>
            </div>

            <!-- Item Card 6 - Lost Item -->
            <div onclick="openItemModal('lost', 'Black Backpack', 'Bags', 'Sports Complex', '4 days ago')" class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">
                <div class="aspect-video bg-gray-100 flex items-center justify-center relative">
                    <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span class="absolute top-2 left-2 text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded">Lost</span>
                </div>
                <div class="p-4">
                    <h3 class="font-medium text-gray-900 mb-1">Black Backpack</h3>
                    <p class="text-sm text-gray-500 mb-3">Bags</p>
                    <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                Sports Comp
                            </span>
                        <span>4 days ago</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6 flex items-center justify-center gap-2">
            <button class="p-2 text-gray-400 hover:text-gray-600 disabled:opacity-50" disabled>
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </button>
            <button class="w-9 h-9 text-sm font-medium bg-primary-600 text-white rounded-lg">1</button>
            <button class="w-9 h-9 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">2</button>
            <button class="w-9 h-9 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">3</button>
            <span class="text-gray-400">...</span>
            <button class="w-9 h-9 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg">8</button>
            <button class="p-2 text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
            </button>
        </div>
    </main>
</div>

<!-- Item Detail Modal -->
<div id="itemModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full overflow-hidden">
        <!-- Image Section -->
        <div id="modalImage" class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
            <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <!-- Blur overlay for found items -->
            <div id="blurOverlay" class="absolute inset-0 backdrop-blur-md bg-gray-100/30 hidden"></div>
            <span id="modalBadge" class="absolute top-3 left-3 text-xs font-medium px-2 py-1 rounded"></span>
        </div>

        <!-- Content -->
        <div class="p-5">
            <div class="flex items-start justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900">Item Details</h2>
                <button onclick="closeItemModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <!-- Limited Info Only -->
            <div class="space-y-3">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Category</p>
                        <p id="modalCategory" class="text-sm font-medium text-gray-900"></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Location</p>
                        <p id="modalLocation" class="text-sm font-medium text-gray-900"></p>
                    </div>
                </div>

                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Date</p>
                        <p id="modalDate" class="text-sm font-medium text-gray-900"></p>
                    </div>
                </div>
            </div>

            <!-- Security Notice for Found Items -->
            <div id="securityNotice" class="hidden mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                <p class="text-xs text-amber-700">For security, detailed descriptions and clear images are only shared after your claim is approved.</p>
            </div>

            <!-- Action Button -->
            <div class="mt-5 flex gap-3">
                <button onclick="closeItemModal()" class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    Close
                </button>
                <button id="claimBtn" class="hidden flex-1 py-2.5 px-4 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm">
                    Claim This Item
                </button>
            </div>
        </div>
    </div>
</div>

<script src="{{asset('js/index.js')}}"></script>
<script>
    // Filters toggle
    function toggleFilters() {
        const panel = document.getElementById('filtersPanel');
        panel.classList.toggle('hidden');
    }


    // Item Modal
    const itemModal = document.getElementById('itemModal');
    const modalCategory = document.getElementById('modalCategory');
    const modalLocation = document.getElementById('modalLocation');
    const modalDate = document.getElementById('modalDate');
    const modalBadge = document.getElementById('modalBadge');
    const blurOverlay = document.getElementById('blurOverlay');
    const securityNotice = document.getElementById('securityNotice');
    const claimBtn = document.getElementById('claimBtn');

    function openItemModal(type, title, category, location, date) {
        modalCategory.textContent = category;
        modalLocation.textContent = location;
        modalDate.textContent = date;

        if (type === 'found') {
            modalBadge.textContent = 'Found';
            modalBadge.className = 'absolute top-3 left-3 text-xs font-medium bg-green-50 text-green-700 px-2 py-1 rounded';
            blurOverlay.classList.remove('hidden');
            securityNotice.classList.remove('hidden');
            claimBtn.classList.remove('hidden');
            claimBtn.classList.add('flex');
        } else {
            modalBadge.textContent = 'Lost';
            modalBadge.className = 'absolute top-3 left-3 text-xs font-medium bg-red-50 text-red-700 px-2 py-1 rounded';
            blurOverlay.classList.add('hidden');
            securityNotice.classList.add('hidden');
            claimBtn.classList.add('hidden');
            claimBtn.classList.remove('flex');
        }

        itemModal.classList.remove('hidden');
        itemModal.classList.add('flex');
    }

    function closeItemModal() {
        itemModal.classList.add('hidden');
        itemModal.classList.remove('flex');
    }

    // Close modal on backdrop click
    itemModal.addEventListener('click', (e) => {
        if (e.target === itemModal) {
            closeItemModal();
        }
    });
</script>
</body>
</html>
