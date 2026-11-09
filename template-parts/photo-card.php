<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Les vignettes reprennent les informations de la fiche photo
$photo_id  = get_the_ID();
$title     = get_the_title();
$reference = get_field( 'reference' ) ?: '';
$image_url = get_the_post_thumbnail_url( $photo_id, 'full' );
$alt       = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true ) ?: $title;

$categories = get_the_terms( $photo_id, 'categorie' );
$category   = '';

if ( $categories && ! is_wp_error( $categories ) ) {
    $category = implode( ', ', wp_list_pluck( $categories, 'name' ) );
}
?>

<article class="photo-card">
    <?php
    the_post_thumbnail(
        'large',
        [
            'class' => 'photo-card-image',
            'sizes' => '(max-width: 767px) 318px, (max-width: 1188px) calc(50vw - 30px), 564px',
        ]
    );
    ?>

    <div class="photo-card-overlay">
        <a
            class="photo-card-link"
            href="<?php echo esc_url( get_permalink() ); ?>"
            aria-label="Voir la photo : <?php echo esc_attr( $title ); ?>"
        >
            <svg viewBox="0 0 48 32" aria-hidden="true" focusable="false">
                <path d="M2 16s8-13 22-13 22 13 22 13-8 13-22 13S2 16 2 16Z"/>
                <circle cx="24" cy="16" r="7"/>
            </svg>
        </a>

        <?php // Le bouton garde l’adresse de la grande photo pour l’ouverture de la lightbox ?>
        <?php if ( $image_url ) : ?>
            <button
                class="photo-fullscreen"
                type="button"
                aria-haspopup="dialog"
                aria-controls="lightbox"
                aria-label="Agrandir la photo : <?php echo esc_attr( $title ); ?>"
                data-image="<?php echo esc_url( $image_url ); ?>"
                data-title="<?php echo esc_attr( $title ); ?>"
                data-alt="<?php echo esc_attr( $alt ); ?>"
                data-reference="<?php echo esc_attr( $reference ); ?>"
                data-category="<?php echo esc_attr( $category ); ?>"
            >
                <svg viewBox="0 0 34 34" aria-hidden="true" focusable="false">
                    <circle cx="17" cy="17" r="17" fill="#000"/>
                    <path d="M14 10h-4v4m10-4h4v4M10 20v4h4m10-4v4h-4" fill="none" stroke="#fff"/>
                </svg>
            </button>
        <?php endif; ?>

        <div class="photo-card-meta">
            <span><?php echo esc_html( $reference ); ?></span>
            <span><?php echo esc_html( $category ); ?></span>
        </div>
    </div>
</article>
