<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Claims - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-student-navigation/>

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-student-header title="My Claims" />

    <main class="flex-1 p-4 sm:p-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3 mb-5">
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">All</p>
                <p class="text-lg font-semibold text-gray-900">{{ $counts['total'] ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Pending</p>
                <p class="text-lg font-semibold text-amber-700">{{ $counts['pending'] ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Approved</p>
                <p class="text-lg font-semibold text-green-700">{{ $counts['approved'] ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Rejected</p>
                <p class="text-lg font-semibold text-red-700">{{ $counts['rejected'] ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Match Claims</p>
                <p class="text-lg font-semibold text-blue-700">{{ $counts['similarity_match'] ?? 0 }}</p>
            </div>
            <div class="rounded-xl bg-white border border-gray-200 px-4 py-3">
                <p class="text-xs text-gray-500">Direct Requests</p>
                <p class="text-lg font-semibold text-purple-700">{{ $counts['direct_ownership_request'] ?? 0 }}</p>
            </div>
        </div>

        <form method="GET" action="{{ route('student.claims') }}" class="bg-white border border-gray-200 rounded-xl p-4 mb-5">
            <div class="grid sm:grid-cols-3 gap-3">
                <select name="status" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Statuses</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approved</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
                <select name="source" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Claim Types</option>
                    <option value="similarity_match" {{ request('source') === 'similarity_match' ? 'selected' : '' }}>Similarity Match</option>
                    <option value="direct_ownership_request" {{ request('source') === 'direct_ownership_request' ? 'selected' : '' }}>Direct Ownership Request</option>
                </select>
                <button type="submit" class="w-full px-3 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                    Apply Filters
                </button>
            </div>
        </form>

        @if($claims->count() === 0)
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Claims Yet</h3>
                <p class="text-sm text-gray-500 mb-4">Your similarity-match claims and direct ownership requests appear here.</p>
                <a href="{{ route('student.matches') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
                    Go To Matches
                </a>
            </div>
        @else
            <div class="space-y-4">
                @foreach($claims as $claim)
                    @php
                        $statusClass = match($claim->status) {
                            'approved' => 'bg-green-50 text-green-700',
                            'rejected' => 'bg-red-50 text-red-700',
                            default => 'bg-amber-50 text-amber-700',
                        };
                        $source = $claim->similarity_details['source'] ?? ($claim->similarity_log_id ? 'similarity_match' : 'direct_ownership_request');
                        $sourceLabel = $source === 'direct_ownership_request' ? 'Direct Ownership Request' : 'Similarity Match Claim';
                        $sourceClass = $source === 'direct_ownership_request'
                            ? 'bg-purple-50 text-purple-700'
                            : 'bg-blue-50 text-blue-700';
                        $displayTitle = $claim->item?->title ?? 'Item unavailable';
                        $displayLocation = $claim->item?->location ?? '-';
                        $handoverConfirmed = (bool) $claim->claimResponse?->handover_confirmed_at;
                        $handoverPending = $claim->status === 'approved' && !$handoverConfirmed;
                        $canSeeFinderPhone = (bool) ($claim->claimResponse?->finder_shares_contact && $claim->item?->share_phone);
                        $canSeeFinderTelegram = (bool) ($claim->claimResponse?->finder_shares_contact && $claim->item?->share_telegram);
                    @endphp
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <div class="flex items-center justify-between gap-2">
                            <div class="flex items-center gap-2">
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $statusClass }}">
                                    {{ ucfirst($claim->status) }}
                                </span>
                                <span class="text-xs font-medium px-2 py-0.5 rounded-full {{ $sourceClass }}">
                                    {{ $sourceLabel }}
                                </span>
                                <p class="text-sm font-medium text-gray-900">
                                    {{ $displayTitle }}
                                </p>
                            </div>
                            <p class="text-xs text-gray-400">{{ $claim->created_at->diffForHumans() }}</p>
                        </div>

                        <div class="mt-3 text-sm text-gray-600 space-y-1">
                            <p><span class="font-medium text-gray-700">Claim ID:</span> #{{ $claim->id }}</p>
                            <p><span class="font-medium text-gray-700">Item Type:</span> {{ ucfirst($claim->item->type ?? 'unknown') }}</p>
                            <p><span class="font-medium text-gray-700">Location:</span> {{ $displayLocation }}</p>
                            @if($source === 'direct_ownership_request')
                                <p><span class="font-medium text-gray-700">Review Basis:</span> Proof details + admin manual verification</p>
                            @else
                                <p><span class="font-medium text-gray-700">Similarity:</span> {{ $claim->similarity_score }}%</p>
                            @endif
                            @if($source !== 'direct_ownership_request' && is_array($claim->similarity_details))
                                <p>
                                    <span class="font-medium text-gray-700">Breakdown:</span>
                                    T {{ $claim->similarity_details['title'] ?? '-' }},
                                    C {{ $claim->similarity_details['category'] ?? '-' }},
                                    D {{ $claim->similarity_details['description'] ?? '-' }},
                                    L {{ $claim->similarity_details['location'] ?? '-' }},
                                    Date {{ $claim->similarity_details['date'] ?? '-' }}
                                </p>
                            @endif
                            <p><span class="font-medium text-gray-700">Proof:</span> {{ $claim->proof }}</p>
                            @if($claim->admin_notes)
                                <p><span class="font-medium text-gray-700">Admin Notes:</span> {{ $claim->admin_notes }}</p>
                            @endif

                            @if($handoverPending)
                                <div class="mt-2 rounded-lg border border-blue-200 bg-blue-50 p-3">
                                    <p><span class="font-medium text-blue-800">Handover Status:</span> Approved, waiting for item transfer confirmation.</p>
                                    @if($claim->claimResponse?->finder_shares_contact)
                                        <p class="mt-1"><span class="font-medium text-blue-800">Transfer Method:</span> Direct contact with finder.</p>
                                        @if($canSeeFinderPhone)
                                            <p class="mt-1"><span class="font-medium text-blue-800">Finder Phone:</span> {{ $claim->item?->user?->phone ?? '-' }}</p>
                                        @endif
                                        @if($canSeeFinderTelegram)
                                            <p class="mt-1"><span class="font-medium text-blue-800">Finder Telegram:</span> {{ $claim->item?->user?->telegram_username ? '@' . ltrim($claim->item->user->telegram_username, '@') : '-' }}</p>
                                        @endif
                                        @if(!$canSeeFinderPhone && !$canSeeFinderTelegram)
                                            <p class="mt-1">Finder approved direct handover but no shareable contact details are available.</p>
                                        @endif
                                    @else
                                        <p class="mt-1"><span class="font-medium text-blue-800">Transfer Method:</span> Collect at admin office.</p>
                                    @endif
                                </div>
                            @elseif($handoverConfirmed)
                                <div class="mt-2 rounded-lg border border-green-200 bg-green-50 p-3">
                                    <p><span class="font-medium text-green-800">Handover Status:</span> Completed on {{ $claim->claimResponse->handover_confirmed_at->format('M d, Y H:i') }}.</p>
                                </div>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-6">
                {{ $claims->withQueryString()->links() }}
            </div>
        @endif
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
