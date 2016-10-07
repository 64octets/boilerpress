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


function boilerpress_fonts(){
	$output = '';
	if ( !empty($id = get_theme_mod('bp_typekit_id')) ) {
		$output .= ( '<script src="https://use.typekit.net/' . $id . '.js"></script>');
		$output .= '<script>try{Typekit.load({ async: true });}catch(e){}</script>';
		$output .= '<link href="https://fonts.googleapis.com/css?family=Pathway+Gothic+One" rel="stylesheet">';
	}
	echo $output;
}
add_action('wp_head', 'boilerpress_fonts');


$boilerpress = (object) array(
	'version' => $theme['Version'],
	/**
	 * Initialize all the things.
	 */
	'main'       => require 'inc/class-boilerpress.php',
);

function boilerpress_nav_menu_button() { ?>
	<form action="" class="inline-form navbar-form pull-xs-right">
		<button class="btn btn-primary btn pull-xs-right hidden-md-down" type="submit">Purchase</button>
	</form>
<?php }

add_action('boilerpress_before_primary_nav', 'boilerpress_nav_menu_button');

/**
 * Creates the theme settings page
 */
require'inc/theme-settings.php';

/**
 * Register custom meta-boxes for pages, posts, taxonomies
 */
require 'inc/meta-boxes.php' ;
