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
            const targetId = href.substring(1);
            const targetSection = document.getElementById(targetId);

            if (targetSection) {
                targetSection.scrollIntoView({ behavior: 'smooth' });
            }

            popupMenu.classList.remove('active');
        });
    });

    const scrollTopBtn = document.querySelector('.scroll-top');

    if (scrollTopBtn) {

        window.addEventListener('scroll', () => {

            if (window.scrollY > 300) {
                scrollTopBtn.classList.add('active');
            } else {
                scrollTopBtn.classList.remove('active');
            }
        });

        scrollTopBtn.addEventListener('click', (event) => {
            event.preventDefault();
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        });
    }
});
