<?php
/**
 * Template Name: About Us Page
 *
 * Custom template for the about page.
 *
 * @version 1.0.0
 * @package btt
 */
get_header();

// Getting meta information.
$default_founder_img = BTT_THEME_URI . '/dist/assets/images/author-image.png';
$description         = ! empty( get_field( 'description' ) ) ? get_field( 'description' ) : '';
$about_founder_title = ! empty( get_field( 'about_founder_title' ) ) ? get_field( 'about_founder_title' ) : 'About Founder';
$founder_image       = ! empty( get_field( 'founder_image' ) ) ? get_field( 'founder_image' ) : $default_founder_img;
$founder_description = ! empty( get_field( 'founder_description' ) ) ? get_field( 'founder_description' ) : '';
$founder_name        = ! empty( get_field( 'founder_name' ) ) ? get_field( 'founder_name' ) : 'Dr. Phillip Imler';
$founder_designation = ! empty( get_field( 'founder_designation' ) ) ? get_field( 'founder_designation' ) : 'Product Owner';
$inquiry_label       = ! empty( get_field( 'inquiry_label' ) ) ? get_field( 'inquiry_label' ) : 'Updates, Inquiries, and Proposals';
$inquiry_description = ! empty( get_field( 'inquiry_description' ) ) ? get_field( 'inquiry_description' ) : '';
$inquiry_cta         = ! empty( get_field( 'inquiry_cta' ) ) ? get_field( 'inquiry_cta' ) : '';
if ( ! empty( $founder_image ) ) {
	$founder_img_url = ! empty( $founder_image['url'] ) ? $founder_image['url'] : '';
}
if ( ! empty( $inquiry_cta ) ) {
	$inquiry_cta_title = $inquiry_cta['title'];
	$inquiry_cta_url   = $inquiry_cta['url'];
}

?>
<main class="about-main-container">

	<?php get_template_part( 'template-parts/header/page', 'introduction' ); ?>

	<?php
	if ( ! empty( $description ) ) {
		?>
			<div class="about-desc-wrapper">
				<h6 class="about-desc"><?php echo esc_html( $description ); ?></h6>
			</div>
		<?php
	}
	?>

	<div class="author-main-wrapper">
		<div class="container">
			<h2 class="text-center"><?php echo esc_html( $about_founder_title ); ?></h2>
			<div class="author-detail-wrapper">
				<?php
				if ( ! empty( $founder_img_url ) ) {
					?>
						<div class="author-img">
							<img src="<?php echo esc_url( $founder_img_url ); ?>" alt="founder-image" />
						</div>
					<?php
				}
				?>
				<div class="author-intro">
					<p><?php echo esc_html( $founder_description ); ?></p>
					<div class="author-detail">
						<h6><?php echo esc_html( $founder_name ); ?></h6>
						<span><?php echo esc_html( $founder_designation ); ?></span>
					</div>
				</div>
			</div>
		</div>
	</div>

	<?php
	if ( have_rows( 'explore_details' ) ) {
		?>
			<div class="explore-detail-wrapper">
				<?php
				while ( have_rows( 'explore_details' ) ) {
					the_row();
					$image            = ! empty( get_sub_field( 'image' ) ) ? get_sub_field( 'image' ) : '';
					$title            = ! empty( get_sub_field( 'title' ) ) ? get_sub_field( 'title' ) : '';
					$description      = ! empty( get_sub_field( 'description' ) ) ? get_sub_field( 'description' ) : '';
					$button_label     = ! empty( get_sub_field( 'button_label' ) ) ? get_sub_field( 'button_label' ) : '';
					$button_link      = ! empty( get_sub_field( 'button_link' ) ) ? get_sub_field( 'button_link' ) : '';
					$display_position = ! empty( get_sub_field( 'display_image_position' ) ) ? get_sub_field( 'display_image_position' ) : '';
					$custom_class     = $display_position == 'right' ? 'explore-detail-right' : 'explore-detail-left';
					?>
						<div class="explore-detail">
							<div class="explore-image">
								<img src="<?php echo esc_url( $image['url'] ); ?>" 
									alt="<?php echo esc_attr( ! empty( $image['alt'] ) ? $image['alt'] : $image['title'] ); ?>">
							</div>
							<div class="explore-intro">
								<h3 class="explore-name"><?php echo esc_html( $title ); ?></h3>
								<h5 class="explore-description"><?php echo wp_kses_post( $description ); ?></h5>
								<a href="<?php echo esc_url( $button_link ); ?>" class="explore-cta"><?php echo esc_html( $button_label ); ?></a>
							</div>
						</div>
					<?php
				}
				?>
			</div>
		<?php
	}
	?>

	<div class="inquiry-block">
		<h2 class="text-white"><?php echo esc_html( $inquiry_label ); ?></h2>
		<h5 class="text-white"><?php echo esc_html( $inquiry_description ); ?></h5>
		<a href="<?php echo esc_url( $inquiry_cta_url ); ?>"><?php echo esc_html( $inquiry_cta_title ); ?></a>
	</div>

</main>
<?php

get_footer();
