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

    // Ouvre ou ferme le panneau mobile
    function setMenu(open) {
        if (open) positionMenu();

        navigation.inert = !open && mobileScreen.matches;
        document.body.classList.toggle('is-menu-open', open);
        menuToggle.setAttribute('aria-expanded', String(open));
    }

    menuToggle.addEventListener('click', () => {
        setMenu(!isMenuOpen());
    });

    navigation.addEventListener('click', (event) => {
        if (event.target.closest('a') && isMenuOpen()) setMenu(false);
    });

    header.addEventListener('focusout', (event) => {
        if (isMenuOpen() && !header.contains(event.relatedTarget)) setMenu(false);
    });

    header.addEventListener('keydown', (event) => {
        if (event.key !== 'Escape' || !isMenuOpen()) return;
        menuToggle.focus();
        setMenu(false);
    });

    // Le panneau suit l'en-tête et se ferme quand on passe la limite mobile
    mobileScreen.addEventListener('change', () => setMenu(false));
    window.addEventListener('resize', () => {
        if (isMenuOpen()) positionMenu();
    });
});
