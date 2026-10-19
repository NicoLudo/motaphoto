// Ouverture et fermeture du menu mobile
document.documentElement.classList.add('js');

const menuToggle = document.querySelector('.menu-toggle');
const navigation = document.querySelector('.primary-navigation');
const header = document.querySelector('.site-header');

if (menuToggle && navigation && header) {
    const mobileScreen = window.matchMedia('(max-width: 767px)');
    navigation.inert = mobileScreen.matches;

    function closeMenu(restoreFocus = true) {
        document.body.classList.remove('is-menu-open');
        menuToggle.setAttribute('aria-expanded', 'false');
        menuToggle.setAttribute('aria-label', 'Ouvrir le menu');
        navigation.inert = mobileScreen.matches;

        if (restoreFocus && mobileScreen.matches) {
            menuToggle.focus();
        }
    }

    function openMenu() {
        navigation.style.setProperty('--menu-top', `${Math.max(0, header.getBoundingClientRect().bottom)}px`);
        navigation.inert = false;
        document.body.classList.add('is-menu-open');
        menuToggle.setAttribute('aria-expanded', 'true');
        menuToggle.setAttribute('aria-label', 'Fermer le menu');
        navigation.querySelector('a')?.focus();
    }

    menuToggle.addEventListener('click', () => {
        if (menuToggle.getAttribute('aria-expanded') === 'true') {
            closeMenu();
        } else {
            openMenu();
        }
    });

    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a')) {
            closeMenu(false);
        }
    });

    document.addEventListener('keydown', (event) => {
        if (menuToggle.getAttribute('aria-expanded') !== 'true') return;

        if (event.key === 'Escape') {
            closeMenu();
        }

        if (event.key === 'Tab') {
            const links = [...header.querySelectorAll('.menu-toggle, .primary-navigation a[href]')];
            const first = links[0];
            const last = links[links.length - 1];

            if (event.shiftKey && document.activeElement === first) {
                event.preventDefault();
                last.focus();
            } else if (!event.shiftKey && document.activeElement === last) {
                event.preventDefault();
                first.focus();
            }
        }
    });

    mobileScreen.addEventListener('change', () => closeMenu(false));
    window.addEventListener('resize', () => {
        if (menuToggle.getAttribute('aria-expanded') === 'true') {
            navigation.style.setProperty('--menu-top', `${Math.max(0, header.getBoundingClientRect().bottom)}px`);
        }
    });
}

// Fenêtre de contact
const contactModal = document.getElementById('contact-modal');
let contactTrigger = null;

if (contactModal) {
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[href="#contact"]');

        if (!link) return;

        event.preventDefault();
        contactTrigger = link;

        contactModal.showModal();
        document.body.classList.add('is-contact-open');
        contactModal.querySelector('input[name="your-name"]')?.focus();
    });

    contactModal.querySelector('.contact-close').addEventListener('click', () => {
        contactModal.close();
    });

    contactModal.addEventListener('click', (event) => {
        if (event.target !== contactModal) return;

        const box = contactModal.getBoundingClientRect();

        if (
            event.clientX < box.left ||
            event.clientX > box.right ||
            event.clientY < box.top ||
            event.clientY > box.bottom
        ) {
            contactModal.close();
        }
    });

    contactModal.addEventListener('close', () => {
        document.body.classList.remove('is-contact-open');

        if (
            contactTrigger?.closest('.primary-navigation') &&
            window.matchMedia('(max-width: 767px)').matches
        ) {
            menuToggle?.focus();
        } else {
            contactTrigger?.focus();
        }
    });
}