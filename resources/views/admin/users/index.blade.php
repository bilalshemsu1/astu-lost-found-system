<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Users - ASTU Lost & Found Admin</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Users" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        @if(session('success'))
            <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 mb-6">
            <x-admin-stat-card label="Total Users" :value="$totalUsers ?? 0" />
            <x-admin-stat-card label="Students" :value="$studentUsers ?? 0" valueClass="text-primary-700" />
            <x-admin-stat-card label="Admins" :value="$adminUsers ?? 0" />
            <x-admin-stat-card label="Active Today" :value="$activeTodayUsers ?? 0" valueClass="text-green-700" />
        </div>

        <form method="GET" action="{{ route('admin.users') }}" class="bg-white rounded-xl border border-gray-200 p-4 mb-5">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-3">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Search name/email/student id..." class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm lg:col-span-2">
                <select name="role" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="">All Roles</option>
                    <option value="student" {{ request('role') === 'student' ? 'selected' : '' }}>Student</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <select name="sort" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                    <option value="latest" {{ request('sort', 'latest') === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="oldest" {{ request('sort') === 'oldest' ? 'selected' : '' }}>Oldest</option>
                    <option value="trust_desc" {{ request('sort') === 'trust_desc' ? 'selected' : '' }}>Trust High-Low</option>
                    <option value="trust_asc" {{ request('sort') === 'trust_asc' ? 'selected' : '' }}>Trust Low-High</option>
                </select>
            </div>
            <div class="mt-3 flex gap-2">
                <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Apply Filters</button>
                <a href="{{ route('admin.users.create') }}" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Create User</a>
            </div>
        </form>

        <div class="bg-white rounded-xl border border-gray-200 overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500">
                <tr>
                    <th class="px-4 py-3 text-left font-medium">User</th>
                    <th class="px-4 py-3 text-left font-medium">Role</th>
                    <th class="px-4 py-3 text-left font-medium">Trust</th>
                    <th class="px-4 py-3 text-left font-medium">Items</th>
                    <th class="px-4 py-3 text-left font-medium">Returned</th>
                    <th class="px-4 py-3 text-left font-medium">Joined</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-3">
                            <p class="font-medium text-gray-900">{{ $user->name }}</p>
                            <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            <p class="text-xs text-gray-500">{{ $user->phone ?? '-' }}</p>
                            @if($user->student_id)
                                <p class="text-xs text-gray-400">{{ $user->student_id }}</p>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            <span class="text-xs font-medium px-2 py-0.5 rounded {{ $user->role === 'admin' ? 'bg-purple-50 text-purple-700' : 'bg-blue-50 text-blue-700' }}">{{ ucfirst($user->role) }}</span>
                        </td>
                        <td class="px-4 py-3 text-gray-700">{{ $user->trust_score >= 0 ? '+' : '' }}{{ $user->trust_score }}</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->items_count }} (L: {{ $user->lost_items_count }}, F: {{ $user->found_items_count }})</td>
                        <td class="px-4 py-3 text-gray-600">{{ $user->returned_items_count }}</td>
                        <td class="px-4 py-3 text-gray-500">{{ $user->created_at->format('M d, Y') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-4 py-8 text-center text-gray-500">No users found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $users->withQueryString()->links() }}
        </div>
    </main>
</div>

<script src="{{ asset('js/index.js') }}"></script>
</body>
</html>
