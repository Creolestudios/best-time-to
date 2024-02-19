<?php
/**
 * Template for nature mega menu.
 */

// Get all the nature terms.
$nature_terms = get_terms(
	[
		'taxonomy'   => 'nature',
		'hide_empty' => true,
	]
);

$first_term_id = reset( $nature_terms )->term_id;
?>

<div class="sub-menu-wrapper nature-popup-wrapper" style="display:none">
	<div class="nature-blogs-container">
		<div class="nature-wrapper">
			<ul class="nature-list">
				<?php
				// Display nature terms.
				if ( ! empty( $nature_terms ) ) {
					foreach ( $nature_terms as $nature_term ) {
						$term_id      = $nature_term->term_id;
						$term_name    = $nature_term->name;
						$active_class = $term_id === $first_term_id ? 'active-nature-term' : '';
						?>
							<li class="nature-term <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_attr( $term_name ); ?></li>
						<?php
					}
				}
				?>
			</ul>
		</div>
		<div class="loader nature-term-loader" style="display:none">
			<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
		</div>
		<div class="nature-featured-blog-wrapper"></div>
	</div>
</div>
