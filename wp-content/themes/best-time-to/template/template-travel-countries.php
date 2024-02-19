<?php
/**
 * Template Name: Travel Countries
 *
 * Custom template for the country list.
 *
 * @version 1.0.0
 * @package btt
 */
get_header();

// Get all the parent terms.
$parent_terms = get_terms(
	[
		'taxonomy'   => 'country',
		'parent'     => 0,
		'hide_empty' => true,
		'meta_key'   => 'mega_menu_order',
		'orderby'    => 'meta_value_num',
		'order'      => 'ASC',
	]
);

$first_parent_id = $parent_terms[0]->term_id;

// Get child terms.
$child_terms = get_terms(
	[
		'taxonomy' => 'country',
		'child_of' => $first_parent_id,
		// 'number'   => 9,
	]
);

?>

<main>
	<?php get_template_part( 'template-parts/header/page', 'introduction' ); ?>
	
	<div class="travel-country-wrapper">
		<ul class="travel-country-list">
			<?php
			// Display parent terms.
			if ( ! empty( $parent_terms ) ) {
				foreach ( $parent_terms as $parent_term ) {
					$term_id      = $parent_term->term_id;
					$term_name    = $parent_term->name;
					$active_class = $term_id === $first_parent_id ? 'active-travel-term' : '';
					?>
						<li class="travel-country-name <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>"><h5><?php echo esc_attr( $term_name ); ?></h5></li>
					<?php
				}
			}
			?>
		</ul>
	</div>

	<div class="category-wrapper">
		<h5><?php echo esc_html( 'Countries' ); ?></h5>
		<div class="category-item-list country-item-list" data-track=<?php echo esc_attr( count( $child_terms ) ); ?>>
			<?php
			$default_cat_img = get_field( 'no_thumbnail_for_category', 'option' );
			foreach ( $child_terms as $track => $term ) {
				if ( $track < 9 ) {
					$term_image = get_field( 'category_image', 'country_' . $term->term_id );
					?>
					<a href="<?php echo esc_attr( get_term_link( $term, 'country' ) ); ?>" class="item">
						<div class="category-item" style="background-image: url('<?php echo esc_attr( ! empty( $term_image ) ? $term_image : $default_cat_img ); ?>');">
							<h4 class="text-white"><?php echo esc_html( $term->name ); ?></h4>
						</div>
					</a>
					<?php
				}
			}
			?>
		</div>
		<div class="loader country-loader" style="display:none">
			<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
		</div>
		<div class="btn load-more-country"><?php echo esc_html( 'Load more' ); ?></div>
	</div>
</main>

<?php
get_footer();
?>
