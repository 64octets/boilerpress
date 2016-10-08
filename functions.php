<?php
/**
 * BoilerPress functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BoilerPress
 */

$theme = wp_get_theme();
$boilerpress_version = $theme['Version'];

$boilerpress = (object) array(
	'version' => $theme['Version'],
	/**
	 * Initialize all the things.
	 */
	'main'       => require __DIR__ . '/inc/class-boilerpress.php',
);


add_filter('boilerpress_google_fonts', function() {
	return array(
		'playfair' => 'Playball',
		'dosis'    => 'Dosis:300, 300italic, 400, 400italic, 500, 500italic'
	);
});

/**
 * Creates the theme settings page
 */
require __DIR__ . '/inc/theme-settings.php';

/**
 * Register custom meta-boxes for pages, posts, taxonomies
 */
require  __DIR__ . '/inc/meta-boxes.php' ;
