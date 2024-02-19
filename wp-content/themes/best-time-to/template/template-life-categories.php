<?php
/**
 * Template Name: Life Categories
 *
 * Custom template for the category list.
 *
 * @version 1.0.0
 * @package btt
 */
get_header();
?>

<main>
	<?php get_template_part( 'template-parts/header/page', 'introduction' ); ?>
	
	<div class="category-wrapper">
		<h5><?php echo esc_html( 'Categories' ); ?></h5>
		<div class="category-item-list" data-track="<?php echo esc_attr( count( get_categories() ) ); ?>">
			<?php
			$categories = get_categories( [ 'number' => 9 ] );

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
			?>
		</div>
		<div class="loader life-category-loader" style="display:none">
			<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
		</div>
		<div class="btn load-more-category"><?php echo esc_html( 'Load more' ); ?></div>
	</div>
</main>    

<?php
get_footer();

