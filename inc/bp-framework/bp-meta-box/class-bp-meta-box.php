<?php

/**
 * This class is simply a wrapper for Meta Box framework.
 *
 * boilerpress
 * class-bp-framework-admin.php
 */
class BP_Meta_Box {
	private static $meta_boxes = array();

	public function __construct() {
		add_filter( 'rwmb_meta_boxes', array( $this, 'get_meta_boxes' ) );
		add_action( 'init', array( $this, 'register_meta_boxes' ), 1 );
	}

	/**
	 * Returns the array of meta boxes to be registered.
	 * @return array
	 */
	public function get_meta_boxes() {
		return (array) self::$meta_boxes;
	}

	public function register_meta_boxes() {
		$meta_boxes = apply_filters( 'boilerpress_meta_boxes', self::$meta_boxes );
		if ( is_array( $meta_boxes ) ) {
			foreach ( $meta_boxes as $meta_box ) {
				self::$meta_boxes[] = $meta_box;
			}
		}
	}

	public function register( $meta_box ) {
		if ( is_array( $meta_box ) ) {
			self::$meta_boxes [] = $meta_box;
		}
	}
}

return new BP_Meta_Box();