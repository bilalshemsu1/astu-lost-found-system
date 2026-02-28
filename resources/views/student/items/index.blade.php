<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Items - ASTU Lost & Found</title>
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
    <x-student-header title="Items Collection" trustScore="3" />

    <!-- Page Content -->
    <main class="flex-1 p-4 sm:p-6">

        <!-- ========== FILTER FORM ========== -->
        <form action="{{ route('student.items') }}" method="GET" id="filterForm">

            <!-- Search + Buttons -->
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </span>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Search by item name..."
                           class="w-full pl-10 pr-4 py-2.5 border border-gray-300 rounded-lg focus:ring-primary-500 focus:border-primary-500">
                </div>
                <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg hover:bg-primary-700">
                    Apply
                </button>
                <button type="button" onclick="toggleFilters()"
                        class="px-4 py-2.5 border border-gray-300 rounded-lg hover:bg-gray-50">
                    Filters
                </button>
            </div>

            <!-- Filters Panel-->
            <div id="filtersPanel"
                 class="{{ request()->hasAny(['type', 'category', 'date', 'location']) ? 'hidden' : '' }} mt-4 pt-4 border-t border-gray-200">
                <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-4">

                    <!-- Type -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Type</label>
                        <select name="type" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="">All Types</option>
                            <option value="lost" {{ request('type') == 'lost' ? 'selected' : '' }}>Lost</option>
                            <option value="found" {{ request('type') == 'found' ? 'selected' : '' }}>Found</option>
                        </select>
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Category</label>
                        <select name="category" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="">All Categories</option>
                            <option value="electronics" {{ request('category') == 'electronics' ? 'selected' : '' }}>Electronics</option>
                            <option value="books" {{ request('category') == 'books' ? 'selected' : '' }}>Books</option>
                            <option value="keys" {{ request('category') == 'keys' ? 'selected' : '' }}>Keys</option>
                            <option value="cards" {{ request('category') == 'cards' ? 'selected' : '' }}>ID Cards</option>
                            <option value="clothing" {{ request('category') == 'clothing' ? 'selected' : '' }}>Clothing</option>
                            <option value="bags" {{ request('category') == 'bags' ? 'selected' : '' }}>Bags</option>
                            <option value="other" {{ request('category') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                    </div>

                    <!-- Date -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Date</label>
                        <select name="date" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="">Any Time</option>
                            <option value="today" {{ request('date') == 'today' ? 'selected' : '' }}>Today</option>
                            <option value="week" {{ request('date') == 'week' ? 'selected' : '' }}>This Week</option>
                            <option value="month" {{ request('date') == 'month' ? 'selected' : '' }}>This Month</option>
                        </select>
                    </div>

                    <!-- Location -->
                    <div>
                        <label class="block text-xs text-gray-500 mb-1">Location</label>
                        <select name="location" class="w-full px-3 py-2 text-sm border border-gray-300 rounded-lg">
                            <option value="">All Locations</option>
                            <option value="library" {{ request('location') == 'library' ? 'selected' : '' }}>Library</option>
                            <option value="cafeteria" {{ request('location') == 'cafeteria' ? 'selected' : '' }}>Cafeteria</option>
                            <option value="engineering" {{ request('location') == 'engineering' ? 'selected' : '' }}>Engineering Block</option>
                            <option value="admin" {{ request('location') == 'admin' ? 'selected' : '' }}>Admin Building</option>
                            <option value="sports" {{ request('location') == 'sports' ? 'selected' : '' }}>Sports Complex</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Results Header + Sort (inside form so sort submits with filters) -->
            <div class="flex items-center justify-between mt-6 mb-4">
                <p class="text-sm text-gray-500">
                    Showing <span class="font-medium text-gray-900">{{ $items->total() }}</span> items
                </p>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-500">Sort by:</label>
                    <select name="sort" onchange="this.form.submit()"
                            class="text-sm border border-gray-300 rounded-lg px-3 py-1.5 focus:ring-primary-500 focus:border-primary-500">
                        <option value="recent" {{ request('sort') == 'recent' ? 'selected' : '' }}>Most Recent</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Oldest First</option>
                    </select>
                </div>
            </div>

        </form>
        <!-- ========== END FILTER FORM ========== -->


        <!-- ========== ITEMS GRID ========== -->
        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-4">

            @forelse($items as $item)
                <div onclick="openItemModal(this)"
                     data-type="{{ $item->type }}"
                     data-title="{{ $item->title }}"
                     data-category="{{ $item->category }}"
                     data-location="{{ $item->location }}"
                     data-date="{{ $item->created_at->diffForHumans() }}"
                     data-description="{{ $item->description ?? '' }}"
                     data-image="{{ $item->image_path ?? '' }}"
                     class="bg-white rounded-xl border border-gray-200 overflow-hidden hover:border-primary-200 transition-colors cursor-pointer">

                    <!-- Image -->
                    <div class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
                        <span class="absolute top-2 left-2 z-10 text-xs font-medium {{ $item->type === 'lost' ? 'bg-red-500 text-white' : 'bg-green-500 text-white' }} px-2 py-0.5 rounded">
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

                    <!-- Card Body -->
                    <div class="p-4">
                        <h3 class="font-medium text-gray-900 mb-1">{{ ucfirst($item->title) }}</h3>
                        <p class="text-sm text-gray-500 mb-3 line-clamp-2">{{ ucfirst($item->category) }}</p>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span class="flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                </svg>
                                {{ Str::limit($item->location, 20) }}
                            </span>
                            <span>{{ $item->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                </div>

            @empty
                <div class="col-span-full text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <p class="text-gray-500">No items found</p>
                </div>
            @endforelse

        </div>
        <!-- ========== END ITEMS GRID ========== -->


        <!-- ========== PAGINATION ========== -->
        @if ($items->hasPages())
            <div class="mt-6 flex items-center justify-center gap-2">
                {{-- Previous --}}
                @if ($items->onFirstPage())
                    <button disabled class="p-2 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                @else
                    <a href="{{ $items->appends(request()->query())->previousPageUrl() }}"
                       class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </a>
                @endif

                {{-- Page Numbers --}}
                @foreach ($items->appends(request()->query())->getUrlRange(1, $items->lastPage()) as $page => $url)
                    @if ($page == $items->currentPage())
                        <button class="w-9 h-9 text-sm font-medium bg-primary-600 text-white rounded-lg">{{ $page }}</button>
                    @else
                        <a href="{{ $url }}"
                           class="w-9 h-9 text-sm font-medium text-gray-600 hover:bg-gray-100 rounded-lg flex items-center justify-center transition-colors">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                {{-- Next --}}
                @if ($items->hasMorePages())
                    <a href="{{ $items->appends(request()->query())->nextPageUrl() }}"
                       class="p-2 text-gray-600 hover:text-gray-900 hover:bg-gray-100 rounded-lg transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @else
                    <button disabled class="p-2 text-gray-400 cursor-not-allowed">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                @endif
            </div>
        @endif
        <!-- ========== END PAGINATION ========== -->

    </main>
</div>


<!-- ========== ITEM DETAIL MODAL ========== -->
<div id="itemModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-md w-full overflow-hidden">

        <!-- Modal Image -->
        <div id="modalImageContainer" class="aspect-video bg-gray-100 flex items-center justify-center relative overflow-hidden">
            <img id="modalImg" src="" alt="" class="h-full w-full object-cover hidden">
            <svg id="modalPlaceholder" class="w-12 h-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <div id="blurOverlay" class="absolute inset-0 backdrop-blur-[1px] bg-gray-100/30 hidden"></div>
            <span id="modalBadge" class="absolute top-3 left-3 text-xs font-medium px-2 py-1 rounded"></span>
        </div>

        <!-- Modal Content -->
        <div class="p-5">
            <div class="flex items-start justify-between mb-4">
                <h2 id="modalTitle" class="text-lg font-semibold text-gray-900"></h2>
                <button onclick="closeItemModal()" class="text-gray-400 hover:text-gray-600 p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            <div class="space-y-3">
                <!-- Category -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Category</p>
                        <p id="modalCategory" class="text-sm font-medium text-gray-900"></p>
                    </div>
                </div>

                <!-- Location -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Location</p>
                        <p id="modalLocation" class="text-sm font-medium text-gray-900"></p>
                    </div>
                </div>

                <!-- Date -->
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500">Date</p>
                        <p id="modalDate" class="text-sm font-medium text-gray-900"></p>
                    </div>
                </div>

                <!-- Description (hidden for found items) -->
                <div id="modalDescriptionContainer" class="flex items-start gap-3 hidden">
                    <div class="w-8 h-8 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs text-gray-500">Description</p>
                        <p id="modalDescription" class="text-sm text-gray-700 mt-0.5"></p>
                    </div>
                </div>
            </div>

            <!-- Security Notice (found items only) -->
            <div id="securityNotice" class="hidden mt-4 bg-amber-50 border border-amber-200 rounded-lg p-3">
                <div class="flex items-start gap-2">
                    <svg class="w-4 h-4 text-amber-600 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-xs text-amber-700">For security, detailed descriptions and clear images are only shared after your claim is approved.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-5 flex gap-3">
                <button onclick="closeItemModal()" class="flex-1 py-2.5 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-sm">
                    Close
                </button>
                <button id="claimBtn" class="hidden flex-1 py-2.5 px-4 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm">
                    Claim This Item
                </button>
                <button id="contactBtn" class="hidden flex-1 py-2.5 px-4 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors text-sm">
                    Contact Owner
                </button>
            </div>
        </div>
    </div>
</div>
<!-- ========== END MODAL ========== -->


<!-- ========== SCRIPTS ========== -->
<script src="{{ asset('js/index.js') }}"></script>
<script>
    // Toggle filters panel
    function toggleFilters() {
        document.getElementById('filtersPanel').classList.toggle('hidden');
    }

    // Modal Elements
    const itemModal = document.getElementById('itemModal');
    const modalTitle = document.getElementById('modalTitle');
    const modalCategory = document.getElementById('modalCategory');
    const modalLocation = document.getElementById('modalLocation');
    const modalDate = document.getElementById('modalDate');
    const modalDescription = document.getElementById('modalDescription');
    const modalDescriptionContainer = document.getElementById('modalDescriptionContainer');
    const modalImg = document.getElementById('modalImg');
    const modalPlaceholder = document.getElementById('modalPlaceholder');
    const modalBadge = document.getElementById('modalBadge');
    const blurOverlay = document.getElementById('blurOverlay');
    const securityNotice = document.getElementById('securityNotice');
    const claimBtn = document.getElementById('claimBtn');
    const contactBtn = document.getElementById('contactBtn');

    function openItemModal(element) {
        const type = element.dataset.type;
        const title = element.dataset.title;
        const category = element.dataset.category;
        const location = element.dataset.location;
        const date = element.dataset.date;
        const description = element.dataset.description || 'No description provided';
        const image = element.dataset.image;

        modalTitle.textContent = title;
        modalCategory.textContent = capitalizeFirst(category);
        modalLocation.textContent = location;
        modalDate.textContent = date;
        modalDescription.textContent = description;

        // Image handling
        if (image && image.trim() !== '') {
            modalImg.src = '/storage/' + image;
            modalImg.classList.remove('hidden');
            modalPlaceholder.classList.add('hidden');
        } else {
            modalImg.classList.add('hidden');
            modalPlaceholder.classList.remove('hidden');
        }

        // Found vs Lost display
        if (type.toLowerCase() === 'found') {
            modalBadge.textContent = 'Found';
            modalBadge.className = 'absolute top-3 left-3 text-xs font-medium bg-green-500 text-white px-2 py-1 rounded';
            blurOverlay.classList.remove('hidden');
            securityNotice.classList.remove('hidden');
            modalDescriptionContainer.classList.add('hidden');
            claimBtn.classList.remove('hidden');
            claimBtn.classList.add('flex');
            contactBtn.classList.add('hidden');
            contactBtn.classList.remove('flex');
        } else {
            modalBadge.textContent = 'Lost';
            modalBadge.className = 'absolute top-3 left-3 text-xs font-medium bg-red-500 text-white px-2 py-1 rounded';
            blurOverlay.classList.add('hidden');
            securityNotice.classList.add('hidden');
            modalDescriptionContainer.classList.remove('hidden');
            claimBtn.classList.add('hidden');
            claimBtn.classList.remove('flex');
            contactBtn.classList.remove('hidden');
            contactBtn.classList.add('flex');
        }

        itemModal.classList.remove('hidden');
        itemModal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeItemModal() {
        itemModal.classList.add('hidden');
        itemModal.classList.remove('flex');
        document.body.style.overflow = '';
    }

    function capitalizeFirst(str) {
        if (!str) return '';
        return str.charAt(0).toUpperCase() + str.slice(1);
    }

    // Close on backdrop click
    itemModal.addEventListener('click', (e) => {
        if (e.target === itemModal) closeItemModal();
    });

    // Close on Escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !itemModal.classList.contains('hidden')) closeItemModal();
    });
</script>

</body>
</html>