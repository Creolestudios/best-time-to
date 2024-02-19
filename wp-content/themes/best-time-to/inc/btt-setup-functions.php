<?php
/**
 * Theme Setup functions.
 *
 * @version 1.0.0
 *
 * @package btt
 */

/**
 * Enable custom support in the theme.
 *
 * @version 1.0.0
 */
function btt_custom_theme_setup() {
	// Enable menu support.
	add_theme_support( 'menus' );
	// Enable feature image support.
	add_theme_support( 'post-thumbnails' );
	/*
	* Let WordPress manage the document title.
	* By adding theme support, we declare that this theme does not use a
	* hard-coded <title> tag in the document head, and expect WordPress to
	* provide it for us.
	*/
	add_theme_support( 'title-tag' );
}
add_action( 'after_setup_theme', 'btt_custom_theme_setup' );

/**
 * Register custom menus in the theme.
 *
 * @version 1.0.0
 */
function btt_register_custom_menus() {
	register_nav_menus(
		[
			'header-menu'        => 'Header Menu',
			'footer-main-menu'   => 'Footer Main Menu',
			'footer-bottom-menu' => 'Footer Bottom Menu',
			'header-mobile-menu' => 'Header Mobile Menu',
		]
	);
}
add_action( 'init', 'btt_register_custom_menus' );

/**
 * Allow SVG Support
 *
 * @param array $mimes Array of allowed mime types.
 * @return array Modified array of allowed mime types.
 */
function btt_allow_svg_support( $mimes ) {
	$mimes['svg'] = 'image/svg+xml';

	return $mimes;
}
add_filter( 'upload_mimes', 'btt_allow_svg_support' );

/**
 * Additional custom taxonomies.
 *
 * @version 1.0.0
 */
function btt_add_custom_taxonomies() {

	// Country and region taxonomy.
	register_taxonomy(
		'country',
		[ 'post' ],
		[
			// Hierarchical taxonomy (like categories)
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			// This array of options controls the labels displayed in the WordPress Admin UI
			'labels'            => [
				'name'              => _x( 'Country & Region', 'Country & Region' ),
				'singular_name'     => _x( 'Country & Region', 'Country & Region' ),
				'search_items'      => __( 'Search Country & Region' ),
				'all_items'         => __( 'All Country & Region' ),
				'parent_item'       => __( 'Parent Country & Region' ),
				'parent_item_colon' => __( 'Parent Country & Region:' ),
				'edit_item'         => __( 'Edit Country & Region' ),
				'update_item'       => __( 'Update Country & Region' ),
				'add_new_item'      => __( 'Add New Country & Region' ),
				'new_item_name'     => __( 'New Country & Region' ),
				'menu_name'         => __( 'Country & Region' ),
			],
		]
	);

	// Natur taxonomy.
	register_taxonomy(
		'nature',
		[ 'post' ],
		[
			// Hierarchical taxonomy (like categories)
			'hierarchical'      => true,
			'show_admin_column' => true,
			'show_in_rest'      => true,
			// This array of options controls the labels displayed in the WordPress Admin UI
			'labels'            => [
				'name'              => _x( 'Nature', 'Nature' ),
				'singular_name'     => _x( 'Nature', 'Nature' ),
				'search_items'      => __( 'Search Nature' ),
				'all_items'         => __( 'All Nature' ),
				'parent_item'       => __( 'Parent Nature' ),
				'parent_item_colon' => __( 'Parent Nature:' ),
				'edit_item'         => __( 'Edit Nature' ),
				'update_item'       => __( 'Update Nature' ),
				'add_new_item'      => __( 'Add New Nature' ),
				'new_item_name'     => __( 'New Nature' ),
				'menu_name'         => __( 'Nature' ),
			],
		]
	);
}
add_action( 'init', 'btt_add_custom_taxonomies' );
