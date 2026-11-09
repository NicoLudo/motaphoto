<?php
// La fenêtre utilise le formulaire créé dans Contact Form 7
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<dialog id="contact-modal" class="contact-modal" aria-label="Contacter Nathalie Mota">
    <button type="button" class="contact-close" aria-label="Fermer la fenêtre">
        x
    </button>

    <div class="contact-banner" aria-hidden="true">
        <span>CONTACTCONTACTCONTACTCONTACT</span>
        <span>CONTACTCONTACTCONTACTCONTACT</span>
    </div>

    <?php // Le shortcode récupère les champs et les réglages du formulaire ?>
    <?php echo do_shortcode( '[contact-form-7 id="9a975d0" title="Contact"]' ); ?>
</dialog>
