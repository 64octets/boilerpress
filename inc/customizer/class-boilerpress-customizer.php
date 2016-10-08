<?php
/**
 * BoilerPress Customizer Class
 *
 * @author   Luke Peavey
 * @package  boilerpress
 * @since    0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'BoilerPress_Customizer' ) ) :

	/**
	 * The BoilerPress Customizer class
	 */
	class BoilerPress_Customizer {
		public function __construct() {
			add_action( 'customize_register',              array($this, 'boilerpress_customize_register'), 10 );
			add_action( 'customize_preview_init',          array($this, 'boilerpress_customize_preview_js' ));
			add_action( 'init',                            array( $this, 'default_theme_mod_values' ), 10 );
		}

		/**
		 * Returns an array of the default values for boilerpress customizer settings
		 *
		 * @return array
		 */
		public static function get_boilerpress_default_setting_values() {
			return apply_filters( 'boilerpress_setting_default_values', array(
				'bp_sticky_header' => '1',
				'bp_typekit_id'    => 'pzc8log',
				'my_other_setting' => 'My Other Setting Value Two'
			));
		}

		/**
		 * Add postMessage support for site title and description for the Theme Customizer.
		 *
		 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
		 */
		public function boilerpress_customize_register( $wp_customize ) {
			$bp_customize = bp()->customize();
			$defaults = self::get_boilerpress_default_setting_values();

			$bp_customize->add_config( 'boilerpress', array(
				'option_type' => 'theme_mod',
				'capability'  => 'edit_theme_options',
			) );

			$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
			$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
			$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';



			$bp_customize->add_section( 'theme_settings', array(
				'title'       => esc_attr__( 'Theme Settings', 'boilerpress' ),
				'capability'  => 'edit_theme_options',
			) );

			$bp_customize->add_field( 'boilerpress', array(
				'type'        => 'checkbox',
				'settings'    => 'sticky_header',
				'default'     => $defaults['bp_sticky_header'],
				'label'       => esc_attr__( 'Sticky Header', 'boilerpress' ),
				'description' => esc_attr__( 'Enable sticky header', 'boilerpress' ),
				'section'     => 'theme_settings',
			    ''
			) );

			$bp_customize->add_section( 'typography', array(
				'title'       => esc_attr__( 'Typography Settings', 'boilerpress' ),
				'capability'  => 'edit_theme_options',
			) );

			$bp_customize->add_field( 'boilerpress', array(
				'type'        => 'text',
				'settings'    => 'bp_typekit_id',
				'default'     => $defaults['bp_typekit_id'],
				'label'       => esc_attr__('Typekit Kit ID', 'boilerpress'),
				'section'     => 'typography',
			));

			$bp_customize->add_field( 'boilerpress', array(
				'type'        => 'text',
				'settings'    => 'my_other_setting',
				'default'     => $defaults['my_other_setting'],
				'label'       => esc_attr__('Other Setting', 'boilerpress'),
				'section'     => 'typography',
			));


		}


		/**
		 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
		 */
		public function boilerpress_customize_preview_js() {
			wp_enqueue_script( 'boilerpress_customizer', get_template_directory_uri() . '/assets/js/customizer.js',
				array( 'customize-preview' ), '20151215', true );
		}


		/**
		 * Adds a value to each BoilerPress setting if one isn't already present.
		 *
		 * @uses get_boilerpress_default_setting_values()
		 */
		public function default_theme_mod_values() {
			foreach ( self::get_boilerpress_default_setting_values() as $mod => $val ) {
				add_filter( 'theme_mod_' . $mod, array( $this, 'get_theme_mod_value' ), 10 );
			}
		}

		/**
		 * Get theme mod value.
		 *
		 * @param string $value
		 * @return string
		 */
		public function get_theme_mod_value( $value ) {
			$key = substr( current_filter(), 10 );

			$set_theme_mods = get_theme_mods();

			if ( isset( $set_theme_mods[ $key ] ) ) {
				return $value;
			}

			$values = self::get_boilerpress_default_setting_values();

			return isset( $values[ $key ] ) ? $values[ $key ] : $value;
		}

		/**
		 * Set Customizer setting defaults.
		 * These defaults need to be applied separately as child themes can filter boilerpress_setting_default_values
		 *
		 * @param  array $wp_customize the Customizer object.
		 * @uses   get_boilerpress_default_setting_values()
		 */
		public function edit_default_customizer_settings( $wp_customize ) {
			foreach ( self::get_boilerpress_default_setting_values() as $mod => $val ) {
				$wp_customize->get_setting( $mod )->default = $val;
			}
		}

		/**
		 * Get all of the BoilerPress theme mods.
		 *
		 * @return array $boilerpress_theme_mods The BoilerPress Theme Mods.
		 */
		public function get_boilerpress_theme_mods() {
			$boilerpress_theme_mods = array(
				'bp_sticky_header'    => get_theme_mod('bp_sticky_header'),
				'bp_typekit_id'       => get_theme_mod('bp_typekit_id'),
			);

			return apply_filters( 'boilerpress_theme_mods', $boilerpress_theme_mods );
		}

	}

endif;
return new BoilerPress_Customizer();