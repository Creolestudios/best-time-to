<?php
/**
 * Template for home page slider.
 */

$show_posts_with_slider = get_field( 'show_posts_with_slider', $args['page_id'] );

if ( isset( $show_posts_with_slider ) && ! empty( $show_posts_with_slider ) ) {
	?>
		<div class="home-banner-slider-wrapper">
			<div class="home-banner-slider slider">
				<?php
				foreach ( $show_posts_with_slider as $post ) {
					$post_id          = $post->ID;
					$post_title       = $post->post_title;
					$post_permalink   = get_permalink( $post_id );
					$post_content     = $post->post_content;
					$trimmed_content  = wp_trim_words( $post_content, 25, '...' );
					$featured_img_url = get_the_post_thumbnail_url( $post_id );
					?>
						<div class="banner-slider-item">                                                                                                                                                                                                                                                                                                                                                                                                                
							<div class="banner-slider-image-wrapper">
								<?php
								if ( ! empty( $featured_img_url ) ) {
									?>
										<img src="<?php echo esc_url( $featured_img_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $post_id ) ); ?>">
									<?php
								} else {
									$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
									?>
										<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
									<?php
								}
								?>
							</div>
							<div class="sider-content-box-wrapper">
								<div class="sider-content-box">
									<a href="<?php echo esc_url( $post_permalink ); ?>">
										<h1 class="sider-content-title">
											<?php echo esc_html( $post_title ); ?>
										</h1>
									</a>
									<div class="sider-content-paragraph">
										<?php echo esc_html( $trimmed_content ); ?>
									</div>
									<a class="arrow arrow-large arrow-green" href="<?php echo esc_url( $post_permalink ); ?>" aria-label="detail-page"></a>
								</div>
							</div>
						</div>
					<?php
				}
				?>
			</div>
		</div>                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                
	<?php
}
