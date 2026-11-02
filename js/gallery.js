document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('gallery-filters');
    const grid = document.getElementById('photo-grid');
    const button = document.getElementById('load-more');
    const status = document.getElementById('gallery-status');

    if (!form || !grid || !button || !status) return;

    let controller = null;
    let appliedFilters = Object.fromEntries(new FormData(form));

    async function loadPhotos(reset = false) {
        if (!reset && controller) return;

        // Un nouveau filtre remplace la demande précédente
        controller?.abort();
        const request = new AbortController();
        controller = request;

        const values = Object.fromEntries(new FormData(form));
        const page = reset ? 1 : Number(button.dataset.page) + 1;

        button.setAttribute('aria-disabled', 'true');
        grid.setAttribute('aria-busy', 'true');
        status.textContent = 'Chargement en cours…';

        try {
            const response = await fetch(button.dataset.url, {
                method: 'POST',
                signal: request.signal,
                body: new URLSearchParams({
                    ...values,
                    action: 'motaphoto_load_photos',
                    nonce: button.dataset.nonce,
                    page: String(page),
                }),
            });

            if (!response.ok) throw new Error('Réponse invalide');

            const result = await response.json();
            if (!result.success) throw new Error('Chargement refusé');
            if (controller !== request) return;

            const firstNewIndex = grid.children.length;

            if (reset) {
                grid.innerHTML = result.data.html;
            } else {
                grid.insertAdjacentHTML('beforeend', result.data.html);
            }

            button.dataset.page = String(page);
            appliedFilters = values;
            status.textContent = grid.children.length
                ? `Photos affichées : ${grid.children.length}`
                : 'Aucune photo ne correspond à ces critères';

            // Déplace le focus seulement si le bouton est encore utilisé
            if (!reset && !result.data.has_more && document.activeElement === button) {
                const photo = grid.children[firstNewIndex] || grid.lastElementChild;
                photo?.querySelector('a')?.focus();
            }

            button.hidden = !result.data.has_more;
        } catch {
            if (request.signal.aborted || controller !== request) return;

            // En cas d’échec, garde les filtres des photos déjà affichées
            for (const [name, value] of Object.entries(appliedFilters)) {
                form.elements.namedItem(name).value = value;
            }

            status.textContent = 'Chargement impossible. Réessaie ou actualise la page.';
        } finally {
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