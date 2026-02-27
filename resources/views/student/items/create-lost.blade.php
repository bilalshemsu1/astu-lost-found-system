<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Lost Item - ASTU Lost & Found</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            50: '#f0fdfa',
                            100: '#ccfbf1',
                            200: '#99f6e4',
                            300: '#5eead4',
                            400: '#2dd4bf',
                            500: '#14b8a6',
                            600: '#0d9488',
                            700: '#0f766e',
                            800: '#115e59',
                            900: '#134e4a',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        body { font-family: 'Inter', sans-serif; }
        .sidebar-link.active {
            background-color: #f0fdfa;
            color: #0d9488;
            border-left: 2px solid #0d9488;
        }
        @media (max-width: 1023px) {
            .sidebar-link.active {
                border-left: none;
                border-left: 2px solid #0d9488;
            }
        }
        .sidebar-overlay {
            background-color: rgba(0, 0, 0, 0.5);
            transition: opacity 0.3s ease;
        }
        .sidebar-panel {
            transition: transform 0.3s ease;
        }
        .sidebar-panel.closed {
            transform: translateX(-100%);
        }
        @media (min-width: 1024px) {
            .sidebar-panel.closed {
                transform: translateX(0);
            }
        }
    </style>
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
            <h1 class="text-base sm:text-lg font-semibold text-gray-900">Report Lost Item</h1>
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
        <div class="max-w-2xl mx-auto">
            <!-- Info Card -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-6">
                <div class="flex gap-3">
                    <div class="w-8 h-8 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm font-medium text-amber-900">Your item will be reviewed</p>
                        <p class="text-xs sm:text-sm text-amber-700 mt-1">After submission, an admin will verify your report. Once verified, our system will automatically search for matches.</p>
                    </div>
                </div>
            </div>

            <!-- Form Card -->
            <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                <div class="p-4 sm:p-6 border-b border-gray-200">
                    <h2 class="font-semibold text-gray-900">Lost Item Details</h2>
                    <p class="text-sm text-gray-500 mt-1">Provide as much detail as possible to help find your item</p>
                </div>

                <form id="lostItemForm" class="p-4 sm:p-6 space-y-5">
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
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                placeholder="e.g., iPhone 15 Pro Max, Black Backpack"
                        >
                        <p class="mt-1.5 text-xs text-gray-400">A clear, specific name for your item</p>
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
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors resize-none"
                                placeholder="Describe your item in detail. Include color, size, brand, distinguishing features, contents, etc."
                        ></textarea>
                        <p class="mt-1.5 text-xs text-gray-400">More details increase match accuracy</p>
                    </div>

                    <!-- Location -->
                    <div>
                        <label for="location" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Last Seen Location <span class="text-red-500">*</span>
                        </label>
                        <input
                                type="text"
                                id="location"
                                name="location"
                                required
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                                placeholder="e.g., Library 3rd floor, Cafeteria, Engineering Block B"
                        >
                    </div>

                    <!-- Date -->
                    <div>
                        <label for="lost_date" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Date Lost <span class="text-red-500">*</span>
                        </label>
                        <input
                                type="date"
                                id="lost_date"
                                name="lost_date"
                                required
                                class="w-full px-3.5 py-2.5 border border-gray-300 rounded-lg text-gray-900 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-colors"
                        >
                        <p class="mt-1.5 text-xs text-gray-400">When did you last have the item?</p>
                    </div>

                    <!-- Image Upload -->
                    <div>
                        <label for="image" class="block text-sm font-medium text-gray-700 mb-1.5">
                            Image (Optional)
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-4 sm:p-6 text-center hover:border-primary-400 transition-colors cursor-pointer" id="dropZone">
                            <input
                                    type="file"
                                    id="image"
                                    name="image"
                                    accept="image/jpeg,image/png,image/gif"
                                    class="hidden"
                            >
                            <svg class="w-10 h-10 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm text-gray-600 mb-1">Click to upload or drag and drop</p>
                            <p class="text-xs text-gray-400">JPG, PNG or GIF (max 2MB)</p>
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
                    </div>

                    <!-- Contact Preference -->
                    <div class="bg-gray-50 rounded-lg p-4">
                        <p class="text-sm font-medium text-gray-700 mb-3">Contact Preference</p>
                        <div class="space-y-2">
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="share_phone" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-sm text-gray-600">Share my phone number with finders</span>
                            </label>
                            <label class="flex items-center gap-3 cursor-pointer">
                                <input type="checkbox" name="share_telegram" class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500">
                                <span class="text-sm text-gray-600">Share my Telegram username</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Buttons -->
                    <div class="flex flex-col sm:flex-row gap-3 pt-4">
                        <a href="student-dashboard.html" class="flex-1 py-2.5 px-4 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition-colors text-center">
                            Cancel
                        </a>
                        <button
                                type="submit"
                                id="submitBtn"
                                class="flex-1 py-2.5 px-4 bg-primary-600 hover:bg-primary-700 text-white font-medium rounded-lg transition-colors flex items-center justify-center gap-2"
                        >
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
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
        <h3 class="text-lg font-semibold text-gray-900 mb-2">Report Submitted!</h3>
        <p class="text-sm text-gray-500 mb-6">Your lost item has been reported and is pending admin verification. We'll notify you when it's approved.</p>
        <a href="{{ route('student.dashboard') }}" class="block w-full py-2.5 bg-primary-600 text-white font-medium rounded-lg hover:bg-primary-700 transition-colors">
            Back to Dashboard
        </a>
    </div>
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

    // Close sidebar when clicking a link on mobile
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

    // Image upload handling
    const dropZone = document.getElementById('dropZone');
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');
    const removeImage = document.getElementById('removeImage');

    dropZone.addEventListener('click', () => imageInput.click());

    dropZone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropZone.classList.add('border-primary-400', 'bg-primary-50');
    });

    dropZone.addEventListener('dragleave', () => {
        dropZone.classList.remove('border-primary-400', 'bg-primary-50');
    });

    dropZone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropZone.classList.remove('border-primary-400', 'bg-primary-50');
        if (e.dataTransfer.files.length) {
            imageInput.files = e.dataTransfer.files;
            handleImagePreview(e.dataTransfer.files[0]);
        }
    });

    imageInput.addEventListener('change', (e) => {
        if (e.target.files.length) {
            handleImagePreview(e.target.files[0]);
        }
    });

    function handleImagePreview(file) {
        if (file.type.startsWith('image/')) {
            const reader = new FileReader();
            reader.onload = (e) => {
                previewImg.src = e.target.result;
                imagePreview.classList.remove('hidden');
                dropZone.classList.add('hidden');
            };
            reader.readAsDataURL(file);
        }
    }

    removeImage.addEventListener('click', () => {
        imageInput.value = '';
        imagePreview.classList.add('hidden');
        dropZone.classList.remove('hidden');
    });

    // Form submission
    const form = document.getElementById('lostItemForm');
    const successModal = document.getElementById('successModal');
    const submitBtn = document.getElementById('submitBtn');

    form.addEventListener('submit', (e) => {
        e.preventDefault();

        submitBtn.disabled = true;
        submitBtn.innerHTML = `
                <svg class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Submitting...
            `;

        // Simulate API call
        setTimeout(() => {
            successModal.classList.remove('hidden');
            successModal.classList.add('flex');
        }, 1500);
    });
</script>
</body>
</html>
