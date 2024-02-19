<?php
/**
 * Template for recent highlight section on home page.
 *
 * @version 1.0.0
 */

$featured_posts = get_field( 'featured_posts', $args['page_id'] );
$featured_tag   = get_term_by( 'name', 'featured', 'post_tag' );
$cta_tag_url    = get_term_link( $featured_tag );

if ( isset( $featured_posts ) && ! empty( $featured_posts ) ) {
	?>
		<div class="what-we-written">
			<div class="container">
				<div class="what-we-written-wrapper">
					<div class="what-we-written-title">
						<h2><?php echo esc_html( 'Featured' ); ?></h2>
						<a href="<?php echo esc_url( $cta_tag_url ); ?>">
							<button class="btn">
								<?php echo esc_html( 'View all' ); ?>
							</button>
						</a>
					</div>

					<div class="what-we-written-list">
						<?php
						foreach ( $featured_posts as $featured_post ) {
							$post_id         = $featured_post;
							$post_title      = get_the_title( $post_id );
							$post_img_url    = get_the_post_thumbnail_url( $post_id, 'large' );
							$post_url        = get_permalink( $post_id );
							$post_categories = get_the_category( $post_id );
							$nature_category = get_the_terms( $post_id, 'nature' );
							?>
								<div href="<?php echo esc_url( $post_url ); ?>" class="what-we-written-item">
									<a href="<?php echo esc_url( $post_url ); ?>" class="arrow arrow-medium arrow-white" tabindex="0" aria-label="detail-page"></a>
										<div class="what-we-written-item-img-wrapper">
											<?php
											if ( $post_img_url ) {
												?>
													<img src="<?php echo esc_url( $post_img_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $post_id ) ); ?>" class="img-cover">
												<?php
											} else {
												$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
												?>
													<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail" class="img-cover">
												<?php
											}
											?>
										</div>
									<div class="what-we-written-item-content">
										<?php
										if ( ! empty( $post_categories ) ) {
											$category_url = get_category_link( $post_categories[0] );
											?>
												<a href="<?php echo esc_url( $category_url ); ?>" class="tag"><?php echo esc_html( $post_categories[0]->name ); ?></a>
											<?php
										}
										if ( ! empty( $nature_category ) ) {
											$term_url = get_category_link( $nature_category[0] );
											?>
												<a href="<?php echo esc_url( $term_url ); ?>" class="tag"><?php echo esc_html( $nature_category[0]->name ); ?></a>
											<?php
										}
										?>
										<h4><a href="<?php echo esc_url( $post_url ); ?>"><?php echo esc_html( $post_title ); ?></a></h4>
									</div>
								</div>
							<?php
						}
						wp_reset_postdata();
						?>
					</div>
				</div>
			</div>
		</div>
	<?php
}
?>
