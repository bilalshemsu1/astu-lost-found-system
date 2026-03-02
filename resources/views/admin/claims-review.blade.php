<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Review Claim - ASTU Lost & Found Admin</title>
    <x-common-head-scripts />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Claim Review" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        @php
            $source = $isDirectRequest ? 'Direct Ownership Request' : 'Similarity Match Claim';
            $similarityLabel = $isDirectRequest
                ? 'Manual Review Required'
                : number_format((float) ($claim->similarity_score ?? 0), 1) . '%';
            $handoverConfirmed = (bool) $claim->claimResponse?->handover_confirmed_at;
            $handoverPending = $claim->status === 'approved' && !$handoverConfirmed;
            $allowsDirectContact = (bool) $claim->claimResponse?->finder_shares_contact;
            $handoverModeLabel = $allowsDirectContact ? 'Direct Contact Allowed' : 'Admin Office Handover';

            $categoryLabels = config('items.categories', []);
        @endphp

        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="mb-4">
            <a href="{{ route('admin.claims') }}" class="inline-flex px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-700 hover:bg-gray-50">Back to Claims</a>
        </div>

        <div class="grid lg:grid-cols-3 gap-4">
            <div class="lg:col-span-2 space-y-4">
                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <div class="flex flex-wrap items-center gap-2 mb-3">
                        <span class="text-sm font-semibold text-gray-900">Claim #{{ $claim->id }}</span>
                        @if($handoverConfirmed)
                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-green-50 text-green-700">Returned</span>
                        @elseif($handoverPending)
                            <span class="text-xs font-medium px-2 py-0.5 rounded bg-blue-50 text-blue-700">Approved - Handover Pending</span>
                        @else
                            <span class="text-xs font-medium px-2 py-0.5 rounded {{ $claim->status === 'pending' ? 'bg-amber-50 text-amber-700' : ($claim->status === 'approved' ? 'bg-green-50 text-green-700' : 'bg-red-50 text-red-700') }}">{{ ucfirst($claim->status) }}</span>
                        @endif
                        <span class="text-xs font-medium px-2 py-0.5 rounded {{ $isDirectRequest ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">{{ $source }}</span>
                    </div>
                    <div class="text-sm text-gray-700 space-y-1">
                        <p><span class="font-medium text-gray-900">Item:</span> {{ $claim->item?->title ?? 'Unknown item' }}</p>
                        <p><span class="font-medium text-gray-900">Similarity:</span> {{ $similarityLabel }}</p>
                        @if($claim->status === 'approved')
                            <p><span class="font-medium text-gray-900">Handover Mode:</span> {{ $handoverModeLabel }}</p>
                        @endif
                        @if($handoverConfirmed)
                            <p><span class="font-medium text-gray-900">Handover Confirmed:</span> {{ $claim->claimResponse->handover_confirmed_at->format('M d, Y H:i') }}</p>
                        @endif
                        <p><span class="font-medium text-gray-900">Submitted:</span> {{ $claim->created_at->format('M d, Y H:i') }}</p>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900 mb-2">Claim Proof</h2>
                    <p class="text-sm text-gray-700 whitespace-pre-line">{{ $claim->proof }}</p>
                </div>

                

                <div class="grid md:grid-cols-2 gap-4">
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Claimant Contact</h3>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><span class="font-medium text-gray-900">Name:</span> {{ $claim->user->name ?? 'Unknown' }}</p>
                            <p><span class="font-medium text-gray-900">Email:</span> {{ $claim->user->email ?? '-' }}</p>
                            <p><span class="font-medium text-gray-900">Phone:</span> {{ $claim->user->phone ?? '-' }}</p>
                            <p><span class="font-medium text-gray-900">Telegram:</span> {{ $claim->user->telegram_username ? '@' . ltrim($claim->user->telegram_username, '@') : '-' }}</p>
                        </div>
                    </div>
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Current Holder Contact</h3>
                        <div class="text-sm text-gray-700 space-y-1">
                            <p><span class="font-medium text-gray-900">Name:</span> {{ $claim->item?->user?->name ?? 'Unknown' }}</p>
                            <p><span class="font-medium text-gray-900">Email:</span> {{ $claim->item?->user?->email ?? '-' }}</p>
                            <p><span class="font-medium text-gray-900">Phone:</span> {{ $claim->item?->user?->phone ?? '-' }}</p>
                            <p><span class="font-medium text-gray-900">Telegram:</span> {{ $claim->item?->user?->telegram_username ? '@' . ltrim($claim->item->user->telegram_username, '@') : '-' }}</p>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h3 class="font-semibold text-gray-900 mb-2">Item Comparison</h3>
                    <div class="grid md:grid-cols-2 gap-4 text-sm">
                        @php
                            if ($claim->similarityLog) {
                                $left = $claim->similarityLog->lostItem;
                                $right = $claim->similarityLog->foundItem;
                                $leftLabel = 'Lost Item';
                                $rightLabel = 'Found Item';
                                $leftBg = 'red';
                                $rightBg = 'green';
                            } else {
                                $left = $claim->item;
                                $right = $claim->item;
                                $leftLabel = $rightLabel = 'Claimed Item';
                                $leftBg = $rightBg = 'gray';
                            }
                        @endphp

                        @foreach (['left' => $left, 'right' => $right] as $side => $itm)
                            <div class="space-y-2 rounded-lg border border-{{$side === 'left' ? $leftBg : $rightBg}}-100 bg-{{$side === 'left' ? $leftBg : $rightBg}}-50 p-3">
                                <p class="text-xs font-medium text-{{$side === 'left' ? $leftBg : $rightBg}}-700 uppercase">
                                    {{ ${$side.'Label'} }}
                                </p>
                                <p class="font-medium text-gray-900">{{ $itm->title ?? 'Unknown' }}</p>
                                <p class="text-xs text-gray-600">Category: {{ $categoryLabels[$itm->category] ?? ucfirst($itm->category) }}</p>
                                <p class="text-xs text-gray-600">Location: {{ $itm->location ?? '-' }}</p>
                                <p class="text-xs text-gray-600">Date: {{ optional($itm->created_at)->format('M d, Y') }}</p>
                                @if($itm->image_path)
                                    <img src="{{ asset('storage/' . $itm->image_path) }}" alt="{{ $itm->title }}" class="mt-1 w-full h-32 object-cover rounded">
                                @endif
                                @if($itm->description)
                                    <p class="mt-2 text-sm text-gray-700">{{ $itm->description }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="space-y-4">
                @if($claim->status === 'pending')
                    <form method="POST" action="{{ route('admin.claims.approve', $claim) }}" class="bg-white rounded-xl border border-green-200 p-4">
                        @csrf
                        @method('PATCH')
                        <h3 class="font-semibold text-green-700 mb-2">Approve Claim</h3>
                        <p class="text-xs text-gray-500 mb-2">Review notes are required before approval.</p>
                        <textarea name="admin_notes" rows="5" required minlength="10" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Write review notes and why this claim is valid...">{{ old('admin_notes') }}</textarea>
                        <button type="submit" class="mt-3 w-full px-3 py-2.5 bg-green-600 text-white text-sm font-medium rounded-lg hover:bg-green-700">Approve Claim</button>
                    </form>

                    <form method="POST" action="{{ route('admin.claims.reject', $claim) }}" class="bg-white rounded-xl border border-red-200 p-4">
                        @csrf
                        @method('PATCH')
                        <h3 class="font-semibold text-red-700 mb-2">Reject Claim</h3>
                        <p class="text-xs text-gray-500 mb-2">Provide a clear reason for the claimant.</p>
                        <textarea name="admin_notes" rows="5" required minlength="5" class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm" placeholder="Explain why this claim is rejected...">{{ old('admin_notes') }}</textarea>
                        <button type="submit" class="mt-3 w-full px-3 py-2.5 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700">Reject Claim</button>
                    </form>
                @elseif($handoverPending)
                    <div class="bg-white rounded-xl border border-blue-200 p-4">
                        <h3 class="font-semibold text-blue-700 mb-2">Handover In Progress</h3>
                        <p class="text-xs text-gray-600 mb-3">
                            Claim is approved, but item is not marked returned yet. Confirm only after the owner physically receives the item.
                        </p>
                        <form method="POST" action="{{ route('admin.claims.handover.confirm', $claim) }}">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="w-full px-3 py-2.5 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700">
                                Confirm Handover Completed
                            </button>
                        </form>
                    </div>
                @else
                    <div class="bg-white rounded-xl border border-gray-200 p-4">
                        <h3 class="font-semibold text-gray-900 mb-2">Review Complete</h3>
                        <p class="text-sm text-gray-600">No further action is required for this claim.</p>
                    </div>
                @endif
            </div>
        </div>
    </main>
</div>

<x-common-page-scripts />
</body>
</html>

