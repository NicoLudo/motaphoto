<?php
// WordPress choisit ce modèle pour une fiche photo
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main class="site-main photo-main">
    <?php
    while ( have_posts() ) :
        the_post();

        // SCF fournit la référence et le type, WordPress fournit les termes
        $reference  = get_field( 'reference' );
        $type       = get_field( 'type' );
        $categories = get_the_terms( get_the_ID(), 'categorie' );
        $formats    = get_the_terms( get_the_ID(), 'format' );

        // Les flèches suivent l’ordre chronologique des fiches photo
        $previous_photo = get_previous_post();
        $next_photo     = get_next_post();
        ?>

        <article <?php post_class( 'photo-detail' ); ?>>
            <div class="photo-info">
                <h1 class="photo-title"><?php echo esc_html( get_the_title() ); ?></h1>

                <p>Référence : <?php echo esc_html( $reference ); ?></p>

                <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                    <p>Catégorie : <?php echo esc_html( $categories[0]->name ); ?></p>
                <?php endif; ?>

                <?php if ( $formats && ! is_wp_error( $formats ) ) : ?>
                    <p>Format : <?php echo esc_html( $formats[0]->name ); ?></p>
                <?php endif; ?>

                <p>Type : <?php echo esc_html( $type ); ?></p>
                <p>Année : <?php echo esc_html( get_the_date( 'Y' ) ); ?></p>
            </div>

            <div class="photo-image">
                <?php
                // Le navigateur choisit une taille adaptée à la largeur affichée
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail(
                        'large',
                        [
                            'sizes' => '(max-width: 767px) 265px, (max-width: 1188px) calc(50vw - 30px), 564px',
                        ]
                    );
                }
                ?>
            </div>
        </article>

        <div class="photo-actions">
            <div class="photo-contact">
                <p>Cette photo vous intéresse ?</p>

                <?php // Le bouton transmet la référence au formulaire de contact ?>
                <a
                    href="#contact"
                    class="photo-contact-button"
                    data-photo-reference="<?php echo esc_attr( $reference ); ?>"
                >
                    Contact
                </a>
            </div>

            <?php // Chaque flèche affiche un aperçu de la photo vers laquelle elle mène ?>
            <?php if ( $previous_photo || $next_photo ) : ?>
                <nav class="photo-navigation" aria-label="Navigation entre les photos">
                    <?php
                    foreach ( [ 'previous' => $previous_photo, 'next' => $next_photo ] as $direction => $photo ) :
                        if ( ! $photo ) {
                            continue;
                        }
                        $is_previous = $direction === 'previous';
                        $label       = $is_previous ? 'Photo précédente' : 'Photo suivante';
                        $arrow       = $is_previous ? '←' : '→';
                    ?>
                        <a
                            class="photo-nav-link photo-nav-<?php echo esc_attr( $direction ); ?>"
                            href="<?php echo esc_url( get_permalink( $photo->ID ) ); ?>"
                            aria-label="<?php echo esc_attr( $label ); ?> : <?php echo esc_attr( get_the_title( $photo->ID ) ); ?>"
                        >
                            <?php echo $arrow; ?>

                            <?php
                            echo get_the_post_thumbnail(
                                $photo->ID,
                                'thumbnail',
                                [
                                    'class' => 'photo-nav-preview',
                                    'alt'   => '',
                                ]
                            );
                            ?>
                        </a>
                    <?php endforeach; ?>
                </nav>
            <?php endif; ?>
        </div>

        <?php get_template_part( 'template-parts/related-photos' ); ?>
    <?php endwhile; ?>
</main>

<?php get_footer();
