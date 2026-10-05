<?php
/**
 * Configuration du thème et chargement de ses ressources.
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

function motaphoto_setup() {
    // WordPress gère le titre de l'onglet et les images mises en avant.
    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', array( 'search-form', 'gallery', 'caption', 'style', 'script' ) );

    // Les liens des menus seront choisis depuis l'administration WordPress.
    register_nav_menus(
        array(
            'main-menu'   => __( 'Menu principal', 'motaphoto' ),
            'footer-menu' => __( 'Menu du pied de page', 'motaphoto' ),
        )
    );
}
add_action( 'after_setup_theme', 'motaphoto_setup' );

function motaphoto_enqueue_assets() {
    // Le numéro change quand le CSS est modifié, pour actualiser le navigateur.
    wp_enqueue_style(
        'motaphoto-style',
        get_stylesheet_uri(),
        array(),
        (string) filemtime( get_stylesheet_directory() . '/style.css' )
    );
}
add_action( 'wp_enqueue_scripts', 'motaphoto_enqueue_assets' );
