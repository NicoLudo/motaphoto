<?php
// Photos associées à la fiche consultée.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$categories = get_the_terms( get_the_ID(), 'categorie' );

if ( ! $categories || is_wp_error( $categories ) ) {
    return;
}

// Deux photos partageant une catégorie, des plus récentes aux plus anciennes.
$related_photos = new WP_Query(
    [
        'post_type'      => 'photo',
        'post_status'    => 'publish',
        'posts_per_page' => 2,
        'post__not_in'   => [ get_the_ID() ],
        'no_found_rows'  => true,
        'orderby'       => [
            'date' => 'DESC',
            'ID'   => 'DESC',
        ],
        'tax_query'     => [
            [
                'taxonomy' => 'categorie',
                'field'    => 'term_id',
                'terms'    => wp_list_pluck( $categories, 'term_id' ),
            ],
        ],
    ]
);

if ( ! $related_photos->have_posts() ) {
    return;
}
?>

<section class="related-photos" aria-labelledby="related-photos-title">
    <h2 id="related-photos-title">Vous aimerez aussi</h2>

    <div class="photo-grid">
        <?php
        while ( $related_photos->have_posts() ) :
            $related_photos->the_post();
            get_template_part( 'template-parts/photo-card' );
        endwhile;
        ?>
    </div>
</section>

<?php
// Restaure la photo de la boucle principale.
wp_reset_postdata();
