<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-student-navigation/>

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-student-header title="Dashboard" :trustScore="$trustScore" />

    <main class="flex-1 p-4 sm:p-6">
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-6 mb-6">
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <p class="text-xs sm:text-sm text-gray-500">My Lost Items</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $myLostCount }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <p class="text-xs sm:text-sm text-gray-500">My Found Items</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $myFoundCount }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <p class="text-xs sm:text-sm text-gray-500">Items Returned</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $itemsReturnedCount }}</p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-4 sm:p-5">
                <p class="text-xs sm:text-sm text-gray-500">Active Matches</p>
                <p class="text-xl sm:text-2xl font-bold text-gray-900 mt-1">{{ $activeMatchesCount }}</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-4 sm:gap-6">
            <div class="lg:col-span-2 bg-white rounded-xl border border-gray-200">
                <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base">My Recent Items</h2>
                    <a href="{{ route('student.my-items') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">View all</a>
                </div>

                <div class="divide-y divide-gray-100">
                    @forelse($recentItems as $item)
                        <div class="p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                            <div class="flex items-start gap-3">
                                <div class="w-12 h-12 sm:w-14 sm:h-14 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0 overflow-hidden">
                                    @if($item->image_path)
                                        <img src="{{ asset('storage/' . $item->image_path) }}" alt="{{ $item->title }}" class="h-full w-full object-cover">
                                    @else
                                        <svg class="w-6 h-6 sm:w-7 sm:h-7 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-1.5 sm:gap-2 mb-1">
                                        <span class="text-xs font-medium px-2 py-0.5 rounded {{ $item->type === 'lost' ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                                            {{ ucfirst($item->type) }}
                                        </span>
                                        <span class="text-xs font-medium px-2 py-0.5 rounded {{ $item->has_match ? 'bg-amber-50 text-amber-700' : 'bg-gray-100 text-gray-600' }}">
                                            {{ $item->has_match ? 'Has Match' : 'No Match' }}
                                        </span>
                                    </div>
                                    <h3 class="font-medium text-gray-900 text-sm sm:text-base mb-1">{{ $item->title }}</h3>
                                    <p class="text-xs sm:text-sm text-gray-500 line-clamp-2">{{ $item->description ? \Illuminate\Support\Str::limit($item->description, 100) : 'No description provided.' }}</p>
                                    <div class="flex flex-wrap items-center gap-2 sm:gap-4 mt-2 text-xs text-gray-400">
                                        <span>{{ $item->location }}</span>
                                        <span>{{ $item->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-8 text-center">
                            <p class="text-sm text-gray-500">No posted items yet.</p>
                            <div class="mt-3 flex items-center justify-center gap-2">
                                <a href="{{ route('student.lost') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Report lost</a>
                                <span class="text-gray-300">|</span>
                                <a href="{{ route('student.found') }}" class="text-sm font-medium text-primary-600 hover:text-primary-700">Report found</a>
                            </div>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="space-y-4 sm:space-y-6">
                <div class="bg-white rounded-xl border border-gray-200">
                    <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                        <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Notifications</h2>
                        <a href="{{ route('student.claims') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">View claims</a>
                    </div>

                    <div class="divide-y divide-gray-100">
                        @forelse($recentClaims as $claim)
                            @php
                                $item = $claim->item;
                                $itemLabel = $item->title ?? 'item';
                            @endphp
                            <div class="p-3 sm:p-4">
                                <p class="text-sm font-medium text-gray-900">
                                    {{ ucfirst($claim->status) }} claim update
                                </p>
                                <p class="text-xs text-gray-600 mt-0.5">
                                    Your request for {{ $itemLabel }} was {{ $claim->status }}.
                                </p>
                                <p class="text-xs text-gray-400 mt-1">{{ $claim->created_at->diffForHumans() }}</p>
                            </div>
                        @empty
                            <div class="p-4 text-sm text-gray-500">No recent claim updates.</div>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white rounded-xl border border-gray-200 p-4">
                    <h2 class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Trust Score</h2>
                    <p class="text-2xl font-bold text-green-700">+{{ $trustScore }}</p>
                    <p class="text-xs text-gray-500 mt-1">Based on approved returns from your found-item posts.</p>
                </div>
            </div>
        </div>

        <div class="mt-4 sm:mt-6 bg-white rounded-xl border border-gray-200">
            <div class="p-4 border-b border-gray-200 flex items-center justify-between gap-2">
                <h2 class="font-semibold text-gray-900 text-sm sm:text-base">Potential Matches for Your Lost Items</h2>
                <a href="{{ route('student.matches') }}" class="text-xs font-medium text-primary-600 hover:text-primary-700">Open matches</a>
            </div>

            <div class="p-3 sm:p-4 grid sm:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
                @forelse($recentMatchCandidates as $match)
                    @php
                        $lost = $match->lostItem;
                    @endphp
                    <div class="border border-gray-200 rounded-lg p-3 sm:p-4">
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-xs font-medium px-2 py-0.5 rounded {{ $match->similarity_percentage >= 80 ? 'bg-green-50 text-green-700' : 'bg-yellow-50 text-yellow-700' }}">
                                {{ $match->similarity_percentage >= 80 ? 'Strong Candidate' : 'Potential Match' }}
                            </span>
                            <span class="text-xs text-gray-400">{{ $match->created_at->diffForHumans() }}</span>
                        </div>

                        <h4 class="font-medium text-gray-900 text-sm mb-1">{{ $lost?->title ?? 'Lost item' }}</h4>
                        <p class="text-xs text-gray-500 mb-2">Found item images are blurred for non-owners until claim review.</p>
                        <a href="{{ route('student.matches') }}" class="inline-flex items-center text-xs font-medium text-primary-600 hover:text-primary-700">
                            Review and claim
                        </a>
                    </div>
                @empty
                    <div class="sm:col-span-2 lg:col-span-3 text-center py-6">
                        <p class="text-sm text-gray-500">No match candidates yet.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </main>
</div>

<script src="{{asset('js/index.js')}}"></script>
</body>
</html>
