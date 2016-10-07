<?php
/**
 * Body Class
 *
 * Applies custom filters to the body class
 *
 * @package BoilerPress
 * @author  Luke Peavey
 */

function boilerpress_body_class( $classes ) {
	global $post;
	$titan = TitanFramework::getInstance('boilerpress');

	function filter_classes_array( $class = '' ) {
		if ( strpos( $class, 'page-template' ) ) {
			return FALSE;
		}
		return TRUE;
	}

	// Custom classes for pages
	if ( is_page() ) {

		if ( is_front_page() ) {
			$classes[] = 'frontpage';
		}
		// Add class for page name and parent page (if there is one)
		// .page-{$page-name}
		$classes[] = "page-{$post->post_name}";

		// .parent-{$page-name}
		if ( $post->post_parent ) {
			$post_parent = get_post( $post->post_parent );
			$classes[]   = "parent-{$post_parent->post_name}";
		}

		// Add class for page template
		if ( $template = get_page_template() ) {
			$path          = pathinfo( $template );
			$template_name = isset( $path[ 'filename' ] ) ? $path[ 'filename' ] : NULL;
			if ( $template_name && $template_name !== 'page' ) {
				$classes[] = $template_name;
			}

		}

		// Custom body class from page options
		if ( !empty( $custom_classes = $titan->getOption('page_body_class', $post->ID) ) ) {
			$classes = array_merge($classes, explode(' ', preg_replace('/\s{2,}/', ' ', $custom_classes) ) );
		}


	}

	return $classes;
}

add_filter( 'body_class', 'boilerpress_body_class' );