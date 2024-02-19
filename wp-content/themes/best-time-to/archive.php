<?php
/**
 * Theme archive page.
 *
 * @version 1.0.0
 * @package btt
 */
get_header();

$default_cat_img = get_field( 'no_thumbnail_for_category', 'option' );

// For default category.
if ( is_category() ) {
	$category         = get_queried_object();
	$category_type    = 'category';
	$current_category = $category->name;
	$category_id      = $category->term_id;
	$category_image   = get_field( 'category_image', 'category_' . $category_id );

	// Getting parent term (if exists)
	if ( $category->parent !== 0 ) {
		$parent_category      = get_category( $category->parent );
		$parent_category_name = $parent_category->name;
		$parent_category_id   = $parent_category->term_id;
	}

	// For related category.
	$related_terms   = get_terms(
		[
			'taxonomy' => 'category',
			'orderby'  => 'rand',
			'number'   => 4,
			'exclude'  => $category_id,
		]
	);
	$highlight_title = 'Life';
}

// For country category.
if ( is_tax( 'country' ) ) {
	$term             = get_queried_object();
	$category_type    = 'country';
	$current_category = $term->name;
	$category_id      = $term->term_id;
	$category_image   = get_field( 'category_image', 'country_' . $category_id );

	// Getting parent term (if exists)
	if ( $term->parent !== 0 ) {
		$parent_category      = get_term( $term->parent, 'country' );
		$parent_category_name = $parent_category->name;
		$parent_category_id   = $parent_category->term_id;
	}

	// For related category.
	if ( $parent_category_id ) {
		$related_terms   = get_terms(
			[
				'taxonomy' => $category_type,
				'child_of' => $parent_category_id,
				'number'   => 4,
				'exclude'  => $category_id,
			]
		);
		$parent_term     = get_term( $parent_category_id, 'country' );
		$highlight_title = $parent_term->name;
	} else {
		$related_terms   = get_terms(
			[
				'taxonomy' => $category_type,
				'child_of' => $category_id,
				'number'   => 4,
			]
		);
		$highlight_title = $current_category;
	}
}

// For nature category.
if ( is_tax( 'nature' ) ) {
	$term             = get_queried_object();
	$category_type    = 'nature';
	$current_category = $term->name;
	$category_id      = $term->term_id;
	$category_image   = get_field( 'category_image', 'nature_' . $category_id );

	// For related category.
	$related_terms   = get_terms(
		[
			'taxonomy' => 'nature',
			'orderby'  => 'rand',
			'number'   => 4,
			'exclude'  => $category_id,
		]
	);
	$highlight_title = 'Nature';
}
btt_set_term_view( $category_id );
?>

