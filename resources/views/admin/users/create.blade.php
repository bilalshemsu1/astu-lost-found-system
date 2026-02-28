<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create User - ASTU Lost & Found Admin</title>
    <x-common-head-scripts />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Create User" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        @if($errors->any())
            <div class="mb-4 max-w-xl rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="max-w-xl bg-white rounded-xl border border-gray-200 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Create User</h2>
            <p class="text-sm text-gray-500 mb-6">Create a student or admin account.</p>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                    <input type="text" name="name" value="{{ old('name') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="Enter full name" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="Enter email" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                    <input type="text" name="phone" value="{{ old('phone') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="e.g., +2519XXXXXXXX" required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Student ID (Optional)</label>
                    <input type="text" name="student_id" value="{{ old('student_id') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="e.g., ETS12345/15">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Telegram Username (Optional)</label>
                    <input type="text" name="telegram_username" value="{{ old('telegram_username') }}" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="e.g., @username">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" required>
                        <option value="student" {{ old('role', 'student') === 'student' ? 'selected' : '' }}>Student</option>
                        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>
                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input type="password" name="password" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" placeholder="At least 8 characters" required>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <input type="password" name="password_confirmation" class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm" required>
                    </div>
                </div>
                <div class="pt-2 flex gap-3">
                    <a href="{{ route('admin.users') }}" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">Create User</button>
                </div>
            </form>
        </div>
    </main>
</div>

<x-common-page-scripts />
</body>
</html>
