<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<footer class="site-footer">
    <div class="footer-inner">
        <?php if ( has_nav_menu( 'footer-menu' ) ) : ?>
            <nav aria-label="Menu du pied de page">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'footer-menu',
                        'container'      => false,
                        'menu_class'     => 'footer-menu',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav>
        <?php endif; ?>

        <p>Tous droits réservés</p>
    </div>
</footer>

<?php wp_footer(); ?>

</body>
</html>