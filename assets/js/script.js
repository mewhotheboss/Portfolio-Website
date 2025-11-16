document.addEventListener('DOMContentLoaded', () => {

    const popupMenu = document.querySelector('.popup-menu');
    const toggleButtons = document.querySelectorAll('.hamburger-btn, .btn-close');
    const menuLinks = document.querySelectorAll('.popup-nav a');
    toggleButtons.forEach(button => {
        button.addEventListener('click', () => popupMenu.classList.toggle('active'));
    });
    menuLinks.forEach(link => {
        link.addEventListener('click', (event) => {
            event.preventDefault();
            const href = link.getAttribute('href');
            const targetId = href.substring(1); // Get "hero"
            const targetSection = document.getElementById(targetId);
            if (targetSection) {
                targetSection.scrollIntoView({
                    behavior: 'smooth'
                });
            }
            popupMenu.classList.remove('active');
        });
    });

});
