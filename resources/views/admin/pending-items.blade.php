<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Items - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Pending Items" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        @php($categoryLabels = config('items.categories', []))

        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <x-admin-stat-card label="Total Pending" :value="$totalPending ?? 0" />
            <x-admin-stat-card label="Lost Pending" :value="$lostPending ?? 0" valueClass="text-red-700" />
            <x-admin-stat-card label="Found Pending" :value="$foundPending ?? 0" valueClass="text-green-700" />
            <x-admin-stat-card label="Today" :value="$todayPending ?? 0" valueClass="text-primary-700" />
        </div>

        <form method="GET" action="{{ route('admin.items.pending') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
            <div class="grid sm:grid-cols-3 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search title, location..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                <select name="type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Types</option>
                    <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Lost</option>
                    <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>Found</option>
                </select>
                <button type="submit" class="w-full px-3 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Apply</button>
            </div>
        </form>

        <div class="space-y-4">
            @forelse($items as $item)
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $item->type === 'lost' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">{{ ucfirst($item->type) }}</span>
                                <span class="text-xs text-gray-400">{{ $item->created_at->diffForHumans() }}</span>
                            </div>
                            <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $item->title }}</h3>
                            <p class="text-xs text-gray-500 mt-1">{{ ($categoryLabels[$item->category] ?? ucfirst($item->category)) }} - {{ $item->location }} - {{ optional($item->item_date)->format('M d, Y') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Posted by {{ $item->user->name ?? 'Unknown' }} (Trust {{ ($item->user->trust_score ?? 0) >= 0 ? '+' : '' }}{{ $item->user->trust_score ?? 0 }})</p>
                            <p class="text-sm text-gray-600 mt-2">{{ $item->description ? \Illuminate\Support\Str::limit($item->description, 180) : 'No description' }}</p>
                        </div>

                        <div class="flex gap-2 sm:flex-col sm:w-36">
                            <form method="POST" action="{{ route('admin.items.approve', $item) }}" class="flex-1 sm:flex-none">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="w-full px-3 py-2 text-xs font-medium bg-green-600 text-white rounded-lg hover:bg-green-700">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.items.reject', $item) }}" class="flex-1 sm:flex-none">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="reason" value="Rejected by admin review">
                                <button type="submit" class="w-full px-3 py-2 text-xs font-medium bg-red-50 text-red-700 rounded-lg hover:bg-red-100 border border-red-200">Reject</button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center text-sm text-gray-500">No pending items found.</div>
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
