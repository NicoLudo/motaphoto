document.addEventListener('DOMContentLoaded', () => {
    const contactModal = document.getElementById('contact-modal');
    if (!contactModal) return;

    const mobileScreen = window.matchMedia('(max-width: 767px)');
    let contactTrigger = null;

    // Tous les liens Contact ouvrent le même formulaire
    document.addEventListener('click', (event) => {
        const link = event.target.closest?.('a[href="#contact"]');
        if (!link) return;

        event.preventDefault();
        contactTrigger = link;

        // Le lien transmet sa référence ou remet le champ à vide
        const photoReference = link.dataset.photoReference || '';
        for (const field of contactModal.querySelectorAll('[name="photo-reference"]')) {
            field.value = photoReference;
        }

        contactModal.showModal();
        document.body.classList.add('is-contact-open');
    });

    contactModal.querySelector('.contact-close')?.addEventListener('click', () => contactModal.close());

    // Sur mobile, le lien du menu fermé n'est plus accessible au clavier
    contactModal.addEventListener('close', () => {
        document.body.classList.remove('is-contact-open');

        if (contactTrigger?.closest('.primary-navigation') && mobileScreen.matches) {
            document.querySelector('.menu-toggle')?.focus();
        }
    });
});
