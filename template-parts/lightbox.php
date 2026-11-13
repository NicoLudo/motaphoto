<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Fenêtre commune pour afficher les photos en grand
?>

<dialog id="lightbox" class="lightbox" aria-labelledby="lightbox-title">
    <button class="lightbox-close" type="button" aria-label="Fermer la photo" autofocus>
        <span aria-hidden="true">×</span>
    </button>

    <?php // Le script affiche les commandes quand il y a plusieurs photos ?>
    <button
        class="lightbox-nav lightbox-prev"
        type="button"
        hidden
    >
        <span class="lightbox-arrow" aria-hidden="true">←</span>
        <span>Précédente</span>
    </button>

    <button
        class="lightbox-nav lightbox-next"
        type="button"
        hidden
    >
        <span>Suivante</span>
        <span class="lightbox-arrow" aria-hidden="true">→</span>
    </button>

    <?php // L’image est ajoutée ici au moment de l’ouverture ?>
    <figure class="lightbox-content">
        <div class="lightbox-media"></div>

        <figcaption class="lightbox-caption">
            <h2 id="lightbox-title" class="lightbox-title"></h2>

            <div class="lightbox-details">
                <span class="lightbox-reference"></span>
                <span class="lightbox-category"></span>
            </div>

            <p class="lightbox-status" role="status"></p>
        </figcaption>
    </figure>
</dialog>
