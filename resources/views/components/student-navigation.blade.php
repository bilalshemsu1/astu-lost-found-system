<aside id="sidebar" class="sidebar-panel closed fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 lg:translate-x-0 lg:z-auto">
    <!-- Logo -->
    <div class="h-16 flex items-center justify-between px-4 lg:px-6 border-b border-gray-200">
        <a href="{{ route('student.dashboard') }}" class="flex items-center gap-3">
            <div class="w-8 h-8 bg-primary-600 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <span class="font-semibold text-gray-900">ASTU L&F</span>
        </a>
        <button onclick="toggleSidebar()" class="lg:hidden p-2 text-gray-400 hover:text-gray-600">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Navigation -->
    <nav class="p-4 space-y-1 overflow-y-auto" style="height: calc(100vh - 8rem);">
        <x-sidebar-link 
            href="{{ route('student.dashboard') }}" 
            label="Dashboard" 
            :active="request()->routeIs('student.dashboard')"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
            </x-slot>
        </x-sidebar-link>

        <x-sidebar-link 
            href="{{ route('student.lost') }}" 
            label="Report Lost Item"
            :active="request()->routeIs('student.lost')"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </x-slot>
        </x-sidebar-link>

        <x-sidebar-link 
            href="{{ route('student.found') }}" 
            label="Report Found Item"
            :active="request()->routeIs('student.found')"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </x-slot>
        </x-sidebar-link>

        <x-sidebar-link 
            href="{{ route('student.items') }}" 
            label="Items Collection"
            :active="request()->routeIs('student.items')"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </x-slot>
        </x-sidebar-link>

        <x-sidebar-link 
            href="{{ route('student.matches') }}" 
            label="My Matches"
            :active="request()->routeIs('student.matches')"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1" />
            </x-slot>
            <span class="ml-auto bg-primary-100 text-primary-700 text-xs font-medium px-2 py-0.5 rounded-full">
                {{ $unreadMatchCount > 0 ? $unreadMatchCount : '' }}
            </span>
        </x-sidebar-link>

        <x-sidebar-link 
            href="{{ route('student.claims.show') }}" 
            label="My Claims"
            :active="request()->routeIs('student.claims.show')"
        >
            <x-slot name="icon">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                    d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </x-slot>
        </x-sidebar-link>
    </nav>

    <!-- User Section -->
    <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
        <div class="flex items-center gap-3">
            <div class="w-9 h-9 bg-primary-100 text-primary-600 rounded-full flex items-center justify-center font-semibold text-sm flex-shrink-0">
                @php
                    $name = auth()->user()->name;  
                    $nameParts = explode(' ', $name);

                    $firstNameLetter = '';
                    $lastNameLetter = '';

                    if (count($nameParts) > 0) {
                        $firstNameLetter = strtoupper($nameParts[0][0]);

                        if (count($nameParts) > 1) {
                            $lastNameLetter = strtoupper($nameParts[1][0]);
                        }
                    }
                @endphp
                {{ $firstNameLetter }}{{ $lastNameLetter }}
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">{{auth()->user()->name}}</p>
                <p class="text-xs text-gray-500 truncate">{{auth()->user()->email}}</p>
            </div>
            <a href="{{route('logout')}}" class="text-gray-400 hover:text-gray-600 flex-shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                </svg>
            </a>
        </div>
    </div>
</aside>