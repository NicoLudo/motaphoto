<?php
// Pages
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
get_header();
?>
<main id="main-content" class="site-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <article <?php post_class( 'page-content' ); ?>>
            <h1><?php the_title(); ?></h1>
            <?php the_content(); ?>
        </article>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>
