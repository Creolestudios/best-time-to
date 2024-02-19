<?php
/**
 * Theme Setup functions.
 *
 * @version 1.0.0
 *
 * @package btt
 */

 /**
  * Custom walker class for header menu.
  */
class Custom_Header_Menu_Walker extends Walker_Nav_Menu {
	/**
	 * Add the custom dropdown icon for specific menu items.
	 *
	 * @param string $output Passed by reference. Used to append additional content.
	 * @param object $item   Menu item data object.
	 * @param int    $depth  Depth of menu item. Used for padding.
	 * @param array  $args   Menu item arguments.
	 * @param int    $id     Current menu item ID.
	 */
	public function start_el( &$output, $item, $depth = 0, $args = [], $id = 0 ) {
		$indent  = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		$output .= $indent . '<li';

		$custom_class = get_field( 'custom_class', $item->ID );

		// Add custom class for items that have the dropdown icon enabled.
		if ( $item->has_dropdown_icon ) {
			$output .= ' class="header-menu-links has-child ' . $custom_class . '"';
		} else {
			$output .= ' class="header-menu-links ' . $custom_class . '"';
		}

		$output .= '>';

		$atts           = [];
		$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
		$atts['target'] = ! empty( $item->target ) ? $item->target : '';
		$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
		$atts['href']   = ! empty( $item->url ) ? $item->url : '';

		$atts = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args, $depth );

		$attributes = '';
		foreach ( $atts as $attr => $value ) {
			if ( ! empty( $value ) ) {
				$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
				$attributes .= ' ' . $attr . '="' . $value . '"';
			}
		}

		$title        = apply_filters( 'the_title', $item->title, $item->ID );
		$item_output  = $args->before;
		$item_output .= '<a' . $attributes . '>';
		$item_output .= '<h5>' . $args->link_before . $title . $args->link_after . '</h5>';

		$item_output .= '</a>';

		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}

/**
 * Function for image alt.
 *
 * @param post_id $post_id ID of a post.
 *
 * @version 1.0.0
 */
function btt_get_img_alt( $post_id ) {
	$post_img_id = get_post_thumbnail_id( $post_id );
	return ( ! empty( get_post_meta( $post_img_id, '_wp_attachment_image_alt', true ) ) ) ? get_post_meta( $post_img_id, '_wp_attachment_image_alt', true ) : get_the_title( $post_id );
}

/**
 * Ajax callback to get country from parent term with posts.
 *
 * @version 1.0.0
 */
