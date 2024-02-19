<?php
/**
 * Theme tag template.
 *
 * @package btt
 * @version 1.0.0
 */
get_header();
?>

<main class="tag-listing-wrapper">

	<div class="intro-wrapper">
		<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/tag-list-banner.png' ); ?>" alt="tag-list-banner">
		<h1><?php printf( esc_html__( 'Tag: %s', 'btt' ), single_tag_title( '', false ) ); ?></h1>
	</div>

	<div class="blog-listing tag-result-listing">
		<div class="container">
			<?php
			$tag_id    = get_queried_object_id();
			$args      = [
				'post_type'      => 'post',
				'tag__in'        => [ $tag_id ],
				'posts_per_page' => 9,
			];
			$tag_query = new WP_Query( $args );
			if ( $tag_query->have_posts() ) {
				?>
					<div class="blog-list tag-list" data-blog-track="<?php echo esc_attr( $tag_query->found_posts ); ?>" data-tag-id="<?php echo esc_attr( $tag_id ); ?>">
					<?php
					while ( $tag_query->have_posts() ) {
						$tag_query->the_post();

						get_template_part( 'template-parts/blog/blog', 'listing' );
					}
					?>
					</div>
				<?php
					wp_reset_postdata();
			} else {
				?>
					<div class="blog-list tag-list" data-blog-track="<?php echo esc_attr( $tag_query->found_posts ); ?>" data-tag-id="<?php echo esc_attr( $tag_id ); ?>">
						<h3><?php echo esc_html( 'No data found.' ); ?></h3>
					</div>
				<?php
			}
			?>
			<div class="loader tag-list-loader" style="display:none">
				<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
			</div>
			<div class="btn load-more-tag"><?php echo esc_html( 'Load more' ); ?></div>
		</div>
	</div>
</main>

<?php
get_footer();
