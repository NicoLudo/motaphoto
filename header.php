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
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<a class="skip-link" href="#main-content">Aller au contenu</a>

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
            <button class="menu-toggle" type="button" aria-controls="primary-navigation" aria-expanded="false" aria-label="Ouvrir le menu">
                <span></span>
                <span></span>
                <span></span>
            </button>

            <nav id="primary-navigation" class="primary-navigation" aria-label="<?php esc_attr_e( 'Menu principal', 'motaphoto' ); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'main-menu',
                        'container'      => false,
                        'menu_class'     => 'main-menu',
                        'fallback_cb'    => false,
                    )
                );
                ?>
            </nav>
        <?php endif; ?>
    </div>
</header>
