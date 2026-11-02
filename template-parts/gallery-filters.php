<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$taxonomies = [ 'categorie' => 'Catégories', 'format' => 'Formats' ];
?>

<form id="gallery-filters" class="gallery-filters" aria-label="Filtres des photos">
    <?php foreach ( $taxonomies as $taxonomy => $label ) :
        // Remplit les listes avec les catégories et formats de WordPress
        $terms = get_terms( [ 'taxonomy' => $taxonomy, 'hide_empty' => false ] );
        $terms = is_wp_error( $terms ) ? [] : $terms;
    ?>
        <select name="<?php echo esc_attr( $taxonomy ); ?>" aria-label="<?php echo esc_attr( $label ); ?>">
            <option value=""><?php echo esc_html( $label ); ?></option>

            <?php foreach ( $terms as $term ) : ?>
                <option value="<?php echo esc_attr( $term->term_id ); ?>">
                    <?php echo esc_html( $term->name ); ?>
                </option>
            <?php endforeach; ?>
        </select>
    <?php endforeach; ?>

    <select name="order" aria-label="Trier les photos par date">
        <option value="">Trier par</option>
        <option value="DESC">Plus récentes d’abord</option>
        <option value="ASC">Plus anciennes d’abord</option>
    </select>
</form>
