<?php
/**
 * Template Name: Home Page
 *
 * Custom template for the home page.
 *
 * @version 1.0.0
 * @package btt
 */
get_header();
$home_page_id = get_the_ID();
// Include slider section.
get_template_part( 'template-parts/home', 'slider', [ 'page_id' => $home_page_id ] );

// Include Ads Secction.
echo do_shortcode( '[ads_section_layout layout="horizontal-banner"]' );

// Include variety topics.
get_template_part( 'template-parts/home', 'variety-topics' );

// Include recently highlights.
get_template_part( 'template-parts/home', 'recent-highlights', [ 'page_id' => $home_page_id ] );

// Include popular content.
get_template_part( 'template-parts/home', 'popular-content' );

// Include favourite content.
get_template_part( 'template-parts/home', 'favourite-content' );

// Include recently featured.
get_template_part( 'template-parts/home', 'recent-featured', [ 'page_id' => $home_page_id ] );

get_footer();

