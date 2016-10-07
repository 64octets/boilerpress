<?php
/**
 * BoilerPress Admin Class
 *
 * @author   Luke Peavey
 * @package  boilerPress
 * @since    0.0.1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $boilerpress_admin;

if ( ! class_exists( 'BoilerPress_Admin' ) && FALSE ) :
	/**
	 * The BoilerPress admin class
	 */
	class BoilerPress_Admin {

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->theme_options_slug = wp_get_theme()->get_template();
			$this->theme_meta_slug = $this->theme_options_slug . '-meta';

			if (is_admin()) {

			}
		}
		private $theme_options_slug;
		private $theme_meta_slug;



		/**
		 * @param array  $args       {
		 *       An array parameters that define the admin panel
		 *
		 *       @type string  $name       The name of the admin page. This is also the name shown in the admin menu.
		 *       @type string  $title      (Optional) The title displayed on the top of this admin page.
		 *                                 Defaults to the name parameter.
		 *       @type string  $desc       (Optional) A description displayed just below the title of this admin page.
		 *       @type int     $id         (Optional) A unique slug for this admin page.
		 *                                 Defaults to a generated slug from the name parameter.
		 *       @type string  $parent     (Optional) If given, the admin page will become a submenu of this slug ID.
		 *                                 Useful if you want your menu to be inside a core WordPress menu.
		 *                                 The list of menu slug IDs can be found in the codex. Defaults to ''
		 *       @type string  $capability (Optional) The required capability of the user for this admin page.
		 *                                 Defaults to manage_options
		 *       @type string  $icon       (Optional) The menu icon for the admin menu for this page.
		 *                                 This can either be a URL to the icon image file, or the name of a WordPress
		 *                                 DashIcon. Defaults to dashicons-admin-generic
		 *       @type boolean $capability (Optional) The required capability of the user for this admin page.
		 *       @type int     $position   (Optional) (Optional) The position where the menu should appear. This value
		 *                                 corresponds to the core menu positions found in the codex. Defaults to null
		 *       @type int use_form        (Optional) If false, the main form tag in the admin page will not be rendered.
		 *                                 Useful only when the admin page doesn’t save anything. Defaults to true
		 * }
		 *
		 * @return mixed
		 */
		public function create_admin_page( $args ) {
			return TitanFramework::getInstance($this->theme_options_slug)->createAdminPage( $args );
		}


		/**
		 * @param array  $args       {
		 *       An array parameters that define the admin panel
		 *
		 *       @type string  $name       The name of the admin page. This is also the name shown in the admin menu.
		 *       @type string  $title      (Optional) The title displayed on the top of this admin page.
		 *                                 Defaults to the name parameter.
		 *       @type string  $desc       (Optional) A description displayed just below the title of this admin page.
		 *       @type int     $id         (Optional) A unique slug for this admin page.
		 *                                 Defaults to a generated slug from the name parameter.
		 *       @type string  $parent     (Optional) If given, the admin page will become a submenu of this slug ID.
		 *                                 Useful if you want your menu to be inside a core WordPress menu.
		 *                                 The list of menu slug IDs can be found in the codex. Defaults to ''
		 *       @type string  $capability (Optional) The required capability of the user for this admin page.
		 *                                 Defaults to manage_options
		 *       @type string  $icon       (Optional) The menu icon for the admin menu for this page.
		 *                                 This can either be a URL to the icon image file, or the name of a WordPress
		 *                                 DashIcon. Defaults to dashicons-admin-generic
		 *       @type boolean $capability (Optional) The required capability of the user for this admin page.
		 *       @type int     $position   (Optional) (Optional) The position where the menu should appear. This value
		 *                                 corresponds to the core menu positions found in the codex. Defaults to null
		 *       @type int use_form        (Optional) If false, the main form tag in the admin page will not be rendered.
		 *                                 Useful only when the admin page doesn’t save anything. Defaults to true
		 * }
		 *
		 * @return mixed
		 */


		public function create_meta_box( $args ) {
			return TitanFramework::getInstance($this->theme_meta_slug)->createMetaBox( $args );
		}

		public function get_option($id){
			return TitanFramework::getInstance($this->theme_options_slug)->getOption($id);
		}

		public function get_meta($id){
			return TitanFramework::getInstance($this->theme_meta_slug)->getOption($id);
		}


		/**
		 * Adds an admin notice upon successful activation.
		 *
		 * @since 1.0.3
		 */
		public function activation_admin_notice() {

		}

		/**
		 * Display an admin notice linking to the welcome screen
		 *
		 * @since 1.0.3
		 */
		public function boilerpress_welcome_admin_notice() { }

		/**
		 * Load welcome screen css
		 *
		 * @param string $hook_suffix the current page hook suffix.
		 *
		 * @return void
		 * @since  1.4.4
		 */
		public function welcome_style( $hook_suffix ) { }

		/**
		 * Creates the dashboard page
		 *
		 * @see   add_theme_page()
		 * @since 0.0.1
		 */
		public function theme_settings_page() {

		}

		/**
		 * The welcome screen
		 *
		 * @since 0.0.1
		 */
		public function boilerpress_welcome_screen() {
			require_once( ABSPATH . 'wp-load.php' );
			require_once( ABSPATH . 'wp-admin/admin.php' );
			require_once( ABSPATH . 'wp-admin/admin-header.php' );
			?>
			<div class="wrap about-wrap">

				<?php
				/**
				 * Functions hooked into boilerpress_welcome action
				 *
				 * @hooked welcome_intro      - 10
				 * @hooked welcome_enhance    - 20
				 * @hooked welcome_contribute - 30
				 */
				do_action( 'boilerpress_welcome' ); ?>

			</div>
			<?php
		}

		/**
		 * Welcome screen intro
		 *
		 * @since 0.0.1
		 */
		public function welcome_intro() {
			require_once( get_template_directory() . '/inc/admin/welcome-screen/component-intro.php' );
		}


		/**
		 * Welcome screen enhance section
		 *
		 * @since 1.5.2
		 */
		public function welcome_enhance() {
			require_once( get_template_directory() . '/inc/admin/welcome-screen/component-enhance.php' );
		}

		/**
		 * Welcome screen contribute section
		 *
		 * @since 1.5.2
		 */
		public function welcome_contribute() {
			require_once( get_template_directory() . '/inc/admin/welcome-screen/component-contribute.php' );
		}

		/**
		 * Get product data from json
		 *
		 * @param  string $url       URL to the json file.
		 * @param  string $transient Name the transient.
		 *
		 * @return [type]            [description]
		 */
		public function get_boilerpress_product_data( $url, $transient ) {
			$raw_products = wp_safe_remote_get( $url );
			$products     = json_decode( wp_remote_retrieve_body( $raw_products ) );

			if ( ! empty( $products ) ) {
				set_transient( $transient, $products, DAY_IN_SECONDS );
			}

			return $products;
		}
	}

endif;
