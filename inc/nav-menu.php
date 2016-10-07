<?php
/**
 * Custom Nav Menu Markup
 *
 * @author   Luke Peavey
 * @package  BoilerPress
 */

// Customizes the output of wp_nav_menu (used in this theme for the main site naviation)
// Customizes the markup of navigation menus creating with the wp_nav_menu function.
// The custom output is based on the markup used by bootrap nav menus.
//
// item             => .nav-item
// item w/ submenu  => .has-sub-menu
// current item     => .active
//
// link             => .nav-link
// current link     => .active
// link w/ submenu  => .dropdown
// current dropdown => .active-parent / .active-ancestor

function boilerpress_menu_item_classes( $classes, $item, $args, $depth ) {

	$custom_class_map = array(
		'menu-item'              => 'nav-item',
		'menu-item-has-children' => 'dropdown',
		'current-menu-item'      => 'active',
		'current-menu-parent'    => 'active-dropdown',
		'menu-item-type-custom'  => 'custom-nav-item',
	);

	$filtered_classes = array_intersect( $classes, array_keys( $custom_class_map ) );
	$classes          = array();

	foreach ( $filtered_classes as $class_name ) {
		if ( array_key_exists( $class_name, $custom_class_map ) ) {
			if ( strpos( $class_name, 'current-menu-item' ) !== FALSE ) {
				$classes[] = in_array( 'menu-item-type-custom', $filtered_classes ) ? '' : 'active';
			} else {
				$classes[] = $custom_class_map[ $class_name ];
			}
		}
	}
	if ( is_numeric( $depth ) && $depth > 0 ) {
		//$classes[] = 'dropdown-item';
	}

	return $classes;
}

function boilerpress_menu_item_ids( $value, $item, $args ) {
	return 'nav-item-' . $item->ID;
}

function boilerpress_menu_link_attributes( $attrs, $item, $args ) {
	$classes = array( 'nav-link' );

	if ( in_array( 'current-menu-item', $item->classes ) ) {
		// $classes[]= 'active';
	}
	if ( in_array( 'menu-item-has-children', $item->classes ) ) {
		$classes[]                = 'dropdown-toggle toggle-switch';
		$attrs[ 'data-toggle' ]   = 'dropdown';
		$attrs[ 'aria-expanded' ] = 'false';
		$attrs[ 'aria-haspopup' ] = 'true';
	}
	$attrs[ "class" ] = join( ' ', $classes );
	$attrs[ "alt" ]   = $item->title;

	return $attrs;
}

add_filter( 'nav_menu_css_class', 'boilerpress_menu_item_classes', 10, 4 );
add_filter( 'nav_menu_item_id', 'boilerpress_menu_item_ids', 10, 4 );
add_filter( 'nav_menu_link_attributes', 'boilerpress_menu_link_attributes', 10, 4 );