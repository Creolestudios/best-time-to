<?php
/**
 * Template part to display related posts.
 */

$post_id = get_the_ID();
$terms   = wp_get_post_terms( $post_id, 'country' );

if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {

	$parent_term_id = $parent_term_name = $child_term_id = $child_term_name = null;
	foreach ( $terms as $term ) {
		if ( $term->parent === 0 ) {
			if ( $parent_term_id === null ) {
				$parent_term_id   = $term->term_id;
				$parent_term_name = $term->name;
			}
		}
		if ( $term->parent != 0 ) {
			if ( $child_term_id === null ) {
				$child_term_id   = $term->term_id;
				$child_term_name = $term->name;
			}
		}
	}

	$related_posts_child  = [];
	$related_posts_parent = [];

	// Query related posts for child terms
	if ( ! empty( $child_term_id ) ) {
		$child_args = [
			'post_type'      => 'post',
			'posts_per_page' => 4,
			'tax_query'      => [
				[
					'taxonomy' => 'country',
					'field'    => 'term_id',
					'terms'    => $child_term_id,
				],
			],
			'post__not_in'   => [ $post_id ],
		];

		$related_posts_child_query = new WP_Query( $child_args );

		// Store the results in the $related_posts_child array
		if ( $related_posts_child_query->have_posts() ) {
			while ( $related_posts_child_query->have_posts() ) {
				$related_posts_child_query->the_post();
				$related_posts_child[] = get_the_ID();
			}
		}

		wp_reset_postdata();
	}


	if ( isset( $parent_term_id ) ) {
		$parent_args = [
			'post_type'      => 'post',
			'posts_per_page' => 4,
			'tax_query'      => [
				[
					'taxonomy' => 'country',
					'field'    => 'term_id',
					'terms'    => $parent_term_id,
				],
				[
					'taxonomy' => 'country',
					'field'    => 'term_id',
					'terms'    => $child_term_id,
					'operator' => 'NOT IN',
				],
			],
			'post__not_in'   => [ $post_id ],
		];

		$related_posts_parent_query = new WP_Query( $parent_args );

		// Store the results in the $related_posts_parent array
		if ( $related_posts_parent_query->have_posts() ) {
			while ( $related_posts_parent_query->have_posts() ) {
				$related_posts_parent_query->the_post();
				$related_posts_parent[] = get_the_ID();
			}
		}

		wp_reset_postdata();
	}

	$related_posts = array_merge( $related_posts_child, $related_posts_parent );

	// Display related posts.
	if ( ! empty( $related_posts ) ) {
		?>
			<div class="related-destinations-wrapper">
				<h2><?php echo esc_html( 'Explore other ' . $parent_term_name . ' Destinations' ); ?></h2>
				<div class="related-destination-iteam">
					<?php
					foreach ( $related_posts as $key => $post ) {
						if ( $key < 4 ) {
							$related_post_id       = $post;
							$related_thumbnail_url = get_the_post_thumbnail_url( $related_post_id, 'large' );
							$related_post_url      = get_permalink( $related_post_id );
							$related_post_title    = get_the_title();
							?>
								<div class="related-destination">
									<div class="item">
										<?php
										if ( $related_thumbnail_url ) {
											?>
												<img class="destination-image" src="<?php echo esc_url( $related_thumbnail_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $related_post_id ) ); ?>">
											<?php
										} else {
											$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
											?>
												<img class="destination-image" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
											<?php
										}

										$categories = get_the_category();
										if ( ! empty( $categories ) ) {
											foreach ( $categories as $category ) {
												$category_url = get_category_link( $category );
												?>
													<a href="<?php echo esc_url( $category_url ); ?>" class="tag"><?php echo esc_html( $category->name ); ?></a>
												<?php
											}
										}
										?>
										<a href="<?php echo esc_url( $related_post_url ); ?>" class="arrow arrow-small arrow-white" tabindex="0" aria-label="detail-page"></a>

										<h5 class="text-white"><a href="<?php echo esc_url( $related_post_url ); ?>"><?php echo esc_html( $related_post_title ); ?></a></h5>
									</div>
								</div>
							<?php
						}
					}
					?>
				</div>
			</div>
		<?php
	}
}
