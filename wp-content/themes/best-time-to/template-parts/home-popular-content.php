<?php
/**
 * Template for home popular content.
 */

$popular_posts = get_field( 'popular_posts', $args['page_id'] );

if ( isset( $popular_posts ) && ! empty( $popular_posts ) ) {
	$first_post_id = $popular_posts[0];
	?>
	<div class="popular-content-main-wrapper">
		<div class="container">
			<h2 class="popular-content-title text-white text-center"><?php echo esc_html( 'Explore the Most Popular' ); ?></h2>
			<div class="popular-content-inner-wrapper">
				<?php
				$featured_image_url    = get_the_post_thumbnail_url( $first_post_id, 'large' );
				$featured_categories   = get_the_category( $first_post_id );
				$featured_post_title   = get_the_title( $first_post_id );
				$featured_post_url     = get_permalink( $first_post_id );
				$featured_post_content = get_post_field( 'post_content', $first_post_id );
				$featured_trim_content = wp_trim_words( $featured_post_content, 50, '...' );
				?>
				<div class="popular-content-left-wrapper">
					<div class="popular-content-information">
						<div class="popular-content-img">
							<div href="<?php echo esc_url( $featured_post_url ); ?>">
								<?php
								if ( ! empty( $featured_image_url ) ) {
									?>
										<img class="img-cover" src="<?php echo esc_url( $featured_image_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $first_post_id ) ); ?>">
									<?php
								} else {
									$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
									?>
										<img class="img-cover" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
									<?php
								}
								?>
							</div>
							<?php
							if ( ! empty( $featured_categories ) ) {
								$category_url = get_category_link( $featured_categories[0] );
								?>
									<a href="<?php echo esc_url( $category_url ); ?>" class="category-label-bg popular-content-category"><?php echo esc_html( $featured_categories[0]->name ); ?></a>
								<?php
							}
							?>
							<a href="<?php echo esc_url( $featured_post_url ); ?>" class="arrow arrow-medium arrow-white" tabindex="0" aria-label="detail-page"></a>
						</div>
						<div class="popular-content-detail">
							<a href="<?php echo esc_url( $featured_post_url ); ?>" class="popular-content-name">
								<h3><?php echo esc_html( $featured_post_title ); ?></h3>
							</a>
							<p class="popular-content-desc"><?php echo esc_html( $featured_trim_content ); ?></p>
						</div>
					</div>
				</div>
				<div class="popular-content-right-wrapper">
					<?php
					for ( $i = 1; $i < count( $popular_posts ); $i++ ) {
						$post_id      = $popular_posts[ $i ];
						$image_url    = get_the_post_thumbnail_url( $post_id, 'large' );
						$categories   = get_the_category( $post_id );
						$nature_terms = get_the_terms( $post_id, 'nature' );
						$post_title   = get_the_title( $post_id );
						$post_url     = get_permalink( $post_id );
						?>
							<div class="popular-content-information">
								<div class="popular-content-img">
									<div href="<?php echo esc_url( $post_url ); ?>">
										<?php
										if ( ! empty( $image_url ) ) {
											?>
												<img class="img-cover" src="<?php echo esc_url( $image_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $first_post_id ) ); ?>">
											<?php
										} else {
											$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
											?>
												<img class="img-cover"src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
											<?php
										}
										?>
									</div>
									<a href="<?php echo esc_url( $post_url ); ?>" class="arrow arrow-small arrow-white" tabindex="0" aria-label="detail-page"></a>
								</div>
								<div class="popular-content-detail">
									<?php
									if ( ! empty( $categories ) ) {
										$category_url = get_category_link( $categories[0] );
										?>
											<a href="<?php echo esc_url( $category_url ); ?>" class="category-label-transparent popular-content-category"><?php echo esc_html( $categories[0]->name ); ?></a>
										<?php
									}
									if ( ! empty( $nature_terms ) ) {
										$term_url = get_category_link( $nature_terms[0] );
										?>
											<a href="<?php echo esc_url( $term_url ); ?>" class="category-label-transparent popular-content-category"><?php echo esc_html( $nature_terms[0]->name ); ?></a>
										<?php
									}
									?>
									<a href="<?php echo esc_url( $post_url ); ?>" class="popular-content-name">
										<h6 class="text-white"><?php echo esc_html( $post_title ); ?></h6>
									</a>
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
	<?php
}
?>
