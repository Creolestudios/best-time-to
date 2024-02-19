<?php
/**
 * Theme functions and definitions
 *
 * @version 1.0.0
 *
 * @package btt
 */

/**
 * Define necessary constants.
 */
define( 'BTT_THEME_DIR', get_template_directory() );
define( 'BTT_THEME_URI', get_template_directory_uri() );

/* Loaded file for general functions. */
require BTT_THEME_DIR . '/inc/btt-general-functions.php';

/* Loaded file for ACF functions. */
require BTT_THEME_DIR . '/inc/btt-acf-functions.php';

/* Loaded file for to enqueue js css. */
require BTT_THEME_DIR . '/inc/btt-render-css-js.php';

/* Loaded file for theme setup functions. */
require BTT_THEME_DIR . '/inc/btt-setup-functions.php';

/* Loaded file for custom shortcodes. */
require BTT_THEME_DIR . '/inc/btt-custom-shortcodes.php';
