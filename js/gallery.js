document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('gallery-filters');
    const grid = document.getElementById('photo-grid');
    const button = document.getElementById('load-more');
    const status = document.getElementById('gallery-status');

    if (!form || !grid || !button || !status) return;

    let controller = null;
    // Garde les choix qui correspondent aux photos déjà affichées
    let appliedFilters = new FormData(form);

    async function loadPhotos(reset = false) {
        if (!reset && controller) return;

        // Un nouveau filtre remplace la demande précédente
        controller?.abort();
        const request = new AbortController();
        controller = request;

        // Un changement de filtre repart de la première page
        const filters = new FormData(form);
        const page = reset ? 1 : Number(button.dataset.page) + 1;
        const body = new URLSearchParams(filters);
        body.set('action', 'motaphoto_load_photos');
        body.set('nonce', button.dataset.nonce);
        body.set('page', String(page));

        button.setAttribute('aria-disabled', 'true');
        grid.setAttribute('aria-busy', 'true');
        status.classList.remove('visually-hidden');
        status.textContent = 'Chargement en cours…';

        try {
            const response = await fetch(button.dataset.url, {
                method: 'POST',
                signal: request.signal,
                body,
            });

            if (!response.ok) throw new Error('Réponse invalide');

            const result = await response.json();
            const data = result?.data;
            if (!result?.success || typeof data?.html !== 'string') throw new Error('Chargement refusé');
            // Seule la demande en cours peut mettre à jour la galerie
            if (controller !== request) return;

            const firstNewIndex = grid.children.length;

            // Les filtres remplacent les cartes, Charger plus les ajoute à la suite
            if (reset) {
                grid.innerHTML = data.html;
            } else {
                grid.insertAdjacentHTML('beforeend', data.html);
            }

            button.dataset.page = String(page);
            appliedFilters = filters;
            const count = grid.children.length;

            // Le compteur de réussite est réservé aux lecteurs d’écran
            status.classList.toggle('visually-hidden', count > 0);
            status.textContent = count
                ? `Photos affichées : ${count}`
                : 'Aucune photo ne correspond à ces critères';

            // Si le bouton va disparaître, le clavier continue sur les photos
            if (!reset && !data.has_more && document.activeElement === button) {
                const photo = grid.children[firstNewIndex] || grid.lastElementChild;
                photo?.querySelector('a')?.focus();
            }

            button.hidden = !data.has_more;
        } catch {
            if (request.signal.aborted || controller !== request) return;

            // En cas d’échec, garde les filtres des photos déjà affichées
            for (const [name, value] of appliedFilters) {
                const field = form.elements.namedItem(name);
                if (field) field.value = value;
            }

            status.classList.remove('visually-hidden');
            status.textContent = 'Chargement impossible. Réessaie ou actualise la page.';
        } finally {
            // Une ancienne demande ne doit pas arrêter l'indicateur de la nouvelle
            if (controller === request) {
                controller = null;
                button.removeAttribute('aria-disabled');
                grid.setAttribute('aria-busy', 'false');
            }
        }
    }

    form.addEventListener('submit', (event) => event.preventDefault());
    form.addEventListener('change', () => loadPhotos(true));
    button.addEventListener('click', () => loadPhotos());
});
