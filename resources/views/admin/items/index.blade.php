<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Items - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="All Items" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 mb-6">
            <x-admin-stat-card label="Total" :value="$totalItems ?? 0" />
            <x-admin-stat-card label="Lost" :value="$lostItems ?? 0" valueClass="text-red-700" />
            <x-admin-stat-card label="Found" :value="$foundItems ?? 0" valueClass="text-green-700" />
            <x-admin-stat-card label="Returned" :value="$returnedItems ?? 0" valueClass="text-primary-700" />
            <x-admin-stat-card label="Active" :value="$activeItems ?? 0" valueClass="text-amber-700" />
        </div>

        <form method="GET" action="{{ route('admin.items') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
            <div class="grid sm:grid-cols-2 lg:grid-cols-6 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item/user..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm lg:col-span-2">
                <select name="type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Types</option>
                    <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Lost</option>
                    <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>Found</option>
                </select>
                <input type="text" name="category" value="{{ request('category') }}" placeholder="Category" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                <input type="text" name="status" value="{{ request('status') }}" placeholder="Status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                <select name="sort" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="recent" {{ request('sort') !== 'oldest' ? 'selected' : '' }}>Most Recent</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
            </div>
            <div class="mt-3">
                <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Apply Filters</button>
            </div>
        </form>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($items as $item)
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
                        <span class="absolute top-2 left-2 text-xs font-medium {{ $item->type === 'lost' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }} px-2 py-0.5 rounded">{{ ucfirst($item->type) }}</span>
                        <span class="absolute top-2 right-2 text-xs font-medium bg-gray-100 text-gray-700 px-2 py-0.5 rounded">{{ ucfirst(str_replace('_', ' ', $item->status)) }}</span>
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900">{{ $item->title }}</h3>
                        <p class="text-xs text-gray-500 mt-1">{{ $item->category }} - {{ $item->location }}</p>
                        <p class="text-xs text-gray-500 mt-1">Owner: {{ $item->user->name ?? 'Unknown' }}</p>
                        <p class="text-xs text-gray-400 mt-2">{{ $item->created_at->diffForHumans() }}</p>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white rounded-xl border border-gray-200 p-8 text-center text-sm text-gray-500">No items found.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $items->withQueryString()->links() }}
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
