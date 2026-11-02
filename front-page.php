<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main-content" class="site-main home-main">
    <section class="hero" aria-labelledby="hero-title">
        <?php
        // Utilise l'image mise en avant de la page Accueil
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
        <?php
        // Récupère les huit photos les plus récentes
        $photos = new WP_Query(
            [
                'post_type'      => 'photo',
                'post_status'    => 'publish',
                'posts_per_page' => 8,
                'no_found_rows'  => true,
                'orderby'       => [
                    'date' => 'DESC',
                    'ID'   => 'DESC',
                ],
            ]
        );
        ?>

        <?php if ( $photos->have_posts() ) : ?>
            <div id="photo-grid" class="photo-grid">
                <?php
                while ( $photos->have_posts() ) :
                    $photos->the_post();
                    get_template_part( 'template-parts/photo-card' );
                endwhile;
                ?>
            </div>
        <?php else : ?>
            <p>Aucune photo à afficher pour le moment</p>
        <?php endif; ?>

        <?php
        // Rétablit les données de la page Accueil
        wp_reset_postdata();
        ?>
    </section>
</main>

<?php get_footer();