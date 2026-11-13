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

    let loadId = 0;
    let photoButtons = [];
    let currentIndex = 0;

    // Charge seulement la photo demandée
    function showPhoto(button) {
        const currentLoad = ++loadId;
        const image = new Image();

        media.replaceChildren();
        status.textContent = 'Chargement de la photo…';

        title.textContent = button.dataset.title;
        reference.textContent = button.dataset.reference;
        category.textContent = button.dataset.category;
        image.alt = button.dataset.alt || button.dataset.title;

        // Un ancien chargement ne doit pas s'afficher après un changement de photo
        function finishLoading(loaded) {
            if (currentLoad !== loadId || !lightbox.open) return;

            if (loaded) media.replaceChildren(image);
            status.textContent = loaded ? '' : 'Impossible de charger cette photo';
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

        lightbox.showModal();
        document.body.classList.add('is-lightbox-open');
        showPhoto(button);
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

    // Invalide le chargement à la fermeture
    lightbox.addEventListener('close', () => {
        loadId++;
        photoButtons = [];
        media.replaceChildren();
        status.textContent = '';
        document.body.classList.remove('is-lightbox-open');
    });
});
