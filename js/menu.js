document.addEventListener('DOMContentLoaded', () => {
    // Sans JavaScript, les liens restent visibles sur mobile
    document.documentElement.classList.add('js');

    const menuToggle = document.querySelector('.menu-toggle');
    const navigation = document.querySelector('.primary-navigation');
    const header = document.querySelector('.site-header');

    if (!menuToggle || !navigation || !header) return;

    const mobileScreen = window.matchMedia('(max-width: 767px)');
    // Les liens du menu fermé ne doivent pas recevoir le focus
    navigation.inert = mobileScreen.matches;

    function isMenuOpen() {
        return menuToggle.getAttribute('aria-expanded') === 'true';
    }

    function positionMenu() {
        const headerBottom = header.getBoundingClientRect().bottom;
        navigation.style.setProperty('--menu-top', `${Math.max(0, headerBottom)}px`);
    }

    // Met à jour l'affichage, l'accès au clavier et le libellé du bouton
    function setMenu(open, restoreFocus = true) {
        if (open) positionMenu();

        navigation.inert = !open && mobileScreen.matches;
        document.body.classList.toggle('is-menu-open', open);
        menuToggle.setAttribute('aria-expanded', String(open));
        menuToggle.setAttribute('aria-label', open ? 'Fermer le menu' : 'Ouvrir le menu');

        if (open) {
            navigation.querySelector('a')?.focus();
        } else if (restoreFocus && mobileScreen.matches) {
            menuToggle.focus();
        }
    }

    menuToggle.addEventListener('click', () => {
        setMenu(!isMenuOpen());
    });

    // Ferme le menu sans renvoyer le focus au bouton avant de suivre le lien
    navigation.addEventListener('click', (event) => {
        if (event.target.closest?.('a')) setMenu(false, false);
    });

    // Échap ferme le menu et Tab reste entre le bouton et les liens
    document.addEventListener('keydown', (event) => {
        if (!isMenuOpen()) return;
        if (event.key === 'Escape') return setMenu(false);
        if (event.key !== 'Tab') return;

        const links = header.querySelectorAll('.menu-toggle, .primary-navigation a[href]');
        const first = links[0];
        const last = links[links.length - 1];

        if (event.shiftKey && document.activeElement === first) {
            event.preventDefault();
            last.focus();
        } else if (!event.shiftKey && document.activeElement === last) {
            event.preventDefault();
            first.focus();
        }
    });

    // Le panneau suit l'en-tête et se ferme quand on passe la limite mobile
    mobileScreen.addEventListener('change', () => setMenu(false, false));
    window.addEventListener('resize', () => {
        if (isMenuOpen()) positionMenu();
    });
});
