<?php
/**
 * Template for variety topics section on home page.
 *
 * @version 1.0.0
 */

// Get the categories.
$categories = get_categories(
	[
		'orderby' => 'count',
		'order'   => 'DESC',
		'number'  => 6,
	]
);
$cta_page   = get_page_by_title( 'Life' );
$cta_url    = get_permalink( $cta_page->ID );

if ( $categories ) {
	?>
		<div class="discover-variety-topics-wrapper">
			<div class="container-fluid">
				<h2 class="text-center"><?php echo esc_html( 'Explore Popular Categories' ); ?></h2>
				<div class="discover-variety-topics-item-wrapper">
					<ul>
						<?php
						foreach ( $categories as $category ) {
							$category_link = get_category_link( $category->cat_ID );
							?>
								<li>
									<a href="<?php echo esc_attr( $category_link ); ?>"><?php echo esc_html( $category->name ); ?></a>
								</li>
							<?php
						}
						?>
					</ul>
				</div>
				<div class="btn-wrapper">
					<a href="<?php echo esc_url( $cta_url ); ?>">
						<button class="btn btn-green">
							<?php echo esc_html( 'View all Topics' ); ?>
						</button>
					</a>
				</div>
			</div>
		</div>
	<?php
}
?>
