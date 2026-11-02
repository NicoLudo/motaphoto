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

    if ( is_front_page() ) {
        wp_enqueue_script(
            'motaphoto-gallery',
            $theme_url . '/js/gallery.js',
            [],
            filemtime( $theme_path . '/js/gallery.js' ),
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'motaphoto_enqueue_assets' );

// Filtre les photos et conserve un tri stable
function motaphoto_get_photos( $page = 1, $filters = [] ) {
    $order = ( $filters['order'] ?? '' ) === 'ASC' ? 'ASC' : 'DESC';

    $args = [
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => 8,
        'paged'          => $page,
        'orderby'       => [ 'date' => $order, 'ID' => $order ],
        'tax_query'     => [ 'relation' => 'AND' ],
    ];

    foreach ( [ 'categorie', 'format' ] as $taxonomy ) {
        $value   = $filters[ $taxonomy ] ?? 0;
        $term_id = is_scalar( $value ) ? absint( $value ) : 0;

        if ( $term_id ) {
            $args['tax_query'][] = [
                'taxonomy' => $taxonomy,
                'field'    => 'term_id',
                'terms'    => $term_id,
            ];
        }
    }

    return new WP_Query( $args );
}

// Renvoie le lot demandé sans recharger la page
function motaphoto_load_photos() {
    check_ajax_referer( 'motaphoto_photos', 'nonce' );

    $page   = max( 1, absint( $_POST['page'] ?? 1 ) );
    $photos = motaphoto_get_photos( $page, wp_unslash( $_POST ) );

    ob_start();

    while ( $photos->have_posts() ) {
        $photos->the_post();
        get_template_part( 'template-parts/photo-card' );
    }

    $html = ob_get_clean();
    wp_reset_postdata();

    wp_send_json_success(
        [
            'html'     => $html,
            'has_more' => $page < $photos->max_num_pages,
        ]
    );
}

add_action( 'wp_ajax_motaphoto_load_photos', 'motaphoto_load_photos' );
add_action( 'wp_ajax_nopriv_motaphoto_load_photos', 'motaphoto_load_photos' );