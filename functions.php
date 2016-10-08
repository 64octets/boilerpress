<?php
/**
 * BoilerPress functions and definitions.
 *
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package BoilerPress
 */

$theme               = wp_get_theme();
$boilerpress_version = $theme[ 'Version' ];

$boilerpress = (object) array(
    'version' => $theme[ 'Version' ],
    /**
     * Initialize all the things.
     */
    'main'    => require __DIR__ . '/inc/class-boilerpress.php',
);

/**
 * Creates the theme settings page
 */
require __DIR__ . '/inc/theme-settings.php';

/**
 * Register custom meta-boxes for pages, posts, taxonomies
 */
require __DIR__ . '/inc/meta-boxes.php';
