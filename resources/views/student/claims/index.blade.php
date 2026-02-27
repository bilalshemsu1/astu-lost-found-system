<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Claims - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{asset('js/script.js')}}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
@include('student.layouts.navigation')

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <header class="h-14 sm:h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 sticky top-0 z-30">
        <div class="flex items-center gap-2 sm:gap-3">
            <button onclick="toggleSidebar()" class="lg:hidden p-2 -ml-2 text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
            <h1 class="text-base sm:text-lg font-semibold text-gray-900">My Claims</h1>
        </div>
        <div class="flex items-center gap-2 sm:gap-4">
            <div class="hidden sm:flex items-center gap-2 bg-green-50 text-green-700 px-3 py-1.5 rounded-full">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                </svg>
                <span class="text-sm font-medium">Trust: +3</span>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="flex-1 p-4 sm:p-6">
        
    </main>
</div>

<script>
    function openSidebar() {
        if (window.innerWidth >= 1024) return;
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.remove('closed');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
    }

    function closeSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        sidebar.classList.add('closed');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
    }

    function toggleSidebar() {
        if (window.innerWidth >= 1024) return;
        const sidebar = document.getElementById('sidebar');

        if (sidebar.classList.contains('closed')) {
            openSidebar();
        } else {
            closeSidebar();
        }
    }

    document.querySelectorAll('.sidebar-link').forEach(link => {
        link.addEventListener('click', () => {
            if (window.innerWidth < 1024) {
                closeSidebar();
            }
        });
    });

    window.addEventListener('resize', () => {
        if (window.innerWidth >= 1024) {
            closeSidebar();
        }
    });

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
