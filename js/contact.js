document.addEventListener('DOMContentLoaded', () => {
    const contactModal = document.getElementById('contact-modal');
    if (!contactModal) return;

    const mobileScreen = window.matchMedia('(max-width: 767px)');
    let contactTrigger = null;

    // Un seul écouteur ouvre le formulaire depuis les liens Contact.
    document.addEventListener('click', (event) => {
        const link = event.target.closest?.('a[href="#contact"]');
        if (!link) return;

        event.preventDefault();
        contactTrigger = link;

        // Une ouverture sans référence efface celle de la photo précédente.
        contactModal.querySelectorAll('[name="photo-reference"]').forEach((field) => {
            field.value = link.dataset.photoReference || '';
        });

        contactModal.showModal();
        document.body.classList.add('is-contact-open');
        contactModal.querySelector('input[name="your-name"]')?.focus();
    });

    contactModal.querySelector('.contact-close')?.addEventListener('click', () => contactModal.close());

    // Seul un clic hors du cadre ferme la fenêtre.
    contactModal.addEventListener('click', (event) => {
        if (event.target !== contactModal) return;
        const box = contactModal.getBoundingClientRect();

        if (
            event.clientX < box.left || event.clientX > box.right ||
            event.clientY < box.top || event.clientY > box.bottom
        ) {
            contactModal.close();
        }
    });

    // Après fermeture, le focus revient sur un élément encore accessible.
    contactModal.addEventListener('close', () => {
        document.body.classList.remove('is-contact-open');

        if (contactTrigger?.closest('.primary-navigation') && mobileScreen.matches) {
            document.querySelector('.menu-toggle')?.focus();
        } else {
            contactTrigger?.focus();
        }
    });
});
