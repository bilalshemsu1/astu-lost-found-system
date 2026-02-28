<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Create User" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        <div class="max-w-xl bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Create User</h2>
            <p class="text-sm text-gray-500 mb-6">UI is ready. Submit handling will be connected in a later update.</p>

            <form class="space-y-4" onsubmit="return false;">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="Enter full name">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="Enter email">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option>Student</option>
                        <option>Admin</option>
                    </select>
                </div>
                <button type="submit" class="px-4 py-2.5 bg-gray-300 text-gray-600 rounded-lg text-sm font-medium cursor-not-allowed">Save (Coming Soon)</button>
            </form>
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
