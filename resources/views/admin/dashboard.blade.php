<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Admin Dashboard" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
            <x-admin-stat-card label="Lost Items" :value="$lostCount ?? 0" />
            <x-admin-stat-card label="Found Items" :value="$foundCount ?? 0" />
            <x-admin-stat-card label="Returned" :value="$returnedCount ?? 0" valueClass="text-green-700" />
            <x-admin-stat-card label="Pending Review" :value="$pendingCount ?? 0" valueClass="text-amber-700" />
        </div>

        <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Recent Pending Items</h2>
                    <a href="{{ route('admin.items.pending') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">Open pending</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentPendingItems ?? [] as $item)
                        <div class="p-4 flex items-center justify-between gap-3">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $item->title }}</p>
                                <p class="text-xs text-gray-500">{{ ucfirst($item->type) }} - {{ $item->location ?? '-' }} - {{ $item->created_at->diffForHumans() }}</p>
                            </div>
                            <span class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-0.5 rounded">Pending</span>
                        </div>
                    @empty
                        <div class="p-6 text-sm text-gray-500 text-center">No pending items.</div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-xl border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Recent Matches</h2>
                    <a href="{{ route('admin.matches') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">Open matches</a>
                </div>
                <div class="divide-y divide-gray-100">
                    @forelse($recentMatches ?? [] as $match)
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-sm font-medium text-gray-900">{{ $match->similarity_percentage }}%</p>
                                <p class="text-xs text-gray-400">{{ $match->created_at->diffForHumans() }}</p>
                            </div>
                            <p class="text-xs text-gray-600">{{ $match->lostItem->title ?? 'Lost item' }} -> {{ $match->foundItem->title ?? 'Found item' }}</p>
                        </div>
                    @empty
                        <div class="p-6 text-sm text-gray-500 text-center">No recent matches.</div>
                    @endforelse
                </div>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 bg-white rounded-xl border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Pending Claims</h2>
                <a href="{{ route('admin.claims') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">Open claims</a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="bg-gray-50 text-gray-500">
                    <tr>
                        <th class="px-4 py-3 text-left font-medium">Item</th>
                        <th class="px-4 py-3 text-left font-medium">Claimant</th>
                        <th class="px-4 py-3 text-left font-medium">Status</th>
                        <th class="px-4 py-3 text-left font-medium">Date</th>
                    </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                    @forelse($pendingClaims ?? [] as $claim)
                        <tr>
                            <td class="px-4 py-3 text-gray-900">{{ $claim->item->title ?? 'Unknown item' }}</td>
                            <td class="px-4 py-3 text-gray-600">{{ $claim->user->name ?? 'Unknown user' }}</td>
                            <td class="px-4 py-3">
                                <span class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-0.5 rounded">{{ ucfirst($claim->status) }}</span>
                            </td>
                            <td class="px-4 py-3 text-gray-500">{{ $claim->created_at->format('M d, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-500">No pending claims.</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
