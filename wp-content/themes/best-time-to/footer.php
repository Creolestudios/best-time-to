<?php
/**
 * Footer section of theme.
 *
 * @version 1.0.0
 *
 * @package btt
 */

$default_logo_url = BTT_THEME_URI . '/dist/assets/images/logo.svg';
$site_logo_url    = ! empty( get_field( 'site_logo', 'option' ) ) ? get_field( 'site_logo', 'option' ) : $default_logo_url;
$copyright_text   = ! empty( get_field( 'footer_copyright_text', 'option' ) ) ? get_field( 'footer_copyright_text', 'option' ) : $default_logo_url;
?>
<div class="container">
	<div class="footer-wrapper">
		<div class="footer-top">
			<div class="footer-logo-wrapper">
				<a href="<?php echo esc_url( site_url() ); ?>">
					<img src="<?php echo esc_url( $site_logo_url ); ?>" alt="footer-logo">
				</a>
			</div>
			<div class="footer-link-main">
				<ul class="footer-link-wrapper">
					<?php
					// Get the menu items for the "footer-main-menu" location.
					$footer_menu_items = wp_get_nav_menu_items( 'footer-main-menu' );

					if ( $footer_menu_items ) {
						foreach ( $footer_menu_items as $menu_item ) {
							?>
								<li class="footer-link">
									<a href="<?php echo esc_url( $menu_item->url ); ?>">
										<?php echo esc_html( $menu_item->title ); ?>
									</a>
								</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
		<div class="footer-bottom">
			<div class="copyright-text">
				<?php echo wp_kses_post( $copyright_text ); ?>
			</div>
			<div class="footer-bottom-links-main">
				<ul class="footer-bottom-links-wrapper">
					<?php
					// Get the menu items for the "footer-bottom-menu" location.
					$footer_bottom_items = wp_get_nav_menu_items( 'footer-bottom-menu' );

					if ( $footer_bottom_items ) {
						foreach ( $footer_bottom_items as $menu_item ) {
							?>
								<li class="footer-bottom-links">
									<a href="<?php echo esc_url( $menu_item->url ); ?>">
										<?php echo esc_html( $menu_item->title ); ?>
									</a>
								</li>
							<?php
						}
					}
					?>
				</ul>
			</div>
		</div>
	</div>
</div>
</body>
</html>
