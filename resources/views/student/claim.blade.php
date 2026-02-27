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

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<x-student-navigation/>

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <x-student-header title="My Claims" trustScore="3" />

    <!-- Page Content -->
    <main class="flex-1 p-4 sm:p-6">
        <!-- Tabs -->
        <div class="flex gap-2 mb-6 overflow-x-auto pb-2">
            <button class="tab-btn active px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="all">
                All Claims
            </button>
            <button class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="pending">
                Pending
                <span class="ml-1 bg-amber-100 text-amber-700 text-xs px-1.5 py-0.5 rounded-full">1</span>
            </button>
            <button class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="approved">
                Approved
                <span class="ml-1 bg-green-100 text-green-700 text-xs px-1.5 py-0.5 rounded-full">1</span>
            </button>
            <button class="tab-btn px-4 py-2 text-sm font-medium border-b-2 border-transparent text-gray-500 hover:text-gray-700 whitespace-nowrap" data-tab="rejected">
                Rejected
            </button>
        </div>

        <!-- Claims List -->
        <div class="space-y-4">
            <!-- Claim Card - Approved -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium bg-green-50 text-green-700 px-2 py-0.5 rounded-full">Approved</span>
                            <span class="text-sm font-medium text-gray-900">Claim #CLM-001</span>
                        </div>
                        <span class="text-xs text-gray-400">Approved on Feb 24, 2026</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 mb-1">Set of Keys</h3>
                            <p class="text-sm text-gray-500 mb-2">3 keys on a blue keychain with teddy bear</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Cafeteria
                                    </span>
                                <span>Match Eligible</span>
                                <span class="text-green-600 font-medium">Returned</span>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Info -->
                    <div class="mt-4 bg-green-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-green-800 mb-2">Finder Contact Information</p>
                        <div class="space-y-1 text-sm">
                            <p class="text-green-700"><span class="font-medium">Name:</span> Sara Tesfaye</p>
                            <p class="text-green-700"><span class="font-medium">Phone:</span> +251 91 234 5678</p>
                            <p class="text-green-700"><span class="font-medium">Telegram:</span> @sara_tesfaye</p>
                        </div>
                        <p class="text-xs text-green-600 mt-3">Please contact the finder to arrange pickup. If they don't respond, visit the Admin Office.</p>
                    </div>
                </div>
            </div>

            <!-- Claim Card - Pending -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium bg-amber-50 text-amber-700 px-2 py-0.5 rounded-full">Pending Review</span>
                            <span class="text-sm font-medium text-gray-900">Claim #CLM-002</span>
                        </div>
                        <span class="text-xs text-gray-400">Submitted 2 hours ago</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 mb-1">iPhone 15 Pro Max</h3>
                            <p class="text-sm text-gray-500 mb-2">Black case with student ID inside</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Library
                                    </span>
                                <span>Under Review</span>
                            </div>
                        </div>
                    </div>

                    <!-- Status Info -->
                    <div class="mt-4 bg-amber-50 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-amber-800">Awaiting Admin Review</p>
                                <p class="text-xs text-amber-600 mt-1">An administrator will review your claim and proof of ownership. You'll be notified once a decision is made.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Your Proof -->
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-2">Your Submitted Proof</p>
                        <div class="bg-gray-50 rounded-lg p-3">
                            <p class="text-sm text-gray-600">"The phone has a small scratch on the back near the camera. The lock screen is a photo of my dog. The case has my student ID (ATR/1234/15) inside the card slot."</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Claim Card - Rejected -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden opacity-75">
                <div class="p-4 border-b border-gray-100">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-medium bg-red-50 text-red-700 px-2 py-0.5 rounded-full">Rejected</span>
                            <span class="text-sm font-medium text-gray-900">Claim #CLM-003</span>
                        </div>
                        <span class="text-xs text-gray-400">Rejected on Feb 20, 2026</span>
                    </div>
                </div>
                <div class="p-4">
                    <div class="flex flex-col sm:flex-row gap-4">
                        <div class="w-20 h-20 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div class="flex-1">
                            <h3 class="font-medium text-gray-900 mb-1">Physics Textbook</h3>
                            <p class="text-sm text-gray-500 mb-2">University Physics 3rd edition</p>
                            <div class="flex flex-wrap items-center gap-3 text-xs text-gray-400">
                                    <span class="flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        </svg>
                                        Library
                                    </span>
                                <span>Low Confidence</span>
                            </div>
                        </div>
                    </div>

                    <!-- Rejection Reason -->
                    <div class="mt-4 bg-red-50 rounded-lg p-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-red-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <p class="text-sm font-medium text-red-800">Claim Rejected</p>
                                <p class="text-xs text-red-600 mt-1"><span class="font-medium">Reason:</span> The provided proof was insufficient to verify ownership. The book was claimed by another student with a valid receipt.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Similar Items Suggestion -->
                    <div class="mt-4">
                        <p class="text-xs font-medium text-gray-500 uppercase mb-2">Similar Items You Might Check</p>
                        <div class="space-y-2">
                            <a href="#" class="flex items-center gap-3 p-2 bg-gray-50 rounded-lg hover:bg-gray-100 transition-colors">
                                <div class="w-10 h-10 bg-gray-200 rounded flex items-center justify-center">
                                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="text-sm font-medium text-gray-900">Physics Textbook</p>
                                    <p class="text-xs text-gray-500">Possible similar item • Found 3 days ago</p>
                                </div>
                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Empty State -->
        <div class="hidden bg-white rounded-xl border border-gray-200 p-8 text-center">
            <div class="w-16 h-16 bg-gray-100 text-gray-400 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">No Claims Yet</h3>
            <p class="text-sm text-gray-500 mb-4">When you submit claims for matched items, they'll appear here.</p>
            <a href="student-matches.html" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700">
                View Your Matches
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                </svg>
            </a>
        </div>
    </main>
</div>


<script src="{{asset('js/index.js')}}"></script>
<script>
    // Tab switching
    document.querySelectorAll('.tab-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
        });
    });
</script>
</body>
</html>
