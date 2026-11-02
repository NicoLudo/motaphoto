document.addEventListener('DOMContentLoaded', () => {
    const grid = document.getElementById('photo-grid');
    const button = document.getElementById('load-more');
    const status = document.getElementById('gallery-status');

    if (!grid || !button || !status) return;

    button.addEventListener('click', async () => {
        if (button.getAttribute('aria-disabled') === 'true') return;

        button.setAttribute('aria-disabled', 'true');
        grid.setAttribute('aria-busy', 'true');
        status.textContent = 'Chargement en cours…';

        const nextPage = Number(button.dataset.page) + 1;

        try {
            // Demande uniquement le lot suivant
            const response = await fetch(button.dataset.url, {
                method: 'POST',
                body: new URLSearchParams({
                    action: 'motaphoto_load_photos',
                    nonce: button.dataset.nonce,
                    page: String(nextPage),
                }),
            });

            if (!response.ok) throw new Error('Réponse invalide');

            const result = await response.json();
            if (!result.success) throw new Error('Chargement refusé');

            const firstNewIndex = grid.children.length;

            grid.insertAdjacentHTML('beforeend', result.data.html);
            button.dataset.page = nextPage;
            status.textContent = `${grid.children.length} photos affichées`;

            // Garde le focus dans la galerie quand le bouton disparaît
            if (!result.data.has_more) {
                const photo = grid.children[firstNewIndex] || grid.lastElementChild;
                photo?.querySelector('a')?.focus();
            }

            button.hidden = !result.data.has_more;
        } catch {
            status.textContent = 'Impossible de charger les photos. Réessaie ou actualise la page.';
        } finally {
            button.removeAttribute('aria-disabled');
            grid.setAttribute('aria-busy', 'false');
        }
    });
});