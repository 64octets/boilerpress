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
global $boilerpress_meta;


class BP_Framework {
	function __construct() {
		$this->load();
	}

	private function load() {
		require_once get_template_directory() . '/inc/bp-framework/bp-kirki/class-bp-kirki.php';
		require_once get_template_directory() . '/inc/bp-framework/bp-kirki/class-bp-installer-controller.php';
		require_once get_template_directory() . '/inc/bp-framework/bp-meta-box/class-bp-meta-box.php';
	}

	public function customize(){
		global $bp_customize;
	    return isset($bp_customize) ? $bp_customize : ( $bp_customize = new BP_Kirki() );
	}

	public function meta() {
		global $boilerpress_meta;
		return isset($boilerpress_meta) ? $boilerpress_meta : ( $boilerpress_meta = new BP_Meta_Box() );
	}
}

function bp() {
	global $boilerpress_framework;
	return isset( $boilerpress_framework ) ? $boilerpress_framework : ( $boilerpress_framework = new BP_Framework() );
}