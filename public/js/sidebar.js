
function toggleSidebar() {
    document.body.classList.toggle('sidebar-open');

    const hamburgerIcon = document.querySelector('.hamburger-btn i');
    if (document.body.classList.contains('sidebar-open')) {
        hamburgerIcon.classList.replace('fa-bars', 'fa-times');
    } else {
        hamburgerIcon.classList.replace('fa-times', 'fa-bars');
    }
}
window.addEventListener('resize', function () {
    if (window.innerWidth < 769) {
        const hamburgerIcon = document.querySelector('.hamburger-btn i');
        if (!document.body.classList.contains('sidebar-open')) {
            hamburgerIcon.classList.replace('fa-times', 'fa-bars');
        }
    }
});