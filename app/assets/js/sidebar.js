const sidebar = document.getElementById('sidebar');
const sidebarToggle = document.getElementById('sidebarToggle');
const backdrop = document.getElementById('backdrop');

function openSidebar() {
    sidebar.classList.add('open');
    backdrop.classList.add('show');
}

function closeSidebar() {
    sidebar.classList.remove('open');
    backdrop.classList.remove('show');
}

sidebarToggle.addEventListener('click', function() {
    if (sidebar.classList.contains('open')) {
        closeSidebar();
    } else {
        openSidebar();
    }
});

backdrop.addEventListener('click', closeSidebar);

// Opcional: cerrar sidebar con ESC
window.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeSidebar();
}); 