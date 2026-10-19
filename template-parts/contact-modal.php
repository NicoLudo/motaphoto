<?php
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

    <?php echo do_shortcode( '[contact-form-7 id="9a975d0" title="Contact"]' ); ?>
</dialog>