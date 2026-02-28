<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Reports & Statistics" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
            <x-admin-stat-card label="Total Items" :value="$totalItems ?? 0" />
            <x-admin-stat-card label="Lost" :value="$lostItems ?? 0" valueClass="text-red-700" />
            <x-admin-stat-card label="Found" :value="$foundItems ?? 0" valueClass="text-green-700" />
            <x-admin-stat-card label="Returned" :value="$returnedItems ?? 0" valueClass="text-primary-700" />
            <x-admin-stat-card label="Claims" :value="$totalClaims ?? 0" />
            <x-admin-stat-card label="Active Matches" :value="$activeMatches ?? 0" valueClass="text-amber-700" />
        </div>

        <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5 mb-6">
            <p class="text-sm text-gray-500">Return Rate</p>
            <p class="text-2xl font-bold text-green-700 mt-1">{{ number_format((float) ($returnRate ?? 0), 1) }}%</p>
        </div>

        <div class="bg-white rounded-xl border border-gray-200">
            <div class="p-4 border-b border-gray-200">
                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Top Categories</h2>
            </div>
            <div class="p-4 space-y-3">
                @forelse($categoryBreakdown ?? [] as $row)
                    @php
                        $max = max(1, (int) (($categoryBreakdown->max('total') ?? 1));
                        $width = (int) round(($row->total / $max) * 100);
                    @endphp
                    <div>
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-sm text-gray-700">{{ ucfirst($row->category ?: 'Uncategorized') }}</span>
                            <span class="text-sm font-medium text-gray-900">{{ $row->total }}</span>
                        </div>
                        <div class="w-full h-2 bg-gray-200 rounded-full overflow-hidden">
                            <div class="h-full bg-primary-600 rounded-full" style="width: {{ $width }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-sm text-gray-500">No category data available.</p>
                @endforelse
            </div>
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
