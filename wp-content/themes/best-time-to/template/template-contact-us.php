<?php
/**
 * Template Name: Contact Us Page
 *
 * Custom template for the contact page.
 *
 * @version 1.0.0
 * @package btt
 */
get_header();
?>

<main class="contact-container">
	<?php get_template_part( 'template-parts/header/page', 'introduction' ); ?>

	<div class="contact-main-wrapper">
		<div class="contact-detail-wrapper">
			<h3><?php echo esc_html( 'Reach Us Via Mail' ); ?></h3>
			<?php
			$contact_details = get_field( 'contact_details', 'option' );
			if ( $contact_details ) {
				foreach ( $contact_details as $detail ) {
					$detail = $detail['detail'];
					?>
						<h5 class="contact-detail"><?php echo esc_html( $detail ); ?></h5>
					<?php
				}
			}

			$social_media_information = get_field( 'social_media_information', 'option' );
			if ( $social_media_information ) {
				?>
					<div class="social-info">
						<?php
						foreach ( $social_media_information as $detail ) {
							$social_media_img = $detail['icon'];
							$social_media_url = $detail['link'];
							?>
								<a href="<?php echo esc_url( $social_media_url ); ?>">
									<img src="<?php echo esc_url( $social_media_img ); ?>" alt="social-information">
								</a>
							<?php
						}
						?>
					</div>
				<?php
			}
			?>
		</div>
		<div class="contact-form-wrapper">
			<h6><?php echo esc_html( 'Reach Us Online' ); ?></h6>
			<form class="inquiry-form">
				<div class="inquiry-field">
					<label for="name"><?php echo esc_html( 'Name' ); ?></label>
					<input type="text" id="name" name="name" placeholder="John Jacob">
					<span class="error-field error name-error"></span>
				</div>

				<div class="inquiry-field">
					<label for="email"><?php echo esc_html( 'Email' ); ?></label>
					<input type="text" id="email" name="email" placeholder="abc@gmail.com">
					<span class=" error-field error email-error"></span>
				</div>

				<div class="inquiry-field">
					<label for="reason"><?php echo esc_html( 'Reason for Contact' ); ?></label>
					<div class="select-dropdown">
						<button href="#" role="button" data-value="0" class="select-dropdown__button"><span><?php echo esc_html( 'Select' ); ?></span> <i class="zmdi zmdi-chevron-down"></i>
						</button>
						<ul class="select-dropdown__list">
							<li data-value="content-update" class="select-dropdown__list-item"><?php echo esc_html( 'Content Update' ); ?></li>
							<li data-value="guest-blog-inquiry" class="select-dropdown__list-item"><?php echo esc_html( 'Guest Blog Inquiry' ); ?></li>
							<li data-value="partner-inquiry" class="select-dropdown__list-item"><?php echo esc_html( 'Partner Inquiry' ); ?></li>
							<li data-value="other" class="select-dropdown__list-item"><?php echo esc_html( 'Other' ); ?></li>
						</ul>
					</div>
					<select id="reason" name="reason">
						<option value="" disabled selected><?php echo esc_html( 'Select' ); ?></option>
						<option value="content-update"><?php echo esc_html( 'Content Update' ); ?></option>
						<option value="guest-blog-inquiry"><?php echo esc_html( 'Guest Blog Inquiry' ); ?></option>
						<option value="partner-inquiry"><?php echo esc_html( 'Partner Inquiry' ); ?></option>
						<option value="other"><?php echo esc_html( 'Other' ); ?></option>
					</select>
					<span class="error-field error reason-error"></span>
				</div>

				<div class="inquiry-field">
					<label for="message"><?php echo esc_html( 'Message' ); ?></label>
					<textarea rows="8" id="message" name="message" placeholder="Type here..."></textarea>
					<span class=" error-field error message-error"></span>
				</div>

				<div class="inquiry-field">
					<button class="submit-form"><?php echo esc_html( 'Send' ); ?></button>
				</div>

				<div class="loader contact-form-loader" style="display:none">
					<img src="<?php echo esc_attr( BTT_THEME_URI . '/dist/assets/images/loader.gif' ); ?>" alt="loader">
				</div>

				<div class="success-response"></div>
			</form>
		</div>
	</div>
</main>

<?php
get_footer();
