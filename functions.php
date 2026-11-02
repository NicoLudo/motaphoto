<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Réglages du thème
function motaphoto_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', [ 'flex-width' => true, 'flex-height' => true ] );
    add_theme_support( 'html5', [ 'search-form', 'gallery', 'caption', 'style', 'script' ] );

    register_nav_menus(
        [
            'main-menu'   => __( 'Menu principal', 'motaphoto' ),
            'footer-menu' => __( 'Menu du pied de page', 'motaphoto' ),
        ]
    );
}
add_action( 'after_setup_theme', 'motaphoto_setup' );

// Charge les fichiers CSS et JavaScript du thème
function motaphoto_enqueue_assets() {
    wp_enqueue_style( 'motaphoto-style', get_stylesheet_uri(), [], filemtime( get_stylesheet_directory() . '/style.css' ) );

    $theme_path = get_template_directory();
    $theme_url  = get_template_directory_uri();

    // La date des fichiers renouvelle le cache après une modification.
    foreach ( [ 'menu', 'contact' ] as $script ) {
        wp_enqueue_script(
            'motaphoto-' . $script,
            $theme_url . '/js/' . $script . '.js',
            [],
            filemtime( $theme_path . '/js/' . $script . '.js' ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'motaphoto_enqueue_assets' );
