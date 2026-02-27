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