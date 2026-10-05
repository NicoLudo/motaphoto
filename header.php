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

<header class="site-header">
    <!-- Ce texte provisoire sera remplacé par le logo exporté de Figma. -->
    <a class="site-name" href="<?php echo esc_url( home_url( '/' ) ); ?>">NATHALIE MOTA</a>
    <?php if ( has_nav_menu( 'main-menu' ) ) : ?>
        <nav aria-label="<?php esc_attr_e( 'Menu principal', 'motaphoto' ); ?>">
            <?php
            wp_nav_menu(
                array(
                    'theme_location' => 'main-menu',
                    'container'      => false,
                    'fallback_cb'    => false,
                )
            );
            ?>
        </nav>
    <?php endif; ?>
</header>
