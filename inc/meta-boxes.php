<?php
/**
 * Register custom meta-boxes for posts, pages, and taxonomies.
 *
 * Boilerpress uses a framework called Meta Box to simplify the process of adding custom
 * meta-boxes to posts and pages. For detailed documentation on how to register meta-boxes,
 * see the Meta Box docs
 * @link https://metabox.io/docs/
 *
 * @package BoilerPress
 */

$bp_meta = bp()->meta();


add_filter( 'boilerpress_meta_boxes', 'boilerpress_meta_boxes' );
function boilerpress_meta_boxes($meta_boxes) {

	$prefix = 'bp_';
	$meta_boxes[] = array(
		'id'         => $prefix . 'page_options',
		'title'      => __(  'Page Options', 'textdomain' ),
		'post_types' => array( 'post', 'page' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array(
			array(
				'name' => 'Custom Body Class',
				'id' => 'custom_body_class',
				'type' => 'text',
			),
			array(
				'name'  => __( 'Sticky Header', 'boilerpress' ),
				'id'    => $prefix . 'sticky_header',
				'type'  => 'select',
				'default' => '-1',
				'options' => array(
					'-1' => 'Use Global Setting',
					'0' => 'Disable Sticky Header On This Page',
					'1' => 'Enable Sticky Header On This Page',
				)
			),
			array(
				'name'  => __( 'Show Page Title Bar', 'boilerpress' ),
				'desc'  => 'Display default page header with page title',
				'id'    => $prefix . 'show_title_bar',
				'type'  => 'checkbox',
				'default'   => '1',
			)
		)
	);

	$meta_boxes[] = array(
		'id'         => $prefix . 'extra_page_meta',
		'title'      => __(  'Page Description', 'textdomain' ),
		'post_types' => array( 'page' ),
		'context'    => 'normal',
		'priority'   => 'high',
		'fields' => array(
			array(
				'name' => 'Page Description',
				'id' => 'page_description',
				'type' => 'text',
			)
		)
	);

	return $meta_boxes;
}