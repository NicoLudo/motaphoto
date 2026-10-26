<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

get_header();
?>

<main id="main-content" class="site-main photo-main">
    <?php while ( have_posts() ) : the_post(); ?>
        <?php
        $reference = get_field( 'reference' );
        $type = get_field( 'type' );
        $categories = get_the_terms( get_the_ID(), 'categorie' );
        $formats = get_the_terms( get_the_ID(), 'format' );

        // Fonctions de WordPress. Permet de naviguer parmi les photos selon leur date
        $previous_photo = get_previous_post();
        $next_photo = get_next_post();
        ?>

        <article <?php post_class( 'photo-detail' ); ?>>
            <div class="photo-info">
                <h1 class="photo-title">
                    <?php echo esc_html( get_the_title() ); ?>
                </h1>

                <p>
                    Référence : <?php echo esc_html( $reference ); ?>
                </p>

                <?php if ( $categories && ! is_wp_error( $categories ) ) : ?>
                    <p>
                        Catégorie : <?php echo esc_html( $categories[0]->name ); ?>
                    </p>
                <?php endif; ?>

                <?php if ( $formats && ! is_wp_error( $formats ) ) : ?>
                    <p>
                        Format : <?php echo esc_html( $formats[0]->name ); ?>
                    </p>
                <?php endif; ?>

                <p>
                    Type : <?php echo esc_html( $type ); ?>
                </p>

                <p>
                    Année : <?php echo esc_html( get_the_date( 'Y' ) ); ?>
                </p>
            </div>

            <div class="photo-image">
                <?php
                if ( has_post_thumbnail() ) {
                    the_post_thumbnail(
                        'large',
                        array(
                            'sizes' => '(max-width: 767px) 265px, (max-width: 1188px) calc(50vw - 30px), 564px',
                        )
                    );
                }
                ?>
            </div>
        </article>

        <div class="photo-actions">
            <div class="photo-contact">
                <p>Cette photo vous intéresse ?</p>

                <a
                    href="#contact"
                    class="photo-contact-button"
                    data-photo-reference="<?php echo esc_attr( $reference ); ?>"
                    aria-haspopup="dialog"
                    aria-controls="contact-modal"
                >
                    Contact
                </a>
            </div>

            <?php if ( $previous_photo || $next_photo ) : ?>
                <nav class="photo-navigation" aria-label="Navigation entre les photos">

                    <?php if ( $previous_photo ) : ?>
                        <a
                            class="photo-nav-link photo-nav-previous"
                            href="<?php echo esc_url( get_permalink( $previous_photo->ID ) ); ?>"
                            aria-label="Photo précédente : <?php echo esc_attr( get_the_title( $previous_photo->ID ) ); ?>"
                        >
                            <span class="photo-nav-arrow" aria-hidden="true">←</span>

                            <?php
                            echo get_the_post_thumbnail(
                                $previous_photo->ID,
                                'thumbnail',
                                array(
                                    'class' => 'photo-nav-preview',
                                    'alt'   => '',
                                )
                            );
                            ?>
                        </a>
                    <?php endif; ?>

                    <?php if ( $next_photo ) : ?>
                        <a
                            class="photo-nav-link photo-nav-next"
                            href="<?php echo esc_url( get_permalink( $next_photo->ID ) ); ?>"
                            aria-label="Photo suivante : <?php echo esc_attr( get_the_title( $next_photo->ID ) ); ?>"
                        >
                            <span class="photo-nav-arrow" aria-hidden="true">→</span>

                            <?php
                            echo get_the_post_thumbnail(
                                $next_photo->ID,
                                'thumbnail',
                                array(
                                    'class' => 'photo-nav-preview',
                                    'alt'   => '',
                                )
                            );
                            ?>
                        </a>
                    <?php endif; ?>

                </nav>
            <?php endif; ?>
        </div>
    <?php endwhile; ?>
</main>

<?php get_footer(); ?>