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

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<x-student-navigation/>

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <x-student-header title="Match Items" trustScore="3" />

    <!-- Page Content -->
<main class="flex-1 p-4 sm:p-6">
    <!-- Info Banner -->
        <div class="bg-primary-50 border border-primary-200 rounded-xl p-4 mb-6">
            <div class="flex gap-3">
                <div class="w-8 h-8 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-medium text-primary-900">Privacy-first matching</p>
                    <p class="text-xs sm:text-sm text-primary-700 mt-1">Potential matches are shown without detailed scoring logic to reduce fraudulent claims.</p>
                </div>
            </div>
        </div>

        <!-- Matches List -->
        <div class="space-y-4">
            @forelse($matches as $match)
                @php
                    $lostItem = $match->lostItem;
                    $foundItem = $match->foundItem;
                    $isMyItemLost = $lostItem->user_id === auth()->id();
                @endphp

                <!-- Match Card -->
                <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                    <div class="p-4 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium {{ $match->similarity_percentage >= 80 ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }} px-2 py-0.5 rounded">
                                {{ $match->similarity_percentage >= 80 ? 'Strong Candidate' : 'Potential Match' }}
                            </span>
                        </div>
                        <span class="text-xs text-gray-400">Candidate found {{ $match->created_at->diffForHumans() }}</span>
                    </div>

                    <div class="p-4">
                        <div class="grid md:grid-cols-2 gap-4 mb-4">
                            <!-- Your Lost Item -->
                            <div class="bg-gray-50 rounded-lg p-4">
                                <p class="text-xs font-medium text-gray-500 uppercase mb-2">{{ $isMyItemLost ? 'Your Lost Item' : 'Your Found Item' }}</p>
                                <div class="flex gap-3">
                                    @if($lostItem->image_path)
                                        <img src="{{ asset('storage/' . $lostItem->image_path) }}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0">
                                    @else
                                        <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                    @endif
                                    <div>
                                        <h3 class="font-medium text-gray-900 text-sm">{{ $lostItem->title }}</h3>
                                        <p class="text-xs text-gray-500 mt-0.5">{{ $lostItem->description ? \Illuminate\Support\Str::limit($lostItem->description, 40) : 'No description' }}</p>
                                        <p class="text-xs text-gray-400 mt-1">{{ $lostItem->location }} • {{ \Carbon\Carbon::parse($lostItem->item_date)->format('M d, Y') }}</p>
                                    </div>
                                </div>
                            </div>

                            <!-- Found Item -->
                            <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-xs font-medium text-green-600 uppercase mb-2">{{ $isMyItemLost ? 'Found Item' : 'Lost Item' }}</p>
                            <div class="flex gap-3">
                                @if($foundItem->image_path)
                                    <img src="{{ asset('storage/' . $foundItem->image_path) }}" class="w-14 h-14 object-cover rounded-lg flex-shrink-0">
                                @else
                                    <div class="w-14 h-14 bg-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                                        <svg class="w-7 h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <h3 class="font-medium text-gray-900 text-sm">{{ $foundItem->title }}</h3>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $foundItem->description ? \Illuminate\Support\Str::limit($foundItem->description, 40) : 'No description' }}</p>
                                    <p class="text-xs text-gray-400 mt-1">{{ $foundItem->location }} • {{ \Carbon\Carbon::parse($foundItem->item_date)->format('M d, Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-col sm:flex-row gap-2">
                        <button onclick="openClaimModal()" class="flex-1 py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm">
                            Yes, This is Mine!
                        </button>
                        <button class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                            Not My Item
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <!-- Empty State -->
            <div class="bg-white rounded-xl border border-gray-200 p-8 text-center">
                <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Matches Yet</h3>
                <p class="text-sm text-gray-500 mb-4">When we find items matching your lost reports, they'll appear here.</p>
                <a href="{{ route('student.items') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
                    Browse Items
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        @endforelse
    </div>
</main>

<!-- Claim Modal -->
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
        <form id="claimForm" class="p-4 space-y-4">
            <div class="bg-amber-50 border border-amber-200 rounded-lg p-3">
                <p class="text-xs text-amber-700">Please provide proof of ownership. This helps us verify legitimate claims.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Proof of Ownership <span class="text-red-500">*</span></label>
                <textarea
                        required
                        rows="3"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none text-sm"
                        placeholder="Describe specific details only the owner would know (e.g., screen lock pattern, unique marks, contents, receipts, etc.)"
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1.5">Additional Notes</label>
                <textarea
                        rows="2"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent resize-none text-sm"
                        placeholder="Any other information that might help verify your claim"
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

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-sm w-full p-6 text-center">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Claim Submitted!</h3>
        <p class="text-sm text-gray-500 mb-6">Your claim has been submitted for admin review. We'll notify you once it's processed.</p>
        <button onclick="closeSuccessModal()" class="w-full py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
            Done
        </button>
    </div>
</div>


<script src="{{asset('js/index.js')}}"></script>
<script>
    // Claim Modal
    const claimModal = document.getElementById('claimModal');
    const successModal = document.getElementById('successModal');
    const claimForm = document.getElementById('claimForm');

    function openClaimModal() {
        claimModal.classList.remove('hidden');
        claimModal.classList.add('flex');
    }

    function closeClaimModal() {
        claimModal.classList.add('hidden');
        claimModal.classList.remove('flex');
    }

    function closeSuccessModal() {
        successModal.classList.add('hidden');
        successModal.classList.remove('flex');
    }

    claimForm.addEventListener('submit', (e) => {
        e.preventDefault();
        closeClaimModal();
        successModal.classList.remove('hidden');
        successModal.classList.add('flex');
    });
</script>
</body>
</html>
