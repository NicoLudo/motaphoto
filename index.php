<?php
// Modèle utilisé quand WordPress ne trouve pas de fichier plus précis
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main class="site-main">
    <?php if ( have_posts() ) : ?>
        <?php if ( ! is_singular() ) : ?>
            <h1><?php echo is_search() ? 'Résultats de recherche' : 'Publications'; ?></h1>
        <?php endif; ?>

        <?php while ( have_posts() ) : the_post(); ?>
            <article <?php post_class(); ?>>
                <?php if ( is_singular() ) : ?>
                    <h1><?php the_title(); ?></h1>
                    <?php the_content(); ?>
                <?php else : ?>
                    <h2><a href="<?php echo esc_url( get_permalink() ); ?>"><?php the_title(); ?></a></h2>
                    <?php the_excerpt(); ?>
                <?php endif; ?>
            </article>
        <?php endwhile; ?>

        <?php the_posts_pagination(); ?>
    <?php else : ?>
        <h1><?php echo is_404() ? 'Page introuvable' : 'Aucun résultat'; ?></h1>
        <p><a href="<?php echo esc_url( home_url( '/' ) ); ?>">Retour à l’accueil</a></p>
    <?php endif; ?>
</main>

<?php get_footer();
