<?php
/**
 * BoilerPress Theme Customizer.
 *
 * @package BoilerPress
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function boilerpress_customize_register( $wp_customize ) {
    $wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
    $wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
    $wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
}

add_action( 'customize_register', 'boilerpress_customize_register' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function boilerpress_customize_preview_js() {
    wp_enqueue_script( 'boilerpress_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', TRUE );
}

add_action( 'customize_preview_init', 'boilerpress_customize_preview_js' );
