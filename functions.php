<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Réglages du thème
function motaphoto_setup() {
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'custom-logo', array( 'flex-width' => true, 'flex-height' => true ) );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

    // Les menus du site
    register_nav_menus(
        array(
            'main-menu'   => __( 'Menu principal', 'motaphoto' ),
            'footer-menu' => __( 'Menu du pied de page', 'motaphoto' ),
        )
    );
}
add_action( 'after_setup_theme', 'motaphoto_setup' );

// CSS et JavaScript
function motaphoto_enqueue_assets() {
    wp_enqueue_style( 'motaphoto-style', get_stylesheet_uri() );
    wp_enqueue_script( 'motaphoto-scripts', get_template_directory_uri() . '/js/scripts.js', array(), null, true );
}
add_action( 'wp_enqueue_scripts', 'motaphoto_enqueue_assets' );
