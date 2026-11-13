<?php
// Affiche le contenu des pages saisies dans WordPress
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>
<main class="site-main">
    <?php
    while ( have_posts() ) :
        the_post();
    ?>
        <article <?php post_class( 'page-content' ); ?>>
            <?php if ( '' !== trim( get_the_content() ) ) : ?>
                <h1><?php the_title(); ?></h1>
            <?php endif; ?>
            <?php the_content(); ?>
        </article>
    <?php endwhile; ?>
</main>

<?php get_footer();
