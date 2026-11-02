<?php
// Vignette réutilisée dans les galeries.
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}
?>

<article class="photo-card">
    <a
        class="photo-card-link"
        href="<?php echo esc_url( get_permalink() ); ?>"
        aria-label="Voir la photo : <?php echo esc_attr( get_the_title() ); ?>"
    >
        <?php
        the_post_thumbnail(
            'large',
            [
                'class' => 'photo-card-image',
                'sizes' => '(max-width: 767px) 318px, (max-width: 1188px) calc(50vw - 30px), 564px',
            ]
        );
        ?>
    </a>
</article>
