<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main-content" class="site-main home-main">
    <section class="hero" aria-labelledby="hero-title">
        <?php
        // La photo du bandeau vient de l’image mise en avant de la page Accueil
        echo get_the_post_thumbnail(
            get_queried_object_id(),
            'full',
            [
                'class'         => 'hero-image',
                'alt'           => '',
                'sizes'         => '100vw',
                'loading'       => 'eager',
                'fetchpriority' => 'high',
            ]
        );
        ?>

        <h1 id="hero-title" class="hero-title">Photographe event</h1>
    </section>

    <section class="home-gallery" aria-label="Galerie de photos">
        <?php get_template_part( 'template-parts/gallery-filters' ); ?>

        <?php
        // Le premier lot est déjà présent quand la page s’affiche
        $photos = motaphoto_get_photos();
        ?>

        <div id="photo-grid" class="photo-grid">
            <?php
            // La même carte sert à l’accueil, aux filtres et aux photos apparentées
            while ( $photos->have_posts() ) :
                $photos->the_post();
                get_template_part( 'template-parts/photo-card' );
            endwhile;
            ?>
        </div>

        <?php
        // Rétablit la page Accueil après avoir parcouru les photos
        wp_reset_postdata();
        ?>

        <?php // Le bouton garde l’adresse Ajax, le jeton et le numéro de page ?>
        <button
            id="load-more"
            class="load-more"
            type="button"
            aria-controls="photo-grid"
            data-page="1"
            data-url="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>"
            data-nonce="<?php echo esc_attr( wp_create_nonce( 'motaphoto_photos' ) ); ?>"
            <?php echo $photos->max_num_pages <= 1 ? 'hidden' : ''; ?>
        >
            Charger plus
        </button>

        <p id="gallery-status" class="gallery-status" role="status"><?php echo $photos->post_count ? '' : 'Aucune photo à afficher pour le moment'; ?></p>
    </section>
</main>

<?php get_footer();
