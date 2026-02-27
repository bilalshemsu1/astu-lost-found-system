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
