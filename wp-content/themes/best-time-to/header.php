<?php
/**
 * Header section of theme.
 *
 * @version 1.0.0
 *
 * @package btt
 */

$default_logo_url = BTT_THEME_URI . '/dist/assets/images/logo.svg';
$site_logo_url    = ! empty( get_field( 'site_logo', 'option' ) ) ? get_field( 'site_logo', 'option' ) : $default_logo_url;
?>

<!Doctype html>
<html <?php language_attributes(); ?>>

<head>
	<link rel="icon" href="<?php echo esc_url( BTT_THEME_URI . '/dist/assets/images/fav-icon.svg' ); ?>">
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<!-- Google tag (gtag.js) -->
	<script async src="https://www.googletagmanager.com/gtag/js?id=G-37K6YSFQJF"></script>
	<script>
	window.dataLayer = window.dataLayer || [];
	function gtag(){dataLayer.push(arguments);}
	gtag('js', new Date());
	gtag('config', 'G-37K6YSFQJF');
	</script>
	<?php wp_head(); ?>
</head>

<body>
	<?php do_action( 'wp_body_open' ); ?>
	<header>
		<div class="container">
			<div class="header-main">
				<div class="header-left">
					<a class="logo" href="<?php echo esc_url( site_url() ); ?>">
						<img src="<?php echo esc_attr( $site_logo_url ); ?>" alt="site-logo">
					</a>
					<div class="humberger-menu">
						<span></span>
					</div>
					<div class="mobile-menu">
						<ul>
							<li>
								<label>	
									<span><?php echo esc_html( 'Travel' ); ?></span>
								</label>
								<?php
								$parent_terms = get_terms(
									[
										'taxonomy' => 'country',
										'parent'   => 0,
										'orderby'  => 'name',
										'order'    => 'ASC',
									]
								);
								if ( $parent_terms ) {
									?>
									<ul>
										<?php
										foreach ( $parent_terms as $parent_term ) {
											$child_terms = get_terms(
												[
													'taxonomy' => 'country',
													'parent'   => $parent_term->term_id,
													'orderby'  => 'name',
													'order'    => 'ASC',
												]
											);
											?>
												<li>
													<label>
														<span><?php echo esc_html( $parent_term->name ); ?></span>
													</label>
													<ul>
														<?php
														foreach ( $child_terms as $child_term ) {
															?>
																<li><a href="<?php echo esc_url( get_term_link( $child_term ) ); ?>"><?php echo esc_html( $child_term->name ); ?></a></li>
															<?php
														}
														?>
														<li><a href="<?php echo esc_url( get_term_link( $parent_term ) ); ?>"><?php echo esc_html( 'Explore ' . $parent_term->name ); ?></a></li>
													</ul>
												</li>
											<?php
										}
										?>
									</ul>
									<?php
								}
								?>
							</li>
							<li>
								<label>	
									<span><?php echo esc_html( 'Life' ); ?></span>
								</label>
								<?php
								$categories = get_categories(
									[
										'orderby'    => 'name',
										'order'      => 'ASC',
										'hide_empty' => false,
									]
								);
								if ( $categories ) {
									?>
									<ul>
										<?php
										foreach ( $categories as $category ) {
											$category_name = $category->name;
											$category_link = get_category_link( $category->term_id );
											?>
												<li>
													<label>	
														<span><?php echo esc_html( $category_name ); ?></span>
													</label>
													<ul>
														<li><a href="<?php echo esc_url( $category_link ); ?>"><?php echo esc_html( 'Explore ' . $category_name ); ?></a></li>
													</ul>
												</li>
											<?php
										}
										?>
									</ul>
									<?php
								}
								?>
							</li>
							<li>
								<label>	
									<span><?php echo esc_html( 'Nature' ); ?></span>
								</label>
								<?php
								$nature_terms = get_terms(
									[
										'taxonomy'   => 'nature',
										'hide_empty' => true,
									]
								);
								if ( $nature_terms ) {
									?>
									<ul>
										<?php
										foreach ( $nature_terms as $category ) {
											$nature_term_name = $category->name;
											$nature_term_link = get_term_link( $category->term_id );
											?>
												<li>
													<label>	
														<span><?php echo esc_html( $nature_term_name ); ?></span>
													</label>
													<ul>
														<li><a href="<?php echo esc_url( $nature_term_link ); ?>"><?php echo esc_html( 'Explore ' . $nature_term_name ); ?></a></li>
													</ul>
												</li>
											<?php
										}
										?>
									</ul>
									<?php
								}
								?>
							</li>
							<?php
							$mobile_items = wp_get_nav_menu_items( 'header-mobile-menu' );
							if ( $mobile_items ) {
								foreach ( $mobile_items as $item ) {
									?>
										<li>
											<label>	
												<span><a href="<?php echo esc_url( $item->url ); ?>"><?php echo esc_html( $item->title ); ?></a></span>
											</label>
										</li>
									<?php
								}
							}
							?>
						</ul>
					</div>
					<?php
					wp_nav_menu(
						[
							'theme_location' => 'header-menu',
							'container'      => false,
							'items_wrap'     => '<ul class="header-menu-links-wrapper">%3$s</ul>',
							'walker'         => new Custom_Header_Menu_Walker(),
						]
					);
					?>
				</div>

				<div class="header-right">
					<div class="header-search-input">
						<input id="btt-search" type="text" placeholder="Search..." />
						<i class="close"></i>
					</div>
					<a class="btn" href="<?php echo esc_attr( site_url() . '/contact-us' ); ?>"><?php echo esc_html( 'Contact us' ); ?></a>
				</div>
				
				<!-- Include country mega menu  -->
				<?php get_template_part( 'template-parts/header/country', 'mega-menu' ); ?>

				<!-- Include category mega menu  -->
				<?php get_template_part( 'template-parts/header/category', 'mega-menu' ); ?>

				<!-- Include nature mega menu  -->
				<?php get_template_part( 'template-parts/header/nature', 'mega-menu' ); ?>
			</div>
		</div>
	</header>
