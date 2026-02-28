<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Items - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

<x-student-navigation/>

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-student-header title="My Items" />

    <main class="flex-1 p-4 sm:p-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Total</p>
                <p class="text-xl font-semibold text-gray-900">{{ $counts['total'] }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Lost</p>
                <p class="text-xl font-semibold text-red-600">{{ $counts['lost'] }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Found</p>
                <p class="text-xl font-semibold text-green-600">{{ $counts['found'] }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('student.my-items') }}" class="bg-white border border-gray-200 rounded-xl p-4 mb-5">
            <div class="grid sm:grid-cols-2 lg:grid-cols-5 gap-3">
                <input
                    type="text"
                    name="search"
                    value="{{ request('search') }}"
                    placeholder="Search title, desc, location..."
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"
                >
                <select name="type" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Types</option>
                    <option value="lost" {{ request('type') === 'lost' ? 'selected' : '' }}>Lost</option>
                    <option value="found" {{ request('type') === 'found' ? 'selected' : '' }}>Found</option>
                </select>
                <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending_verification" {{ request('status') === 'pending_verification' ? 'selected' : '' }}>Pending Verification</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                    <option value="returned" {{ request('status') === 'returned' ? 'selected' : '' }}>Returned</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="sort" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="recent" {{ request('sort') !== 'oldest' ? 'selected' : '' }}>Most Recent</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                </select>
                <button type="submit" class="w-full px-3 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                    Apply Filters
                </button>
            </div>
        </form>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($items as $item)
                @php
                    $statusClass = match($item->status) {
                        'active' => 'bg-green-50 text-green-700',
                        'pending_verification' => 'bg-amber-50 text-amber-700',
                        'returned' => 'bg-blue-50 text-blue-700',
                        'rejected' => 'bg-red-50 text-red-700',
                        default => 'bg-gray-100 text-gray-700',
                    };
                @endphp
                <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
                    <div class="aspect-video bg-gray-100 relative flex items-center justify-center">
                        <span class="absolute top-2 left-2 text-xs font-medium {{ $item->type === 'lost' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }} px-2 py-0.5 rounded">
                            {{ ucfirst($item->type) }}
                        </span>
                        @if($item->image_path)
                            <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                        @else
                            <svg class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        @endif
                    </div>
                    <div class="p-4">
                        <div class="flex items-start justify-between gap-2 mb-2">
                            <h3 class="font-medium text-gray-900">{{ $item->title }}</h3>
                            <span class="text-xs font-medium px-2 py-0.5 rounded {{ $statusClass }}">
                                {{ str_replace('_', ' ', ucfirst($item->status)) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mb-1">{{ ucfirst($item->category) }}</p>
                        <p class="text-sm text-gray-600 mb-2">{{ $item->description ? \Illuminate\Support\Str::limit($item->description, 70) : 'No description' }}</p>
                        <div class="text-xs text-gray-400 space-y-1">
                            <p>{{ $item->location }}</p>
                            <p>Item date: {{ optional($item->item_date)->format('M d, Y') }}</p>
                            <p>Posted: {{ $item->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full bg-white border border-gray-200 rounded-xl p-8 text-center">
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No items found</h3>
                    <p class="text-sm text-gray-500">You have not posted lost or found items yet.</p>
                </div>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $items->links() }}
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
