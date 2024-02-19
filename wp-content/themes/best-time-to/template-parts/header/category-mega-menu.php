<?php
/**
 * Template for category mega menu.
 */

// Get all the category terms.
$parent_terms = get_terms(
	[
		'taxonomy'   => 'category',
		'hide_empty' => true,
	]
);

$first_parent_id = reset( $parent_terms )->term_id;
?>

<div class="sub-menu-wrapper category-popup-wrapper" style="display:none">
	<div class="category-blogs-container">
		<div class="category-wrapper">
			<ul class="category-list">
				<?php
				// Display category terms.
				if ( ! empty( $parent_terms ) ) {
					foreach ( $parent_terms as $parent_term ) {
						$term_id      = $parent_term->term_id;
						$term_name    = $parent_term->name;
						$active_class = $term_id === $first_parent_id ? 'active-category-term' : '';
						?>
							<li class="category-name <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_attr( $term_name ); ?></li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<div class="loader" style="display:none">
			<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
		</div>
		<div class="cat-featured-blog-wrapper"></div>
	</div>
</div>
