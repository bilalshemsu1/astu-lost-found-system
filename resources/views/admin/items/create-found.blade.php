<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Found Item - ASTU Lost & Found Admin</title>
    <x-common-head-scripts />
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>
<x-admin-navigation :pendingCount="$pendingCount ?? 0" :pendingClaimsCount="$pendingClaimsCount ?? 0" />

<div class="lg:ml-64 min-h-screen flex flex-col">
    <x-admin-header title="Create Found Item" :notificationsCount="$pendingCount + $pendingClaimsCount" />

    <main class="flex-1 p-4 sm:p-6">
        @if($errors->any())
            <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="max-w-3xl bg-white rounded-xl border border-gray-200 p-6">
            <p class="text-sm text-gray-500 mb-6">Admin-created found items are published directly and included in matching immediately.</p>

            <form method="POST" action="{{ route('admin.items.found.store') }}" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">Item Name <span class="text-red-500">*</span></label>
                    <input
                        id="title"
                        name="title"
                        type="text"
                        required
                        value="{{ old('title') }}"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"
                        placeholder="e.g., Black Backpack"
                    >
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-1.5">Category <span class="text-red-500">*</span></label>
                    <select id="category" name="category" required class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm">
                        <option value="">Select category</option>
                        @foreach(($categories ?? []) as $key => $label)
                            <option value="{{ $key }}" {{ old('category') === $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">Description</label>
                    <textarea
                        id="description"
                        name="description"
                        rows="4"
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"
                        placeholder="Describe color, marks, contents, and useful identifying details."
                    >{{ old('description') }}</textarea>
                </div>

                <div class="grid sm:grid-cols-2 gap-4">
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">Found Location <span class="text-red-500">*</span></label>
                        <input
                            id="location"
                            name="location"
                            type="text"
                            required
                            value="{{ old('location') }}"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"
                            placeholder="e.g., Library 2nd floor"
                        >
                    </div>
                    <div>
                        <label for="item_date" class="block text-sm font-medium text-gray-700 mb-1.5">Date Found <span class="text-red-500">*</span></label>
                        <input
                            id="item_date"
                            name="item_date"
                            type="date"
                            required
                            value="{{ old('item_date') }}"
                            class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm"
                        >
                    </div>
                </div>

                <div>
                    <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">Image <span class="text-red-500">*</span></label>
                    <input
                        id="image"
                        name="image"
                        type="file"
                        accept="image/jpeg,image/png,image/gif"
                        required
                        class="w-full px-3 py-2.5 border border-gray-300 rounded-lg text-sm file:mr-3 file:py-1.5 file:px-3 file:border-0 file:bg-gray-100 file:text-gray-700 file:rounded"
                    >
                </div>

                <div class="pt-2 flex gap-3">
                    <a href="{{ route('admin.items') }}" class="px-4 py-2.5 border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50">Cancel</a>
                    <button type="submit" class="px-4 py-2.5 bg-primary-600 text-white rounded-lg text-sm font-medium hover:bg-primary-700">
                        Create Found Item
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>

<x-common-page-scripts />
</body>
</html>

