<?php
/**
 * The template for displaying detail of page.
 *
 * @version 1.0.0
 *
 * @package btt
 */
get_header();
$post_id       = get_the_ID();
$thumbnail_url = get_the_post_thumbnail_url( $post_id, 'full' );
// $post_author_id    = get_post_field( 'post_author', $post_id );
// $post_author_name  = get_the_author_meta( 'display_name', $post_author_id );
// $post_author_image = get_avatar( $post_author_id, 96 );
?>
<main class="blog-detail-container">
	<div class="blog-information-wrapper">
		<h1 class="blog-title"><?php esc_html( the_title() ); ?></h1>
		<?php
		if ( $thumbnail_url ) {
			?>
				<img class="blog-image" src="<?php echo esc_url( $thumbnail_url ); ?>" alt="<?php echo esc_attr( btt_get_img_alt( $post_id ) ); ?>">
			<?php
		} else {
			$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
			?>
				<img class="blog-image" src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
			<?php
		}
		?>
	</div>
	<div class="blog-detail-wrapper">
		<?php echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' ); ?>
		<div class="blog-detail-inner-wrapper">
			<div class="blog-detail-left">
				<div class="blog-content">
					<?php the_content(); ?>
				</div>
				<?php
				$post_tags = get_the_tags();
				if ( $post_tags ) {
					?>
						<div class="blog-tags-wrapper">
							<h3><?php echo esc_html( 'Tags' ); ?></h3>
							<div class="blog-tags">
								<?php
								foreach ( $post_tags as $tag ) {
									$tag_url = get_term_link( $tag );
									?>
										<a href="<?php echo esc_url( $tag_url ); ?>" class="tag-name"><?php echo esc_html( $tag->name ); ?></a>
									<?php
								}
								?>
							</div>
						</div>
					<?php
				}
				?>
			</div>
			<div class="blog-detail-right">
				<?php echo do_shortcode( '[ads_section_layout layout="vertical-banner"]' ); ?>
				<?php echo do_shortcode( '[ads_section_layout layout="vertical-banner"]' ); ?>
			</div>
		</div>
	</div>
</main>
<?php
get_footer();

