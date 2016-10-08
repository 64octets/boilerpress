<?php

/**
 *
 * A breif summary of the file
 *
 * boilerpress
 * class-bp-framework.php
 */

global $boilerpress_framework;
global $boilerpress_customize;

class BP_Framework {
	public function __construct() {
		require_once get_template_directory() . '/inc/boilerpress-framework/required-plugins.php';
		require_once get_template_directory() . '/inc/boilerpress-framework/bp-kirki/class-bp-customize.php';
	}

	public function customize(){
		global $bp_customize;
	    return isset($bp_customize) ? $bp_customize : ( $bp_customize = new BP_Kirki() );
	}

	public function meta() {
		 function boilerpress_register_meta_boxes( $meta_boxes ){
			return apply_filters('boilerpress_meta_boxes', $meta_boxes);
		}
		add_filter( 'rwmb_meta_boxes', 'boilerpress_meta_boxes' );
	}
}

function bp() {
	global $boilerpress_framework;
	return isset( $boilerpress_framework ) ? $boilerpress_framework : ( $boilerpress_framework = new BP_Framework() );
}