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
        const position = `${currentIndex + 1} sur ${photoButtons.length}`;

        media.replaceChildren();
        media.setAttribute('aria-busy', 'true');
        status.classList.remove('visually-hidden');
        status.textContent = 'Chargement de la photo…';

        title.textContent = button.dataset.title;
        reference.textContent = button.dataset.reference;
        category.textContent = button.dataset.category;
        image.alt = button.dataset.alt || button.dataset.title;

        // Un ancien chargement ne doit pas s'afficher après un changement de photo
        function finishLoading(loaded) {
            if (currentLoad !== loadId || !lightbox.open) return;

            if (loaded) media.replaceChildren(image);
            media.setAttribute('aria-busy', 'false');
            status.classList.toggle('visually-hidden', loaded);
            status.textContent = loaded ? `Photo ${position}` : 'Impossible de charger cette photo';
        }

        image.onload = () => finishLoading(true);
        image.onerror = () => finishLoading(false);
        image.src = button.dataset.image;
    }

    // Parcourt la galerie en revenant au début après la dernière photo
    function changePhoto(direction) {
        if (!lightbox.open || photoButtons.length < 2) return;

        currentIndex += direction;
        if (currentIndex < 0) currentIndex = photoButtons.length - 1;
        if (currentIndex >= photoButtons.length) currentIndex = 0;
        showPhoto(photoButtons[currentIndex]);
    }

    // La liste garde l'ordre des photos de la galerie ouverte, y compris les cartes Ajax
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

    // Les flèches simples naviguent sans bloquer les raccourcis du navigateur
    lightbox.addEventListener('keydown', (event) => {
        if (photoButtons.length < 2) return;
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

    // Invalide le chargement et rend le focus au bouton qui a ouvert la fenêtre
    lightbox.addEventListener('close', () => {
        loadId++;
        photoButtons = [];
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
