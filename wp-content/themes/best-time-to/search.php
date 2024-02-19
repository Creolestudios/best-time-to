<?php
/**
 * Theme search template.
 *
 * @package btt
 * @version 1.0.0
 */
get_header();
?>

<main class="search-template">

	<div class="intro-wrapper">
		<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/search-result-banner.png' ); ?>" alt="search-banner">
		<h1><?php echo esc_html__( 'Search Results for: ' ), get_search_query(); ?></h1>
	</div>
	
	<div class="blog-listing search-result-listing">
		<div class="container">
			<?php
			$search_query = new WP_Query(
				[
					'post_type'      => 'post',
					's'              => get_search_query(),
					'posts_per_page' => 9,
				]
			);
			if ( $search_query->have_posts() ) {
				?>
					<div class="blog-list search-list" data-blog-track="<?php echo esc_attr( $search_query->found_posts ); ?>" data-search="<?php echo esc_attr( get_search_query() ); ?>">
					<?php
					while ( $search_query->have_posts() ) {
						$search_query->the_post();

						get_template_part( 'template-parts/blog/blog', 'listing' );
					}
					?>
					</div>
				<?php
					wp_reset_postdata();
			} else {
				?>
					<div class="blog-list search-list" data-blog-track="<?php echo esc_attr( $search_query->found_posts ); ?>" data-search="<?php echo esc_attr( get_search_query() ); ?>">
						<h3><?php echo esc_html( 'No data found.' ); ?></h3>
					</div>
				<?php
			}
			?>
			<div class="loader search-list-loader" style="display:none">
				<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
			</div>
			<div class="btn load-more-search"><?php echo esc_html( 'Load more' ); ?></div>
		</div>
	</div>
</main>

<?php get_footer(); ?>
