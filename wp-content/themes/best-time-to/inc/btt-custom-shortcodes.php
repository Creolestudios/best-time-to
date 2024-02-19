<?php
/**
 * General shortcodes of theme.
 *
 * @package btt
 */

/**
 * Shortcode to manage Ads section layout
 *
 * @version 1.0.0
 * @param attributes $atts Attributes of shortcode.
 */
function btt_ads_script_layout( $atts ) {
	ob_start();

	if ( 'horizontal-banner' === strtolower( $atts['layout'] ) ) {
		if ( get_field( 'horizontal_banner', 'options' ) ) {
			?>
				<div class="add-banner">
					<?php get_field( 'horizontal_banner', 'options' ); ?>
				</div>
			<?php
		} else {
			?>
				<div class="add-banner">
					<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/addbanner.png' ); ?>" alt="advertisement banner">
				</div>
			<?php
		}
	}
	if ( 'vertical-banner' === strtolower( $atts['layout'] ) ) {
		if ( get_field( 'vertical_banner', 'options' ) ) {
			?>
				<div class="add-banner-vertical <?php echo esc_attr( $atts['class'] ); ?>">
					<?php get_field( 'vertical_banner', 'options' ); ?>
				</div>
			<?php
		} else {
			?>
				<div class="add-banner-vertical <?php echo esc_attr( $atts['class'] ); ?>">
					<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/sidebar-ads.png' ); ?>" alt="advertisement banner">
				</div>
			<?php
		}
	}

	$html = ob_get_contents();
	ob_end_clean();
	return $html;
}
add_shortcode( 'ads_section_layout', 'btt_ads_script_layout' );

/**
 * Generate custom category breadcrumb.
 *
 * @param array $atts Shortcode attributes.
 *
 * @return string Breadcrumb HTML.
 */
function btt_generate_category_breadcrumb( $atts ) {
	$atts = shortcode_atts(
		[
			'post_id' => get_the_ID(),
		],
		$atts
	);

	$post_id      = absint( $atts['post_id'] );
	$life_term    = get_the_category( $post_id ) ? get_the_category( $post_id ) : [];
	$travel_terms = get_the_terms( $post_id, 'country' ) ? get_the_terms( $post_id, 'country' ) : [];
	$nature_terms = get_the_terms( $post_id, 'nature' ) ? get_the_terms( $post_id, 'nature' ) : [];

	// Get all categories assigned to the post.
	$all_categories = array_merge( $life_term, $travel_terms, $nature_terms );

	// Separate parent and child categories for 'country' taxonomy.
	$parent_categories = [];
	$child_categories  = [];

	if ( $all_categories ) {
		foreach ( $all_categories as $category ) {
			if ( $category->parent === 0 ) {
				$parent_categories[] = $category;
			} else {
				$child_categories[] = $category;
			}
		}
	}

	// Initialize the breadcrumb trail.
	$breadcrumb = '';

	if ( $parent_categories ) {
		foreach ( $parent_categories as $index => $category ) {
			if ( $index > 0 ) {
				$breadcrumb .= '<span>&gt;</span>';
			}
			$breadcrumb .= '<a class="post-category" href="' . esc_url( get_term_link( $category ) ) . '"><h5>' . esc_html( $category->name ) . '</h5></a>';
		}
	}

	// Add child category if available.
	if ( $child_categories ) {
		foreach ( $child_categories as $category ) {
			$breadcrumb .= '<span>&gt;</span>';
			$breadcrumb .= '<a class="post-category" href="' . esc_url( get_term_link( $category ) ) . '"><h5>' . esc_html( $category->name ) . '</h5></a>';
		}
	}

	return '<div class="breadcrumb-category">' . $breadcrumb . '</div>';
}
add_shortcode( 'custom_category_breadcrumb', 'btt_generate_category_breadcrumb' );
