<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Matches - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Matches" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <x-admin-stat-card label="Total Matches" :value="$totalMatches ?? 0" />
            <x-admin-stat-card label="High (90%+)" :value="$highMatches ?? 0" valueClass="text-green-700" />
            <x-admin-stat-card label="Medium (80-89%)" :value="$mediumMatches ?? 0" valueClass="text-amber-700" />
            <x-admin-stat-card label="Notified" :value="$notifiedMatches ?? 0" valueClass="text-primary-700" />
        </div>

        <form method="GET" action="{{ route('admin.matches') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
            <div class="grid sm:grid-cols-3 lg:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by item title..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm lg:col-span-2">
                <input type="number" min="0" max="100" step="1" name="min_similarity" value="{{ request('min_similarity') }}" placeholder="Min similarity" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                <select name="notified" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Notifications</option>
                    <option value="yes" {{ request('notified') === 'yes' ? 'selected' : '' }}>Notified</option>
                    <option value="no" {{ request('notified') === 'no' ? 'selected' : '' }}>Not Notified</option>
                </select>
            </div>
            <div class="mt-3">
                <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Apply Filters</button>
            </div>
        </form>

        <div class="space-y-4">
            @forelse($matches as $match)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 mb-3">
                        <p class="text-sm font-semibold text-gray-900">Match #{{ $match->id }} - {{ number_format($match->similarity_percentage, 2) }}%</p>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium px-2 py-0.5 rounded {{ $match->notified ? 'bg-green-50 text-green-700' : 'bg-amber-50 text-amber-700' }}">{{ $match->notified ? 'Notified' : 'Pending Notify' }}</span>
                            <span class="text-xs text-gray-400">{{ $match->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <div class="grid md:grid-cols-2 gap-3 text-sm">
                        <div class="rounded-lg border border-red-100 bg-red-50 p-3">
                            <p class="text-xs font-medium text-red-700 uppercase mb-1">Lost Item</p>
                            <p class="font-medium text-gray-900">{{ $match->lostItem->title ?? 'Unknown lost item' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Owner: {{ $match->lostItem->user->name ?? 'Unknown' }}</p>
                        </div>
                        <div class="rounded-lg border border-green-100 bg-green-50 p-3">
                            <p class="text-xs font-medium text-green-700 uppercase mb-1">Found Item</p>
                            <p class="font-medium text-gray-900">{{ $match->foundItem->title ?? 'Unknown found item' }}</p>
                            <p class="text-xs text-gray-500 mt-1">Owner: {{ $match->foundItem->user->name ?? 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-sm text-gray-500">No matches found.</div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $matches->withQueryString()->links() }}
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
