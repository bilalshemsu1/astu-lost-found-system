<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Claims - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Claims" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-6 gap-3 sm:gap-4 mb-6">
            <x-admin-stat-card label="Total" :value="$claimsTotal ?? 0" />
            <x-admin-stat-card label="Pending" :value="$claimsPending ?? 0" valueClass="text-amber-700" />
            <x-admin-stat-card label="Approved" :value="$claimsApproved ?? 0" valueClass="text-green-700" />
            <x-admin-stat-card label="Rejected" :value="$claimsRejected ?? 0" valueClass="text-red-700" />
            <x-admin-stat-card label="Similarity" :value="$similarityClaims ?? 0" valueClass="text-primary-700" />
            <x-admin-stat-card label="Direct" :value="$directClaims ?? 0" />
        </div>

        <form method="GET" action="{{ route('admin.claims') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search item/user/proof..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm lg:col-span-2">
                <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="source" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" {{ ($hasSimilarityLogId ?? false) ? '' : 'disabled' }}>
                    <option value="">All Sources</option>
                    <option value="similarity_match" {{ request('source') === 'similarity_match' ? 'selected' : '' }}>Similarity Match</option>
                    <option value="direct_ownership_request" {{ request('source') === 'direct_ownership_request' ? 'selected' : '' }}>Direct Request</option>
                </select>
            </div>
            <div class="mt-3">
                <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Apply Filters</button>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">Claim</th>
                    <th class="px-4 py-3 text-left font-medium">Item</th>
                    <th class="px-4 py-3 text-left font-medium">User</th>
                    <th class="px-4 py-3 text-left font-medium">Status</th>
                    <th class="px-4 py-3 text-left font-medium">Score</th>
                    <th class="px-4 py-3 text-left font-medium">Date</th>
                    <th class="px-4 py-3 text-left font-medium">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($claims as $claim)
                    @php
                        $isDirectRequest = !$claim->similarity_log_id;
                        $scoreLabel = $isDirectRequest
                            ? 'Manual Review'
                            : number_format((float) ($claim->similarity_score ?? 0), 1) . '%';
                    @endphp
                    <tr>
                        <td class="px-4 py-3 text-gray-900">#{{ $claim->id }}</td>
                        <td class="px-4 py-3 text-gray-900">{{ $claim->item->title ?? 'Unknown item' }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $claim->user->name ?? 'Unknown user' }}</td>
                        <td class="px-4 py-3">
                                <span class="text-xs font-medium px-2 py-0.5 rounded {{ $claim->status === 'approved' ? 'bg-green-50 text-green-700' : ($claim->status === 'rejected' ? 'bg-red-50 text-red-700' : 'bg-amber-50 text-amber-700') }}">{{ ucfirst($claim->status) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-600">{{ $scoreLabel }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $claim->created_at->format('M d, Y') }}</td>
                        <td class="px-4 py-3">
                            @if($claim->status === 'pending')
                                <a href="{{ route('admin.claims.review', $claim) }}" class="px-2.5 py-1.5 text-xs font-medium bg-primary-600 text-white rounded hover:bg-primary-700">Review</a>
                            @else
                                <span class="text-xs text-gray-400">Processed</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 pb-4 text-xs text-gray-500" colspan="7">
                            Proof: {{ \Illuminate\Support\Str::limit($claim->proof, 180) }}
                            <span class="ml-3">Claimant: {{ $claim->user->phone ?? '-' }}</span>
                            <span class="ml-3">Holder: {{ $claim->item?->user?->phone ?? '-' }}</span>
                            @if($claim->admin_notes)
                                <span class="ml-3">Admin Notes: {{ \Illuminate\Support\Str::limit($claim->admin_notes, 120) }}</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-4 py-8 text-center text-gray-500">No claims found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $claims->withQueryString()->links() }}
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
