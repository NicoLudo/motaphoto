<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<footer class="site-footer">
    <div class="footer-inner">
        <?php // Liens du pied de page gérés dans WordPress ?>
        <?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
            <nav aria-label="Menu du pied de page">
                <?php
                wp_nav_menu(
                    [
                        'theme_location' => 'footer-menu',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => false,
                    ]
                );
                ?>
            </nav>
        <?php endif; ?>

        <p>Tous droits réservés</p>
    </div>
</footer>

<?php
// Les deux fenêtres sont disponibles depuis les pages du site
get_template_part( 'template-parts/contact-modal' );
get_template_part( 'template-parts/lightbox' );

// Charge les scripts prévus en bas de page
wp_footer();
?>

</body>
</html>
