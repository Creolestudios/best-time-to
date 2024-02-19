<?php
/**
 * Load JS and CSS files of theme.
 *
 * @version 1.0.0
 *
 * @package btt
 */

/**
 * Load needed js and css files of theme.
 *
 * @version 1.0.0
 */
function btt_enqueue_scripts_styles() {
	// Load CSS files.
	wp_enqueue_style( 'slick-min', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick.min.css' );
	wp_enqueue_style( 'slick-theme', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.5.9/slick-theme.min.css' );
	wp_enqueue_style( 'all-style', BTT_THEME_URI . '/dist/assets/css/app.min.css', [], '1.0.0' );

	// Load JS files.
	wp_enqueue_script( 'global-script', BTT_THEME_URI . '/assets/js/btt-global.js', [ 'jquery' ], '1.0.0', false );
	wp_localize_script(
		'global-script',
		'globalScript',
		[
			'home_url'    => home_url( '/' ),
			'ajax_url'    => admin_url( 'admin-ajax.php' ),
			'nonce'       => wp_create_nonce( 'auth-token' ),
			'travel_page' => get_permalink( get_page_by_title( 'Travel' )->ID ),
		]
	);
	if ( is_page_template( 'template/template-life-categories.php' ) || is_page_template( 'template/template-travel-countries.php' ) ) {
		wp_enqueue_script( 'category-list-script', BTT_THEME_URI . '/assets/js/btt-category-list.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'category-list-script',
			'categoryScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_page_template( 'template/template-contact-us.php' ) ) {
		wp_enqueue_script( 'contact-form-script', BTT_THEME_URI . '/assets/js/btt-contact-form.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'contact-form-script',
			'contactScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_archive() ) {
		wp_enqueue_script( 'blog-list-script', BTT_THEME_URI . '/assets/js/btt-blog-list.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'blog-list-script',
			'myVar',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_search() ) {
		wp_enqueue_script( 'search-script', BTT_THEME_URI . '/assets/js/btt-search.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'search-script',
			'searchScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	if ( is_tag() ) {
		wp_enqueue_script( 'tag-script', BTT_THEME_URI . '/assets/js/btt-tag-list.js', [ 'jquery' ], '1.0.0', false );
		wp_localize_script(
			'tag-script',
			'tagScript',
			[
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'auth-token' ),
			]
		);
	}
	wp_enqueue_script( 'slick-js', 'https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.js', [ 'jquery' ], '1.0.0', false );
	wp_enqueue_script( 'jquery-validator-js', 'https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js', [], '1.0.0', false );
	wp_enqueue_script( 'custom-js', BTT_THEME_URI . '/dist/assets/js/custom.js', [ 'jquery' ], '1.0.0', false );
}
add_action( 'wp_enqueue_scripts', 'btt_enqueue_scripts_styles' );