<div class="blog-listing-wrapper">
	<div class="banner-section" style="background-image: url('<?php echo esc_attr( ! empty( $category_image ) ? $category_image : $default_cat_img ); ?>');">
			<div class="banner-content">
				<h5 class="text-white"><?php echo esc_html( $parent_category_name ); ?></h5>
				<h1 class="text-white"><?php echo esc_html( $current_category ); ?></h1>
				<div class="search-input">
					<input id="btt-blog-search" type="text" placeholder="Search...">
				</div>
				<div class="tag-wrapper">
					<div class="title text-white"><?php echo esc_html( 'Popular Tags :' ); ?></div>
					<ul>
					<?php
						$default_categories = get_categories(
							[
								'taxonomy' => 'category',
								'orderby'  => 'name',
								'order'    => 'ASC',
							]
						);

						$popularity_data = [];

						foreach ( $default_categories as $category ) {
							$view_ip_data = get_term_meta( $category->term_id, 'view_ip', true );
							$popularity   = $view_ip_data ? count( array_unique( $view_ip_data ) ) : 0;

							$popularity_data[ $category->term_id ] = $popularity;
						}

						// Sort categories by popularity.
						arsort( $popularity_data );

						// Display top viewed categories
						$count = 0;
						foreach ( $popularity_data as $cat_id => $popularity ) {
							if ( $count >= 6 ) {
								break; // Display only top 6 categories
							}

							$category     = get_category( $cat_id );
							$category_url = get_category_link( $cat_id );
							?>
								<li><a href="<?php echo esc_url( $category_url ); ?>"><?php echo esc_html( $category->name ); ?></a></li>
							<?php

							$count++;
						}
						?>
					</ul>
				</div>
			</div>
	</div>

	<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>

	<div class="blog-listing">
		<div class="container">
			<div class="filter">
				<div class="filter-wrap">
					<div class="filter-btn">
						<?php echo esc_html( 'Filter' ); ?>
						<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/filter.png' ); ?>" alt="filter-images" />
					</div>
					<ul class="filter-dropdown">
						<li>
							<label>
								<input type="radio" name="check" value="recent" class="filter-check"  checked="checked" />
								<span><?php echo esc_html( 'Recently Added' ); ?></span>
							</label>
						</li>
						<li>
							<label>
								<input type="radio" name="check" value="featured" class="filter-check" />
								<span><?php echo esc_html( 'Featured' ); ?></span>
							</label>    
						</li>
					</ul>
				</div>
			</div>

			<?php
			// Display blog posts associated with the current category.
			$category_args = [
				'post_type'      => 'post',
				'posts_per_page' => 9,
			];

			if ( is_category() ) {
				$category_args['category_name'] = $current_category;
			} elseif ( is_tax( 'country' ) ) {
				$category_args['tax_query'] = [
					[
						'taxonomy'         => 'country',
						'field'            => 'slug',
						'terms'            => $term->slug,
						'include_children' => true,
					],
				];
			} elseif ( is_tax( 'nature' ) ) {
				$category_args['tax_query'] = [
					[
						'taxonomy'         => 'nature',
						'field'            => 'slug',
						'terms'            => $term->slug,
						'include_children' => true,
					],
				];
			}

			$category_posts = new WP_Query( $category_args );
			$total_posts    = $category_posts->found_posts;
			if ( $category_posts->have_posts() ) {
				?>
					<div class="blog-list" data-cat-type="<?php echo esc_attr( $category_type ); ?>" data-cat-id="<?php echo esc_attr( $category_id ); ?>" data-blog-track="<?php echo esc_attr( $total_posts ); ?>">
						<?php
						while ( $category_posts->have_posts() ) {
							$category_posts->the_post();

							get_template_part( 'template-parts/blog/blog', 'listing' );
						}
						?>
					</div>
				<?php
			} else {
				?>
					<div class="blog-list" data-cat-type="<?php echo esc_attr( $category_type ); ?>" data-cat-id="<?php echo esc_attr( $category_id ); ?>" data-blog-track="<?php echo esc_attr( $total_posts ); ?>">
						<h3><?php echo esc_html( 'No data found.' ); ?></h3>
					</div>
				<?php
			}
			wp_reset_postdata();
			?>
			<div class="loader blog-list-loader" style="display:none">
				<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
			</div>
			<div class="btn load-more-blogs"><?php echo esc_html( 'Load more' ); ?></div>
		</div>
	</div>

	<div class="highlights-wrapper">
		<div class="container">
			<?php
			if ( $related_terms ) {
				?>
					<h2><?php echo esc_html( $highlight_title . ' Highlights' ); ?></h2>
					<div class="highlight-list">
						<?php
						foreach ( $related_terms as $term ) {
							$term_id    = $term->term_id;
							$term_image = get_field( 'category_image', $category_type . '_' . $term_id );
							$term_link  = get_term_link( $term_id, $category_type );
							?>
								<a href="<?php echo esc_url( $term_link ); ?>" class="item">
									<div class="category-item">
									<img src="<?php echo esc_attr( ! empty( $term_image ) ? $term_image : $default_cat_img ); ?>" alt="highlights-images" />
										<h4 class="text-white"><?php echo esc_html( $term->name ); ?></h4>
									</div>
								</a>
							<?php
						}
						?>
					</div>
				<?php
			}
			?>
		</div>
	</div>
</div>

<?php
get_footer();
?>
