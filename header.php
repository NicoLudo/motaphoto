<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php
    // WordPress et les extensions ajoutent ici leurs ressources
    wp_head();
    ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php // Logo et menu réglables dans WordPress ?>
<header class="site-header">
    <div class="header-inner">
        <div class="site-logo">
            <?php if ( has_custom_logo() ) : ?>
                <?php the_custom_logo(); ?>
            <?php else : ?>
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
            <?php endif; ?>
        </div>

        <?php if ( has_nav_menu( 'main-menu' ) ) : ?>
            <button class="menu-toggle" type="button" aria-expanded="false" aria-label="Menu principal">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav class="primary-navigation" aria-label="Menu principal">
                <?php
                wp_nav_menu(
                    [
                        'theme_location' => 'main-menu',
                        'container'      => false,
                        'menu_class'     => 'main-menu',
                        'fallback_cb'    => false,
                    ]
                );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</header>
