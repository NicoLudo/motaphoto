document.addEventListener('DOMContentLoaded', () => {
    const lightbox = document.getElementById('lightbox');
    if (!lightbox) return;

    const media = lightbox.querySelector('.lightbox-media');
    const title = lightbox.querySelector('.lightbox-title');
    const reference = lightbox.querySelector('.lightbox-reference');
    const category = lightbox.querySelector('.lightbox-category');
    const status = lightbox.querySelector('.lightbox-status');
    const previous = lightbox.querySelector('.lightbox-prev');
    const next = lightbox.querySelector('.lightbox-next');

    let trigger = null;
    let loadId = 0;
    let photoButtons = [];
    let currentIndex = 0;

    // Charge seulement la photo demandée
    function showPhoto(button) {
        const currentLoad = ++loadId;
        const image = new Image();

        media.replaceChildren();
        media.setAttribute('aria-busy', 'true');
        status.textContent = `Chargement de la photo ${currentIndex + 1} sur ${photoButtons.length}…`;

        title.textContent = button.dataset.title;
        reference.textContent = button.dataset.reference;
        category.textContent = button.dataset.category;
        image.alt = button.dataset.alt || button.dataset.title;

        image.onload = () => {
            if (currentLoad !== loadId || !lightbox.open) return;

            media.replaceChildren(image);
            media.setAttribute('aria-busy', 'false');
            status.textContent = `Photo ${currentIndex + 1} sur ${photoButtons.length}`;
        };

        image.onerror = () => {
            if (currentLoad !== loadId || !lightbox.open) return;

            media.setAttribute('aria-busy', 'false');
            status.textContent = 'Impossible de charger cette photo';
        };

        image.src = button.dataset.image;
    }

    // Parcourt la galerie en revenant au début après la dernière photo
    function changePhoto(direction) {
        if (!lightbox.open || photoButtons.length < 2) return;

        currentIndex = (currentIndex + direction + photoButtons.length) % photoButtons.length;
        showPhoto(photoButtons[currentIndex]);
    }

    // Garde les photos affichées dans la galerie au moment de l’ouverture
    document.addEventListener('click', (event) => {
        const button = event.target.closest?.('.photo-fullscreen');
        if (!button || lightbox.open) return;

        const gallery = button.closest('.photo-grid');
        photoButtons = gallery
            ? Array.from(gallery.querySelectorAll('.photo-fullscreen'))
            : [button];

        currentIndex = photoButtons.indexOf(button);

        const hideNavigation = photoButtons.length < 2;
        previous.hidden = hideNavigation;
        next.hidden = hideNavigation;

        trigger = button;
        showPhoto(button);
        lightbox.showModal();
        document.body.classList.add('is-lightbox-open');
    });

    previous.addEventListener('click', () => changePhoto(-1));
    next.addEventListener('click', () => changePhoto(1));

    lightbox.addEventListener('keydown', (event) => {
        if (event.altKey || event.ctrlKey || event.metaKey || event.shiftKey || event.repeat) return;

        if (event.key === 'ArrowLeft' || event.key === 'ArrowRight') {
            event.preventDefault();
            changePhoto(event.key === 'ArrowLeft' ? -1 : 1);
        }
    });

    lightbox.querySelector('.lightbox-close').addEventListener('click', () => {
        lightbox.close();
    });

    lightbox.addEventListener('click', (event) => {
        if (event.target === lightbox) lightbox.close();
    });

    // Nettoie la fenêtre et rend le focus à la page
    lightbox.addEventListener('close', () => {
        loadId++;
        photoButtons = [];
        currentIndex = 0;
        media.replaceChildren();
        media.setAttribute('aria-busy', 'false');
        status.textContent = '';
        document.body.classList.remove('is-lightbox-open');

        const target = trigger?.isConnected
            ? trigger
            : document.querySelector('#gallery-filters select, .photo-card-link');

        target?.focus({ preventScroll: true });
        trigger = null;
    });
});