function btt_filter_country_with_posts() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$parent_term_id = isset( $_POST['parent_term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['parent_term_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$child_terms = btt_get_country_child_terms( $parent_term_id );

		if ( empty( $child_terms ) ) {
			return '';
		}

		$first_child_id = $child_terms[0];

		ob_start();
		?>
			<div class="country-wrapper">
				<div class="mega-menu-title"><?php echo esc_html( 'Countries' ); ?></div>
				<ul class="country-list">
					<?php
					foreach ( $child_terms as $child_term_id ) {
						$child_term      = get_term_by( 'id', $child_term_id, 'country' );
						$child_term_name = $child_term->name;
						$child_term_id   = $child_term->term_id;
						$child_active    = $child_term_id == $first_child_id ? 'active-child-term' : '';
						?>
							<li class="country-name <?php echo esc_attr( $child_active ); ?>" data-term-id="<?php echo esc_attr( $child_term_id ); ?>"><?php echo esc_html( $child_term_name ); ?></li>
						<?php
					}
					?>
				</ul>
			</div>
		<?php
		$html = ob_get_contents();
		ob_end_clean();

		$html    .= btt_get_filtered_country_posts( $parent_term_id, $first_child_id );
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
	wp_die();
}
add_action( 'wp_ajax_btt_filter_country_with_posts', 'btt_filter_country_with_posts' );
add_action( 'wp_ajax_nopriv_btt_filter_country_with_posts', 'btt_filter_country_with_posts' );

/**
 * Ajax callback to get filtered posts by country.
 *
 * @version 1.0.0
 */
function btt_filter_posts_by_country() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$child_term_id  = isset( $_POST['child_term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['child_term_id'] ) ) : '';
	$parent_term_id = isset( $_POST['parent_term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['parent_term_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$html     = btt_get_filtered_country_posts( $parent_term_id, $child_term_id );
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_filter_posts_by_country', 'btt_filter_posts_by_country' );
add_action( 'wp_ajax_nopriv_btt_filter_posts_by_country', 'btt_filter_posts_by_country' );

/**
 * Ajax callback to get filtered posts by category.
 *
 * @version 1.0.0
 */
function btt_filter_posts_by_category() {
	$security_nonce   = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$category_term_id = isset( $_POST['category_term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['category_term_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$html     = btt_get_filtered_category_posts( $category_term_id );
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_filter_posts_by_category', 'btt_filter_posts_by_category' );
add_action( 'wp_ajax_nopriv_btt_filter_posts_by_category', 'btt_filter_posts_by_category' );

/**
 * Ajax callback to get filtered posts by nature term.
 *
 * @version 1.0.0
 */
function btt_filter_posts_by_nature() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$nature_term_id = isset( $_POST['nature_term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['nature_term_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$html     = btt_get_filtered_nature_posts( $nature_term_id );
		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_filter_posts_by_nature', 'btt_filter_posts_by_nature' );
add_action( 'wp_ajax_nopriv_btt_filter_posts_by_nature', 'btt_filter_posts_by_nature' );

/**
 * Get child terms of a given parent term in the 'country' taxonomy.
 *
 * @param int $parent_term_id The ID of the parent term.
 * @return array An array containing the child term IDs.
 */
function btt_get_country_child_terms( $parent_term_id ) {
	$child_terms = get_term_children( $parent_term_id, 'country' );
	return $child_terms;
}

/**
 * Get post details from a specific parent and child term ID.
 *
 * @param int $parent_term_id The ID of the parent term.
 * @param int $child_term_id  The ID of the child term.
 *
 * @return string The HTML content as a string.
 */
function btt_get_filtered_country_posts( $parent_term_id, $child_term_id ) {
	$term = get_term( $child_term_id, 'country' );
	if ( $term instanceof WP_Term && ! is_wp_error( $term ) ) {
		$child_term_name = $term->name;
		$child_term_url  = get_term_link( $term );
	}

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => 6,
		'meta_query'     => [
			[
				'key'     => 'show_on_travel_menu',
				'value'   => true,
				'compare' => '=',
				'type'    => 'BOOLEAN',
			],
		],
		'tax_query'      => [
			'relation' => 'AND',
			[
				'taxonomy' => 'country',
				'field'    => 'term_id',
				'terms'    => $parent_term_id,
			],
			[
				'taxonomy' => 'country',
				'field'    => 'term_id',
				'terms'    => $child_term_id,
			],
		],
	];

	$filtered_posts = new WP_Query( $args );

	// If the query has posts, return the post details.
	if ( $filtered_posts->have_posts() ) {
		$post_details = [];
		ob_start();
		?>
		<div class="featured-blogs-wrapper">
			<p class="featured-blogs-title mega-menu-title"><?php echo esc_html( 'Featured Blogs' ); ?></p>
			<div class="featured-blog-list-wrapper">
				<?php
				while ( $filtered_posts->have_posts() ) {
					$filtered_posts->the_post();

					$post_id    = get_the_ID();
					$post_title = get_the_title();
					$post_img   = get_the_post_thumbnail_url( $post_id, 'large' );
					$permalink  = get_permalink();
					?>
						<div class="featured-blog-list">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<span class="arrow arrow-small arrow-white" href="#" tabindex="0"></span>
								<div class="featured-blog-image">
									<?php
									if ( ! empty( $post_img ) ) {
										?>
											<img src="<?php echo esc_url( $post_img ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $post_id ) ); ?>">
										<?php
									} else {
										$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
										?>
											<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
										<?php
									}
									?>
								</div>
								<div class="featured-blog-meta">
									<p><?php echo esc_html( $post_title ); ?></p>
								</div>
							</a>
						</div>
					<?php
				}
				?>
			</div>
			<a class="explore-country" href="<?php echo esc_url( $child_term_url ); ?>">
				<?php echo esc_html( 'Explore ' . $child_term_name ); ?>
			</a>
		</div>
		<?php
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
	} else {
		ob_start();
		?>
			<div class="featured-blogs-wrapper">
				<h3 class="no-data-found"><?php echo esc_html( 'No data found.' ); ?></h3>
			</div>
		<?php
		$html = ob_get_clean();
		ob_end_clean();
	}
	return $html;
}

/**
 * Get post details from a specific category.
 *
 * @param int $category_term_id The ID of the category term.
 *
 * @return string The HTML content as a string.
 */
function btt_get_filtered_category_posts( $category_term_id ) {
	$category_name = get_cat_name( $category_term_id );
	$category_url  = get_category_link( $category_term_id );

	$args = [
		'post_type'      => 'post',
		'posts_per_page' => 6,
		'cat'            => $category_term_id,
		'meta_query'     => [
			[
				'key'     => 'show_on_life_menu',
				'value'   => true,
				'compare' => '=',
				'type'    => 'BOOLEAN',
			],
		],
	];

	$filtered_posts = new WP_Query( $args );

	// If the query has posts, return the post details.
	if ( $filtered_posts->have_posts() ) {
		$post_details = [];
		ob_start();
		?>
			<p class="cat-featured-blogs-title mega-menu-title"><?php echo esc_html( 'Featured Blogs' ); ?></p>
			<div class="cat-featured-blogs-wrapper">
				<?php
				while ( $filtered_posts->have_posts() ) {
					$filtered_posts->the_post();

					$post_id    = get_the_ID();
					$post_title = get_the_title();
					$post_img   = get_the_post_thumbnail_url( $post_id, 'large' );
					$permalink  = get_permalink();
					?>
						<div class="cat-featured-blog-list">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<span class="arrow arrow-small arrow-white" href="#" tabindex="0"></span>
								<div class="cat-featured-blog-image">
									<?php
									if ( ! empty( $post_img ) ) {
										?>
											<img src="<?php echo esc_url( $post_img ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $post_id ) ); ?>">
										<?php
									} else {
										$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
										?>
											<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
										<?php
									}
									?>
								</div>
								<div class="cat-featured-blog-meta">
									<p><?php echo esc_html( $post_title ); ?></p>
								</div>
							</a>
						</div>
					<?php
				}
				?>
			</div>
			<a class="explore-country" href="<?php echo esc_url( $category_url ); ?>">
				<?php echo esc_html( 'Explore ' . $category_name ); ?>
			</a>
		<?php
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
	} else {
		ob_start();
		?>
			<h3 class="no-data-found"><?php echo esc_html( 'No data found.' ); ?></h3>
		<?php
		$html = ob_get_clean();
		ob_end_clean();
	}
	return $html;
}

/**
 * Get post details from a specific nature term.
 *
 * @param int $nature_term_id The ID of the nature term.
 *
 * @return string The HTML content as a string.
 */
function btt_get_filtered_nature_posts( $nature_term_id ) {
	$nature_term = get_term( $nature_term_id, 'nature' );
	$term_name   = $nature_term->name;
	$term_url    = get_term_link( $nature_term, 'nature' );
	$args        = [
		'post_type'      => 'post',
		'posts_per_page' => 6,
		'meta_query'     => [
			[
				'key'     => 'show_on_nature_menu',
				'value'   => true,
				'compare' => '=',
				'type'    => 'BOOLEAN',
			],
		],
		'tax_query'      => [
			[
				'taxonomy' => 'nature',
				'field'    => 'term_id',
				'terms'    => $nature_term_id,
			],
		],
	];

	$filtered_posts = new WP_Query( $args );

	// If the query has posts, return the post details.
	if ( $filtered_posts->have_posts() ) {
		ob_start();
		?>
			<p class="cat-featured-blogs-title mega-menu-title"><?php echo esc_html( 'Featured Blogs' ); ?></p>
			<div class="cat-featured-blogs-wrapper">
				<?php
				while ( $filtered_posts->have_posts() ) {
					$filtered_posts->the_post();

					$post_id    = get_the_ID();
					$post_title = get_the_title();
					$post_img   = get_the_post_thumbnail_url( $post_id, 'large' );
					$permalink  = get_permalink();
					?>
						<div class="cat-featured-blog-list">
							<a href="<?php echo esc_url( $permalink ); ?>">
								<span class="arrow arrow-small arrow-white" href="#" tabindex="0"></span>
								<div class="cat-featured-blog-image">
									<?php
									if ( ! empty( $post_img ) ) {
										?>
											<img src="<?php echo esc_url( $post_img ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $post_id ) ); ?>">
										<?php
									} else {
										$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
										?>
											<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
										<?php
									}
									?>
								</div>
								<div class="cat-featured-blog-meta">
									<p><?php echo esc_html( $post_title ); ?></p>
								</div>
							</a>
						</div>
					<?php
				}
				?>
			</div>
			<a class="explore-country" href="<?php echo esc_url( $term_url ); ?>">
				<?php echo esc_html( 'Explore ' . $term_name ); ?>
			</a>
		<?php
		$html = ob_get_clean();
		ob_end_clean();
		wp_reset_postdata();
	} else {
		ob_start();
		?>
			<h3 class="no-data-found"><?php echo esc_html( 'No data found.' ); ?></h3>
		<?php
		$html = ob_get_clean();
		ob_end_clean();
	}
	return $html;
}

/**
 * Indicates whether the IP address passed in as the parameter is valid or not.
 *
 * @version 1.0.0
 *
 * @param ip $ip IP Address.
 *
 * @return [boolean]
 */
function validate_ip( $ip ) {
	if ( filter_var(
		$ip,
		FILTER_VALIDATE_IP,
		FILTER_FLAG_IPV4 |
		FILTER_FLAG_IPV6 |
		FILTER_FLAG_NO_PRIV_RANGE |
		FILTER_FLAG_NO_RES_RANGE
	) === false ) {
		return false;
	}
	return true;
}

/**
 * Get IP Address.
 *
 * @version 1.0.0
 */
function btt_get_ip_address() {
	// check for shared internet/ISP IP.
	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) && validate_ip( sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) ) ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_CLIENT_IP'] ) );
	}
	// check for IPs passing through proxies.
	if ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		// check if multiple ips exist in var.
		$iplist = explode( ',', sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) );
		foreach ( $iplist as $ip ) {
			if ( validate_ip( $ip ) ) {
				return $ip;
			}
		}
	}
	if ( isset( $_SERVER['HTTP_X_FORWARDED'] ) && ! empty( $_SERVER['HTTP_X_FORWARDED'] ) && validate_ip( $_SERVER['HTTP_X_FORWARDED'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_FORWARDED'] ) );
	}
	if ( isset( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) && ! empty( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) && validate_ip( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_X_CLUSTER_CLIENT_IP'] ) );
	}
	if ( isset( $_SERVER['HTTP_FORWARDED_FOR'] ) && ! empty( $_SERVER['HTTP_FORWARDED_FOR'] ) && validate_ip( $_SERVER['HTTP_FORWARDED_FOR'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED_FOR'] ) );
	}
	if ( isset( $_SERVER['HTTP_FORWARDED'] ) && ! empty( $_SERVER['HTTP_FORWARDED'] ) && validate_ip( $_SERVER['HTTP_FORWARDED'] ) ) {
		return sanitize_text_field( wp_unslash( $_SERVER['HTTP_FORWARDED'] ) );
	}
	// return unreliable ip since all else failed.
	return sanitize_text_field( wp_unslash( $_SERVER['REMOTE_ADDR'] ) ); //phpcs:ignore
}

/**
 * Set view count in the postmeta for the respective post.
 *
 * @param [integer] $post_id  $post_id ID of single post.
 *
 * @version 1.0.0
 */
function btt_set_view( $post_id ) {
	$stored_ip_addresses = get_post_meta( $post_id, 'view_ip', true );
	if ( $stored_ip_addresses ) {
		if ( count( $stored_ip_addresses ) ) {
			$current_ip = btt_get_ip_address();
			if ( ! in_array( $current_ip, $stored_ip_addresses ) ) {
				$meta_key = 'entry_views';
				if ( empty( get_post_meta( $post_id, $meta_key, true ) ) ) {
					$new_viewed_count = 1;
				} else {
					$view_post_meta   = get_post_meta( $post_id, $meta_key, true );
					$new_viewed_count = $view_post_meta + 1;
				}
				update_post_meta( $post_id, $meta_key, $new_viewed_count );
				$stored_ip_addresses[] = $current_ip;
				update_post_meta( $post_id, 'view_ip', $stored_ip_addresses );
			}
		}
	} else {
		$meta_key = 'entry_views';
		if ( empty( get_post_meta( $post_id, $meta_key, true ) ) ) {
			$new_viewed_count = 1;
		} else {
			$view_post_meta   = get_post_meta( $post_id, $meta_key, true );
			$new_viewed_count = $view_post_meta + 1;
		}
		update_post_meta( $post_id, $meta_key, $new_viewed_count );
		$ip_arr[] = btt_get_ip_address();
		update_post_meta( $post_id, 'view_ip', $ip_arr );
	}
}

/**
 * Set view count in the termmeta for the respective term.
 *
 * @param [integer] $term_id  $term_id ID of single term.
 *
 * @version 1.0.0
 */
function btt_set_term_view( $term_id ) {
	$stored_ip_addresses = get_term_meta( $term_id, 'view_ip', true );

	if ( $stored_ip_addresses ) {
		$current_ip = btt_get_ip_address();
		if ( ! in_array( $current_ip, $stored_ip_addresses ) ) {
			$meta_key         = 'term_views';
			$term_views       = get_term_meta( $term_id, $meta_key, true );
			$new_viewed_count = empty( $term_views ) ? 1 : $term_views + 1;

			update_term_meta( $term_id, $meta_key, $new_viewed_count );

			$stored_ip_addresses[] = $current_ip;
			update_term_meta( $term_id, 'view_ip', $stored_ip_addresses );
		}
	} else {
		$meta_key         = 'term_views';
		$term_views       = get_term_meta( $term_id, $meta_key, true );
		$new_viewed_count = empty( $term_views ) ? 1 : $term_views + 1;

		update_term_meta( $term_id, $meta_key, $new_viewed_count );

		$ip_arr[] = btt_get_ip_address();
		update_term_meta( $term_id, 'view_ip', $ip_arr );
	}
}

/**
 * Get view count in the postmeta for the respective post.
 *
 * @param [integer] $post_id  $post_id ID of single post.
 *
 * @version 1.0.0
 */
function btt_get_views( $post_id = '' ) {
	empty( $post_id ) ? $post_id = get_the_ID() : $post_id;
	$meta_key                    = 'entry_views';
	$view_post_meta              = get_post_meta( $post_id, $meta_key, true );
	return $view_post_meta;
}

/**
 * Handles the AJAX request to load more categories.
 *
 * @version 1.0.0
 */
function btt_load_more_categories() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$page           = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
	$per_page       = isset( $_POST['perPage'] ) ? sanitize_text_field( wp_unslash( $_POST['perPage'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {

		$categories = get_categories(
			[
				'number' => $per_page,
				'offset' => ( $page - 1 ) * $per_page,
			]
		);

		ob_start();

		foreach ( $categories as $category ) {
			$category_image  = get_field( 'category_image', 'category_' . $category->term_id );
			$default_cat_img = get_field( 'no_thumbnail_for_category', 'option' );
			?>
				<a href="<?php echo esc_attr( get_category_link( $category ) ); ?>" class="item">
					<div class="category-item" style="background-image: url('<?php echo esc_attr( ! empty( $category_image ) ? $category_image : $default_cat_img ); ?>');">
						<h4 class="text-white"><?php echo esc_html( $category->name ); ?></h4>
					</div>
				</a>
			<?php
		}

		$html = ob_get_clean();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_load_more_categories', 'btt_load_more_categories' );
add_action( 'wp_ajax_nopriv_btt_load_more_categories', 'btt_load_more_categories' );

/**
 * Handles the AJAX request to load more countries.
 *
 * @version 1.0.0
 */
function btt_load_more_countries() {
	$security_nonce    = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$page              = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
	$per_page          = isset( $_POST['perPage'] ) ? sanitize_text_field( wp_unslash( $_POST['perPage'] ) ) : '';
	$current_parent_id = isset( $_POST['current_parent_id'] ) ? sanitize_text_field( wp_unslash( $_POST['current_parent_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {

		$countries = get_terms(
			[
				'taxonomy' => 'country',
				'child_of' => $current_parent_id,
				'number'   => $per_page,
				'offset'   => ( $page - 1 ) * $per_page,
			]
		);

		ob_start();

		foreach ( $countries as $category ) {
			$category_image  = get_field( 'category_image', 'country_' . $category->term_id );
			$default_cat_img = get_field( 'no_thumbnail_for_category', 'option' );
			?>
				<a href="<?php echo esc_attr( get_category_link( $category ) ); ?>" class="item">
					<div class="category-item" style="background-image: url('<?php echo esc_attr( ! empty( $category_image ) ? $category_image : $default_cat_img ); ?>');">
						<h4 class="text-white"><?php echo esc_html( $category->name ); ?></h4>
					</div>
				</a>
			<?php
		}

		$html = ob_get_clean();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_load_more_countries', 'btt_load_more_countries' );
add_action( 'wp_ajax_nopriv_btt_load_more_countries', 'btt_load_more_countries' );

/**
 * Ajax callback to list country based on parent term.
 *
 * @version 1.0.0
 */
function btt_get_country_by_filter() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$parent_term_id = isset( $_POST['parent_term_id'] ) ? sanitize_text_field( wp_unslash( $_POST['parent_term_id'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$child_terms = get_terms(
			[
				'taxonomy' => 'country',
				'child_of' => $parent_term_id,
				'number'   => 9,
			]
		);
		$total_terms = count(
			get_terms(
				[
					'taxonomy' => 'country',
					'child_of' => $parent_term_id,
				]
			)
		);
		ob_start();
		foreach ( $child_terms as $term ) {
			$term_image      = get_field( 'category_image', 'country_' . $term->term_id );
			$default_cat_img = get_field( 'no_thumbnail_for_category', 'option' );
			?>
			<a href="<?php echo esc_attr( get_term_link( $term, 'country' ) ); ?>" class="item">
				<div class="category-item" style="background-image: url('<?php echo esc_attr( ! empty( $term_image ) ? $term_image : $default_cat_img ); ?>');">
					<h4 class="text-white"><?php echo esc_html( $term->name ); ?></h4>
				</div>
			</a>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html'       => $html,
			'totalTerms' => $total_terms,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_get_country_by_filter', 'btt_get_country_by_filter' );
add_action( 'wp_ajax_nopriv_btt_get_country_by_filter', 'btt_get_country_by_filter' );

/**
 * Ajax callback to submit contact form inquiry.
 *
 * @version 1.0.0
 */
function btt_submit_inquiry_details() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$name           = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
	$email          = isset( $_POST['email'] ) ? sanitize_text_field( wp_unslash( $_POST['email'] ) ) : '';
	$reason         = isset( $_POST['reason'] ) ? sanitize_text_field( wp_unslash( $_POST['reason'] ) ) : '';
	$message        = isset( $_POST['message'] ) ? sanitize_text_field( wp_unslash( $_POST['message'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$errors = []; // Array to store validation errors.

		// Validate name.
		if ( strlen( $name ) < 1 ) {
			$errors['name'] = 'Please enter your name.';
		} elseif ( strlen( $name ) < 2 ) {
			$errors['name'] = 'Name must be at least 2 characters.';
		}

		// Validate email.
		if ( strlen( $email ) < 1 ) {
			$errors['email'] = 'Please enter your email.';
		} elseif ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
			$errors['email'] = 'Please enter a valid email address.';
		}

		// Validate reason.
		if ( strlen( $reason ) < 1 ) {
			$errors['reason'] = 'Please select a reason.';
		}

		// Validate message.
		if ( strlen( $message ) < 1 ) {
			$errors['message'] = 'Please enter a message.';
		} elseif ( strlen( $message ) < 2 ) {
			$errors['message'] = 'Message must be at least 2 characters.';
		}

		if ( ! empty( $errors ) ) {
			// Send validation errors as JSON response.
			wp_send_json_error(
				[
					'message' => 'Validation errors',
					'errors'  => $errors,
				]
			);
			wp_die();
		}

		// Send inquiry details to the admin.
		$inquiry_email_id    = ! empty( get_field( 'inquiry_email_id', 'option' ) ) ? get_field( 'inquiry_email_id', 'option' ) : '';
		$multiple_recipients = [
			$inquiry_email_id,
		];

		$subj = "Inquiry - Contact Us - $name";
		$body = "
			Name: $name
			Email: $email
			Reason: $reason
			Message: $message
		";
		wp_mail( $multiple_recipients, $subj, $body );

		// Prepare the response data.
		$response = [
			'response' => 'Sent successfully.',
		];

		// Send the JSON-encoded response with success status.
		wp_send_json_success( $response );
		wp_die();
	}
}
add_action( 'wp_ajax_btt_submit_inquiry_details', 'btt_submit_inquiry_details' );
add_action( 'wp_ajax_nopriv_btt_submit_inquiry_details', 'btt_submit_inquiry_details' );

/**
 * Ajax callback to filter blogs.
 *
 * @version 1.0.0
 */
function btt_get_filter_blog_post() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$selected_value = isset( $_POST['selected_value'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_value'] ) ) : '';
	$cat_id         = isset( $_POST['cat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_id'] ) ) : '';
	$cat_type       = isset( $_POST['cat_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_type'] ) ) : '';
	$per_page       = isset( $_POST['perPage'] ) ? sanitize_text_field( wp_unslash( $_POST['perPage'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$args = [
			'posts_per_page' => $per_page,
			'post_type'      => 'post',
		];

		if ( $cat_type === 'category' ) {
			$args['category__in'] = $cat_id;
		} elseif ( $cat_type === 'country' ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'country',
					'field'    => 'term_id',
					'terms'    => $cat_id,
				],
			];
		} elseif ( $cat_type === 'nature' ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'nature',
					'field'    => 'term_id',
					'terms'    => $cat_id,
				],
			];
		}

		if ( $selected_value == 'featured' ) {
			$args['tag'] = 'featured';
		}

		$query       = new WP_Query( $args );
		$total_posts = $query->found_posts;

		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();

				get_template_part( 'template-parts/blog/blog', 'listing' );
			}
		} else {
			?>
				<div class="blog-list">
					<h3 class="no-data-find"><?php echo esc_html( 'No data found.' ); ?></h3>
				</div>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html'       => $html,
			'totalPosts' => $total_posts,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_get_filter_blog_post', 'btt_get_filter_blog_post' );
add_action( 'wp_ajax_nopriv_btt_get_filter_blog_post', 'btt_get_filter_blog_post' );

/**
 * Ajax callback to load more blogs.
 *
 * @version 1.0.0
 */
function btt_load_more_blogs() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$selected_value = isset( $_POST['selected_value'] ) ? sanitize_text_field( wp_unslash( $_POST['selected_value'] ) ) : '';
	$cat_id         = isset( $_POST['cat_id'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_id'] ) ) : '';
	$cat_type       = isset( $_POST['cat_type'] ) ? sanitize_text_field( wp_unslash( $_POST['cat_type'] ) ) : '';
	$page           = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
	$per_page       = isset( $_POST['perPage'] ) ? sanitize_text_field( wp_unslash( $_POST['perPage'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$args = [
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_type'      => 'post',
		];

		if ( $cat_type === 'category' ) {
			$args['category__in'] = $cat_id;
		} elseif ( $cat_type === 'country' ) {
			$args['tax_query'] = [
				[
					'taxonomy' => 'country',
					'field'    => 'term_id',
					'terms'    => $cat_id,
				],
			];
		}

		if ( $selected_value == 'featured' ) {
			$args['tag'] = 'featured';
		}

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();

				get_template_part( 'template-parts/blog/blog', 'listing' );
			}
		} else {
			?>
				<h3><?php echo esc_html( 'No data found.' ); ?></h3>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_load_more_blogs', 'btt_load_more_blogs' );
add_action( 'wp_ajax_nopriv_btt_load_more_blogs', 'btt_load_more_blogs' );

/**
 * Ajax callback to load more search results.
 *
 * @version 1.0.0
 */
function btt_load_more_search_results() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$search_value   = isset( $_POST['search_value'] ) ? sanitize_text_field( wp_unslash( $_POST['search_value'] ) ) : '';
	$page           = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
	$per_page       = isset( $_POST['perPage'] ) ? sanitize_text_field( wp_unslash( $_POST['perPage'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$args = [
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_type'      => 'post',
			's'              => $search_value,
		];

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();

				get_template_part( 'template-parts/blog/blog', 'listing' );
			}
		} else {
			?>
				<h3><?php echo esc_html( 'No data found.' ); ?></h3>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_load_more_search_results', 'btt_load_more_search_results' );
add_action( 'wp_ajax_nopriv_btt_load_more_search_results', 'btt_load_more_search_results' );

/**
 * Ajax callback to load more tag results.
 *
 * @version 1.0.0
 */
function btt_load_more_tag_results() {
	$security_nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';
	$tag_id         = isset( $_POST['tag_id'] ) ? sanitize_text_field( wp_unslash( $_POST['tag_id'] ) ) : '';
	$page           = isset( $_POST['page'] ) ? sanitize_text_field( wp_unslash( $_POST['page'] ) ) : '';
	$per_page       = isset( $_POST['perPage'] ) ? sanitize_text_field( wp_unslash( $_POST['perPage'] ) ) : '';
	if ( wp_verify_nonce( $security_nonce, 'auth-token' ) ) {
		$args = [
			'posts_per_page' => $per_page,
			'paged'          => $page,
			'post_type'      => 'post',
			'tag__in'        => [ $tag_id ],
		];

		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			ob_start();
			while ( $query->have_posts() ) {
				$query->the_post();

				get_template_part( 'template-parts/blog/blog', 'listing' );
			}
		} else {
			?>
				<h3><?php echo esc_html( 'No data found.' ); ?></h3>
			<?php
		}
		$html = ob_get_contents();
		ob_end_clean();

		$response = [
			'html' => $html,
		];
		wp_send_json_success( $response );
		wp_die();
	} else {
		wp_send_json_error( 'Nonce Verification Failed..!' );
	}
}
add_action( 'wp_ajax_btt_load_more_tag_results', 'btt_load_more_tag_results' );
add_action( 'wp_ajax_nopriv_btt_load_more_tag_results', 'btt_load_more_tag_results' );

/**
 * Insert ads in between in the content for the mobile.
 *
 * @version 1.0.0
 * @param $content post content
 */
function btt_insert_ads_in_content( $content ) {

	if ( is_single() && ! is_admin() && ! is_feed() ) {
		$positions = [ 3, 8 ];

		$paragraphs = preg_split( '/<\/p>/', $content );

		foreach ( $positions as $key => $position ) {
			if ( isset( $paragraphs[ $position - 1 ] ) ) {
				$custom_class                 = $key == 1 ? 'mobile-ads' : '';
				$ads_shortcode                = '[ads_section_layout layout="vertical-banner" class="' . $custom_class . '"]';
				$paragraphs[ $position - 1 ] .= $ads_shortcode;
			}
		}

		// Rejoin the paragraphs to form the modified content.
		$content = implode( '</p>', $paragraphs );
	}

	return $content;
}
add_filter( 'the_content', 'btt_insert_ads_in_content' );

/**
 * Custom function to remove specific columns from the post admin screen.
 *
 * @param array $columns The array of post columns.
 * @return array Modified array of post columns.
 */
function btt_custom_remove_columns( $columns ) {
	unset( $columns['comments'] );
	unset( $columns['taxonomy-nature'] );
	unset( $columns['tags'] );

	return $columns;
}
add_filter( 'manage_posts_columns', 'btt_custom_remove_columns' );
