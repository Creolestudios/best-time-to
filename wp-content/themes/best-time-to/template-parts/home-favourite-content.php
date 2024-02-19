<?php
/**
 * Template for home favourite content.
 *
 * @version 1.0.0
 */

$favorite_posts  = get_field( 'favorite_posts', $args['page_id'] );
$favorite_tag    = get_term_by( 'name', 'favorites', 'post_tag' );
$cta_fav_tag_url = get_term_link( $favorite_tag );

if ( isset( $favorite_posts ) && ! empty( $favorite_posts ) ) {
	$first_post_id = $favorite_posts[0];
	?>
	<div class="favourite-content-main-wrapper">
		<div class="container">
		<div class="favourate-content-inner">
		<h2 class="favourite-content-title text-center">
			<?php echo esc_html( 'Discover Our Favorites' ); ?>
		</h2>
		<a class="favourite-content-view-all text-right" href="<?php echo esc_url( $cta_fav_tag_url ); ?>">
			<button class="btn"><?php echo esc_html( 'View all' ); ?></button>
		</a>
		<div class="favourite-content-inner-wrapper">
			<?php
			$featured_image_url    = get_the_post_thumbnail_url( $first_post_id, 'large' );
			$featured_categories   = get_the_category( $first_post_id );
			$featured_nature_terms = get_the_terms( $first_post_id, 'nature' );
			$featured_post_title   = get_the_title( $first_post_id );
			$featured_post_url     = get_permalink( $first_post_id );
			$featured_post_content = get_post_field( 'post_content', $first_post_id );
			$featured_trim_content = wp_trim_words( $featured_post_content, 50, '...' );
			?>
			<div class="favourite-content-upper-wrapper">
				<div class="favourite-content-information">
					<div class="favourite-content-img">
						<a href="<?php echo esc_url( $featured_post_url ); ?>">
							<?php
							if ( ! empty( $featured_image_url ) ) {
								?>
									<img src="<?php echo esc_url( $featured_image_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $first_post_id ) ); ?>">
								<?php
							} else {
								$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
								?>
									<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
								<?php
							}
							?>
						</a>
						<div class="category-wrapper">
						<?php
						if ( ! empty( $featured_categories ) ) {
							$category_url = get_category_link( $featured_categories[0] );
							?>
								<a href="<?php echo esc_url( $category_url ); ?>" class="favourite-content-category"><?php echo esc_html( $featured_categories[0]->name ); ?></a>
							<?php
						}
						if ( ! empty( $featured_nature_terms ) ) {
							$nature_term_url = get_category_link( $featured_nature_terms[0] );
							?>
								<a href="<?php echo esc_url( $nature_term_url ); ?>" class="favourite-content-category"><?php echo esc_html( $featured_nature_terms[0]->name ); ?></a>
							<?php
						}
						?>
						</div>
					</div>
					<div class="favourite-content-detail">
						<a href="<?php echo esc_url( $featured_post_url ); ?>" class="favourite-content-name">
							<h4><?php echo esc_html( $featured_post_title ); ?></h4>
						</a>
						<p class="favourite-content-desc"><?php echo esc_html( $featured_trim_content ); ?></p>
						<a href="<?php echo esc_url( $featured_post_url ); ?>" class="arrow arrow-medium arrow-green" aria-label="detail-page"></a>
					</div>
				</div>
			</div>
			<div class="favourite-content-below-wrapper">
				<?php
				for ( $i = 1; $i < count( $favorite_posts ); $i++ ) {
					$post_id      = $favorite_posts[ $i ];
					$image_url    = get_the_post_thumbnail_url( $post_id, 'large' );
					$categories   = get_the_category( $post_id );
					$nature_terms = get_the_terms( $post_id, 'nature' );
					$post_title   = get_the_title( $post_id );
					$post_url     = get_permalink( $post_id );
					$post_content = get_post_field( 'post_content', $first_post_id );
					$trim_content = wp_trim_words( $post_content, 50, '...' );
					?>
						<div class="favourite-content-information">
							<div class="favourite-content-img">
								<a href="<?php echo esc_url( $post_url ); ?>">
									<?php
									if ( ! empty( $image_url ) ) {
										?>
											<img src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $first_post_id ) ); ?>">
										<?php
									} else {
										$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
										?>
											<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
										<?php
									}
									?>
								</a>
								<div class="category-wrapper">
									<?php
									if ( ! empty( $categories ) ) {
										$category_url = get_category_link( $categories[0] );
										?>
											<a href="<?php echo esc_url( $category_url ); ?>" class="favourite-content-category"><?php echo esc_html( $categories[0]->name ); ?></a>
										<?php
									}
									if ( ! empty( $nature_terms ) ) {
										$category_url = get_category_link( $nature_terms[0] );
										?>
											<a href="<?php echo esc_url( $category_url ); ?>" class="favourite-content-category"><?php echo esc_html( $nature_terms[0]->name ); ?></a>
										<?php
									}
									?>
								</div>
							</div>
							<div class="favourite-content-detail">
								
								<a href="<?php echo esc_url( $post_url ); ?>" class="favourite-content-name">
									<h6><?php echo esc_html( $post_title ); ?></h6>
								</a>
								<p class="favourite-content-desc"><?php echo esc_html( $trim_content ); ?></p>
								<a class="read-more" href="<?php echo esc_url( $post_url ); ?>"><?php echo esc_html( 'Read More' ); ?></a>
							</div>
						</div>
					<?php
				}
				?>
			</div>
			<?php wp_reset_postdata(); ?>
		</div>   
		</div>
		</div> 
	</div>    
	<?php
}
?>
