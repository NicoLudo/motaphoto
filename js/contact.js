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

        // Le dialogue natif gère Échap et le clavier peut commencer sur le nom
        contactModal.showModal();
        document.body.classList.add('is-contact-open');
        contactModal.querySelector('input[name="your-name"]')?.focus();
    });

    contactModal.querySelector('.contact-close')?.addEventListener('click', () => contactModal.close());

    // Vérifie les bords pour distinguer le fond d'un clic dans le cadre
    contactModal.addEventListener('click', (event) => {
        if (event.target !== contactModal) return;
        const box = contactModal.getBoundingClientRect();

        const outside = event.clientX < box.left || event.clientX > box.right ||
            event.clientY < box.top || event.clientY > box.bottom;

        if (outside) contactModal.close();
    });

    // Sur mobile, le lien du menu fermé n'est plus accessible au clavier
    contactModal.addEventListener('close', () => {
        document.body.classList.remove('is-contact-open');

        if (contactTrigger?.closest('.primary-navigation') && mobileScreen.matches) {
            document.querySelector('.menu-toggle')?.focus();
        } else {
            contactTrigger?.focus();
        }
    });
});
