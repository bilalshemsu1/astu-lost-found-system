<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Matches - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-student-navigation/>

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-student-header title="Match Items" trustScore="3" />

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

        <div class="space-y-4">
            @forelse($matches as $match)
                @php
                    $lostItem = $match->lostItem;
                    $foundItem = $match->foundItem;
                    $currentUserId = (int) auth()->id();

                    $isLostOwner = (int) $lostItem->user_id === $currentUserId;
                    $isFoundOwner = (int) $foundItem->user_id === $currentUserId;

                    $yourItem = $isLostOwner ? $lostItem : $foundItem;
                    $otherItem = $isLostOwner ? $foundItem : $lostItem;
                    $otherItemType = $otherItem->type;
                    $shouldBlurOtherImage = $otherItemType === 'found' && !$isFoundOwner;

                    $canClaim = $isLostOwner && !$isFoundOwner;
                    $claimItemLabel = $foundItem->title ?: 'Matched found item';

                    $alreadyClaimed = $existingClaims->contains(function ($claim) use ($match, $foundItem) {
                        return (int) $claim->item_id === (int) $foundItem->id
                            && (
                                !$claim->similarity_log_id
                                || (int) $claim->similarity_log_id === (int) $match->id
                            );
                    });
                @endphp

                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <span class="text-xs font-medium {{ $match->similarity_percentage >= 80 ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }} px-2 py-0.5 rounded w-fit">
                            {{ $match->similarity_percentage >= 80 ? 'Strong Candidate' : 'Potential Match' }}
                        </span>
                        <span class="text-xs text-gray-400">Candidate found {{ $match->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="p-4">
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-2">Your {{ ucfirst($yourItem->type) }} Item</p>
                                <div class="flex gap-3">
                                    @if($yourItem->image_path)
                                        <img src="{{ asset('storage/' . $yourItem->image_path) }}" alt="{{ $yourItem->title }}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-medium text-gray-900 text-sm">{{ $yourItem->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $yourItem->description ? \Illuminate\Support\Str::limit($yourItem->description, 40) : 'No description' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $yourItem->location }} - {{ \Carbon\Carbon::parse($yourItem->item_date)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="{{ $otherItemType === 'found' ? 'bg-green-50' : 'bg-red-50' }} rounded-lg p-4">
                                <p class="text-xs font-medium {{ $otherItemType === 'found' ? 'text-green-700' : 'text-red-700' }} uppercase mb-2">
                                    Matched {{ ucfirst($otherItemType) }} Item
                                </p>

                                <div class="flex gap-3">
                                    @if($otherItem->image_path)
                                        <img
                                            src="{{ asset('storage/' . $otherItem->image_path) }}"
                                            alt="{{ $otherItem->title }}"
                                            class="w-14 h-14 object-cover rounded-lg flex-shrink-0 {{ $shouldBlurOtherImage ? 'blur-sm scale-105' : '' }}"
                                        >
                                    @else
                                        <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-medium text-gray-900 text-sm">{{ $otherItem->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $otherItem->description ? \Illuminate\Support\Str::limit($otherItem->description, 40) : 'No description' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $otherItem->location }} - {{ \Carbon\Carbon::parse($otherItem->item_date)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row gap-2">
                            @if($canClaim && !$alreadyClaimed)
                                <button
                                    type="button"
                                    onclick='openClaimModal(@json($match->id), @json($claimItemLabel))'
                                    class="flex-1 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm"
                                >
                                    Yes, This is Mine!
                                </button>
                            @elseif($alreadyClaimed)
                                <button type="button" disabled class="flex-1 py-2.5 bg-gray-200 text-gray-600 font-medium rounded-lg cursor-not-allowed text-sm">
                                    Claim Submitted
                                </button>
                            @else
                                <button type="button" disabled class="flex-1 py-2.5 bg-gray-200 text-gray-600 font-medium rounded-lg cursor-not-allowed text-sm">
                                    Owner Can Claim
                                </button>
                            @endif

                            <form method="POST" action="{{ route('student.matches.dismiss', $match) }}" class="flex-1">
                                @csrf
                                <button
                                    type="submit"
                                    onclick="return confirm('Remove this match from your list?')"
                                    class="w-full py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm text-center"
                                >
                                    Not My Item
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                    <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                        </svg>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Matches Yet</h3>
                    <p class="text-sm text-gray-500 mb-4">When we find items matching your reports, they will appear here.</p>
                    <a href="{{ route('student.items') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
                        Browse Items
                    </a>
                </div>
            @endforelse
        </div>

        @if($matches->hasPages())
            <div class="mt-6">
                {{ $matches->links() }}
            </div>
        @endif
    </main>
</div>

<div id="claimModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-4 border-b border-gray-200 flex items-center justify-between sticky top-0 bg-white">
            <h2 class="font-semibold text-gray-900">Claim This Item</h2>
            <button onclick="closeClaimModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <form id="claimForm" method="POST" action="{{ route('student.claims.store') }}" class="p-4 space-y-4">
            @csrf
            <input type="hidden" name="similarity_log_id" id="similarityLogIdInput">

            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                <p class="text-xs text-amber-700">Provide proof of ownership. Found-item images stay blurred for non-owners until review.</p>
            </div>

            <p class="text-sm text-gray-600">
                Claiming match for: <span id="claimItemTitle" class="font-medium text-gray-900"></span>
            </p>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Proof of Ownership <span class="text-red-500">*</span></label>
                <textarea
                    name="proof"
                    required
                    rows="3"
                    class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none text-sm"
                    placeholder="Describe details only the real owner can provide."
                ></textarea>
            </div>

            <div class="pt-2 flex gap-3">
                <button type="button" onclick="closeClaimModal()" class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    Cancel
                </button>
                <button type="submit" class="flex-1 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm">
                    Submit Claim
                </button>
            </div>
        </form>
    </div>
</div>

<script src="{{ asset('js/index.js') }}"></script>
<script>
    const claimModal = document.getElementById('claimModal');
    const similarityLogIdInput = document.getElementById('similarityLogIdInput');
    const claimItemTitle = document.getElementById('claimItemTitle');

    function openClaimModal(similarityLogId, itemTitle) {
        similarityLogIdInput.value = similarityLogId ?? '';
        claimItemTitle.textContent = itemTitle ?? 'Matched item';
        claimModal.classList.remove('hidden');
        claimModal.classList.add('flex');
    }

    function closeClaimModal() {
        claimModal.classList.add('hidden');
        claimModal.classList.remove('flex');
    }
</script>
</body>
</html>
