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