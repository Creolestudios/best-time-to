<?php
/**
 * Template for page introduction.
 */

$page_title       = ! empty( get_field( 'page_title', get_the_ID() ) ) ? get_field( 'page_title', get_the_ID() ) : '';
$background_image = ! empty( get_field( 'page_cover_image', get_the_ID() ) ) ? get_field( 'page_cover_image', get_the_ID() ) : '';
$default_img_url  = BTT_THEME_URI . '/dist/assets/images/about-us-banner.png';

if ( ! empty( $background_image ) ) {
	$background_img_url = ! empty( $background_image['url'] ) ? $background_image['url'] : '';
	$background_img_alt = ! empty( $background_image['alt'] ) ? $background_image['alt'] : $background_image['name'];
}
?>

<div class="intro-wrapper">
	<img src="<?php echo esc_url( ! empty( $background_img_url ) ? $background_img_url : $default_img_url ); ?>" 
		alt="<?php echo esc_attr( ! empty( $background_img_alt ) ? $background_img_alt : 'about-us-banner' ); ?>">
	<h1><?php echo esc_html( $page_title ); ?></h1>
</div>
