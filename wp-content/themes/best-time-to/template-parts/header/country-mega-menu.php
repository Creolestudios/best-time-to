<?php
/**
 * Template for country mega menu.
 */

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
?>

<div class="sub-menu-wrapper country-popup-wrapper" style="display:none">
	<div class="continents-wrapper">
		<ul class="continents-list">
			<?php
			// Display parent terms.
			if ( ! empty( $parent_terms ) ) {
				foreach ( $parent_terms as $parent_term ) {
					$term_id      = $parent_term->term_id;
					$term_name    = $parent_term->name;
					$active_class = $term_id === $first_parent_id ? 'active-parent-term' : '';
					?>
						<li class="continents-name <?php echo esc_attr( $active_class ); ?>" data-term-id="<?php echo esc_attr( $term_id ); ?>"><?php echo esc_attr( $term_name ); ?></li>
					<?php
				}
				?>
					<li class="continents-name" data-term-id="all"><?php echo esc_html( 'Other' ); ?></li>
				<?php
			}
			?>
		</ul>
	</div>
	<div class="loader" style="display:none">
		<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
	</div>
	<div class="country-blogs-container"></div>
</div>
