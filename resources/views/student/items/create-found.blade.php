<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Found Item - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="{{ asset('js/script.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/post.css') }}">
</head>
<body class="bg-gray-50 text-gray-900 antialiased overflow-x-hidden">

<!-- Mobile Sidebar Overlay -->
<div id="sidebarOverlay" class="sidebar-overlay fixed inset-0 z-40 hidden lg:hidden" onclick="closeSidebar()"></div>

<!-- Sidebar -->
<x-student-navigation/>

<!-- Main Content -->
<div class="lg:ml-64 min-h-screen flex flex-col">
    <!-- Top Bar -->
    <x-student-header title="Post Found Items" trustScore="3" />

    <!-- Page Content -->
    <main class="flex-1 p-4 sm:p-6">
        <div class="max-w-2xl mx-auto">
            
            <!-- Form Card -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900">Found Item Details</h2>
                    <p class="text-sm text-gray-500 mt-1">Please provide accurate details to help the owner identify their item</p>
                </div>

                <form id="foundItemForm" class="p-4 sm:p-6 space-y-5"  method="POST" action="{{route('student.found.post')}}" enctype="multipart/form-data" novalidate>
                    @if(session('success'))
                        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4">
                            {{ session('success') }}
                        </div>
                    @endif


                    @csrf
                    <!-- Title -->
                    <div>
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Item Name <span class="text-red-500">*</span>
                        </label>
                        <input
                                type="text"
                                id="title"
                                name="title"
                                required
                                value="{{ old('title') }}" 
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                placeholder="e.g., iPhone 15, Student ID Card, Keys"
                        >
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Category -->
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Category <span class="text-red-500">*</span>
                        </label>
                        <select
                                id="category"
                                name="category"
                                required
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                        >
                            <option value="">Select a category</option>
                            @if (old('category'))
                                <option value="{{ old('category') }}" selected>{{ old('category') }}</option>
                            @endif
                            <option value="electronics">Electronics (Phone, Laptop, Tablet)</option>
                            <option value="books">Books & Study Materials</option>
                            <option value="keys">Keys</option>
                            <option value="cards">ID Cards & Documents</option>
                            <option value="clothing">Clothing & Accessories</option>
                            <option value="bags">Bags & Backpacks</option>
                            <option value="jewelry">Jewelry & Watches</option>
                            <option value="sports">Sports Equipment</option>
                            <option value="other">Other</option>
                        </select>
                        @error('category')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Description
                        </label>
                        <textarea
                                id="description"
                                name="description"
                                rows="4"
                                value="{{ old('description') }}" 
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-none"
                                placeholder="Describe the item. Include color, condition, any identifying features or contents."
                        ></textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Where Found <span class="text-red-500">*</span>
                        </label>
                        <input
                                type="text"
                                id="location"
                                name="location"
                                required
                                value="{{ old('location') }}" 
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                placeholder="e.g., Library 3rd floor, Cafeteria table 5, Bus stop"
                        >
                        @error('location')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="item_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Date Found <span class="text-red-500">*</span>
                        </label>
                        <input
                                type="date"
                                id="item_date"
                                name="item_date"
                                required
                                value="{{ old('item_date') }}"
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                        >
                        @error('item_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Image Upload - REQUIRED for found items -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1.5">
                            Photo of Item <span class="text-red-500">*</span>
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 text-center hover:border-primary-400 transition-colors cursor-pointer" id="dropZone">
                            <input
                                    type="file"
                                    id="image"
                                    name="image"
                                    accept="image/jpeg,image/png,image/gif"
                                    required
                                    class="hidden"
                            >
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-sm text-gray-600 mb-1">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-400">JPG, PNG or GIF (max 2MB)</p>
                            <p class="text-xs text-red-500 mt-2">Photo is required for found items</p>
                        </div>
                        <div id="imagePreview" class="hidden mt-3">
                            <div class="relative inline-block">
                                <img id="previewImg" class="h-24 w-24 object-cover rounded-lg border border-gray-200" src="" alt="Preview">
                                <button type="button" id="removeImage" class="absolute -top-2 -right-2 w-6 h-6 bg-red-500 text-white rounded-full flex items-center justify-center hover:bg-red-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <p id="imageError" class="mt-1.5 text-xs text-red-500 hidden">Please upload a photo of the found item</p>
                        @error('image')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Where to Return -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Where should the owner collect the item?</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="return_location" value="admin_office" checked class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                                <span class="text-sm text-gray-600">Admin Office (Recommended)</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="radio" name="return_location" value="direct" class="w-4 h-4 text-primary-600 border-gray-300 focus:ring-primary-500">
                                <span class="text-sm text-gray-600">I can meet directly with the owner</span>
                            </label>
                        </div>
                    </div>

                    <!-- Contact Preference -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Contact Preference</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="share_phone" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-sm text-gray-600">Share my phone number with verified owners</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="share_telegram" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-sm text-gray-600">Share my Telegram username</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <button
                                type="submit"
                                id="submitBtn"
                                class="flex-1 py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Submit Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>
</div>

<!-- Success Modal -->
<div id="successModal" class="fixed inset-0 bg-black/50 z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-xl max-w-sm w-full p-6 text-center">
        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
        </div>
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Thank You!</h3>
        <p class="text-sm text-gray-500 mb-6">Your found item has been reported and is pending admin verification. You'll be notified if someone claims it.</p>
        <a href="student-dashboard.html" class="block w-full py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
            Back to Dashboard
        </a>
    </div>
</div>

<script src="{{asset('js/index.js')}}"></script>
<script src="{{asset('js/post.js')}}"></script>
</body>
</html>
