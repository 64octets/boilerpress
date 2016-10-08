<?php
/**
 * BoilerPress Class
 *
 * @author   Luke Peavey
 * @since    0.0.1
 * @package  BoilerPress
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'BoilerPress' ) ) :

	/**
	 * The main Storefront class
	 */
	class BoilerPress {

		private static $structured_data;

		/**
		 * Setup class.
		 *
		 * @since 1.0
		 */
		public function __construct() {
			$this->load();
			add_action( 'after_setup_theme',          array( $this, 'setup' ) );
			add_action( 'widgets_init',               array( $this, 'widgets_init' ) );
			add_action( 'wp_enqueue_scripts',         array( $this, 'scripts' ),       10 );
			add_action( 'wp_enqueue_scripts',         array( $this, 'child_scripts' ), 30 ); // After WooCommerce.
			add_action( 'wp_enqueue_scripts',         array( $this, 'google_webfonts' ), 30 ); // After WooCommerce.
			add_action( 'wp_head',                    array( $this, 'typekit_webfonts'), 1);
			add_action( 'enqueue_embed_scripts',      array( $this, 'print_embed_styles' ) );
			add_action( 'wp_footer',                  array( $this, 'get_structured_data' ) );


			add_filter( 'body_class',                 array( $this, 'body_classes' ) );
			add_filter( 'wp_page_menu_args',          array( $this, 'page_menu_args' ) );
			add_filter( 'navigation_markup_template', array( $this, 'navigation_markup_template' ) );

			add_filter( 'embed_oembed_html',          array( $this, 'wrap_embed_html' ), 10, 3 );

			// The following 3 filters customize the markup of navigation menus created with wp_nav_menu()
			add_filter( 'nav_menu_css_class', array($this, 'nav_menu_css_class'), 10, 4 );
			add_filter( 'nav_menu_item_id', array($this, 'nav_menu_item_id'), 10, 4 );
			add_filter( 'nav_menu_link_attributes', array($this, 'nav_menu_link_attributes'), 10, 4);
		}

		public function load() {
			require_once get_template_directory() . '/inc/boilerpress-framework/class-boilerpress-framework.php';
			require_once get_template_directory() . '/inc/boilerpress-functions.php';
			require_once get_template_directory() . '/inc/boilerpress-template-hooks.php';
			require_once get_template_directory() . '/inc/boilerpress-template-functions.php';
			require_once get_template_directory() . '/inc/admin/class-boilerpress-admin.php';
			require_once get_template_directory() . '/inc/customizer/class-boilerpress-customizer.php';
		}

		/**
		 * Sets up theme defaults and registers support for various WordPress features.
		 *
		 * Note that this function is hooked into the after_setup_theme hook, which
		 * runs before the init hook. The init hook is too late for some features, such
		 * as indicating support for post thumbnails.
		 */
		public function setup() {
			/*
			 * Load Localisation files.
			 *
			 * Note: the first-loaded translation file overrides any following ones if the same translation is present.
			 */

			// Loads wp-content/languages/themes/boilerpress-it_IT.mo.
			load_theme_textdomain( 'boilerpress', trailingslashit( WP_LANG_DIR ) . 'themes/' );

			// Loads wp-content/themes/child-theme-name/languages/it_IT.mo.
			load_theme_textdomain( 'boilerpress', get_stylesheet_directory() . '/languages' );

			// Loads wp-content/themes/boilerpress/languages/it_IT.mo.
			load_theme_textdomain( 'boilerpress', get_template_directory() . '/languages' );

			/**
			 * Add default posts and comments RSS feed links to head.
			 */
			add_theme_support( 'automatic-feed-links' );

			/*
			 * Enable support for Post Thumbnails on posts and pages.
			 *
			 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
			 */
			add_theme_support( 'post-thumbnails' );

			/**
			 * Enable support for site logo
			 */
			add_theme_support( 'custom-logo', array(
				'height'      => 110,
				'width'       => 470,
				'flex-width'  => true,
			) );

			// This theme uses wp_nav_menu() in two locations.
			register_nav_menus( array(
				'primary'   => __( 'Primary Menu', 'boilerpress' ),
				'secondary' => __( 'Secondary Menu', 'boilerpress' ),
				'handheld'  => __( 'Handheld Menu', 'boilerpress' ),
			) );

			/*
			 * Switch default core markup for search form, comment form, comments, galleries, captions and widgets
			 * to output valid HTML5.
			 */
			add_theme_support( 'html5', array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
				'widgets',
			) );

			// Setup the WordPress core custom background feature.
			add_theme_support( 'custom-background', apply_filters( 'boilerpress_custom_background_args', array(
				'default-color' => apply_filters( 'boilerpress_default_background_color', 'ffffff' ),
				'default-image' => '',
			) ) );

			/**
			 *  Add support for the Site Logo plugin and the site logo functionality in JetPack
			 *  https://github.com/automattic/site-logo
			 *  http://jetpack.me/
			 */
			add_theme_support( 'site-logo', array( 'size' => 'full' ) );

			// Declare WooCommerce support.
			add_theme_support( 'woocommerce' );

			// Declare support for title theme feature.
			add_theme_support( 'title-tag' );

			// Declare support for selective refreshing of widgets.
			add_theme_support( 'customize-selective-refresh-widgets' );
		}

		/**
		 * Register widget area.
		 *
		 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
		 */
		public function widgets_init() {
			$sidebar_args['header'] = array(
				'name'        => __( 'Below Header', 'boilerpress' ),
				'id'          => 'header-1',
				'description' => __( 'Widgets added to this region will appear beneath the header and above the main content.', 'boilerpress' ),
			);

			$sidebar_args['sidebar'] = array(
				'name'          => __( 'Sidebar', 'boilerpress' ),
				'id'            => 'sidebar-1',
				'description'   => ''
			);

			$footer_widget_regions = apply_filters( 'boilerpress_footer_widget_regions', 4 );

			for ( $i = 1, $n = (int) $footer_widget_regions; $i <= $n; $i++ ) {
				$footer = sprintf( 'footer_%d', $i );

				$sidebar_args[ $footer ] = array(
					'name'        => sprintf( __( 'Footer %d', 'boilerpress' ), $i ),
					'id'          => sprintf( 'footer-%d', $i ),
					'description' => sprintf( __( 'Widgetized Footer Region %d.', 'boilerpress' ), $i )
				);
			}

			foreach ( $sidebar_args as $sidebar => $args ) {
				$widget_tags = array(
					'before_widget' => '<div id="%1$s" class="widget %2$s">',
					'after_widget'  => '</div>',
					'before_title'  => '<span class="gamma widget-title">',
					'after_title'   => '</span>'
				);

				/**
				 * Dynamically generated filter hooks. Allow changing widget wrapper and title tags. See the list below.
				 *
				 * 'boilerpress_header_widget_tags'
				 * 'boilerpress_sidebar_widget_tags'
				 *
				 * boilerpress_footer_1_widget_tags
				 * boilerpress_footer_2_widget_tags
				 * boilerpress_footer_3_widget_tags
				 * boilerpress_footer_4_widget_tags
				 */
				$filter_hook = sprintf( 'boilerpress_%s_widget_tags', $sidebar );
				$widget_tags = apply_filters( $filter_hook, $widget_tags );

				if ( is_array( $widget_tags ) ) {
					register_sidebar( $args + $widget_tags );
				}
			}
		}

		public function typekit_webfonts(){
			$typekit_id = (string) apply_filters('boilerpress_typekit_id', get_theme_mod('bp_typekit_id') );

			if ( '' !== $typekit_id ) {
				echo get_typekit_embed_code($typekit_id);
			}
		}


		public function google_webfonts(){
			$google_fonts = apply_filters( 'boilerpress_google_fonts', array() );

			if ( !empty($google_fonts) ) {
				wp_enqueue_style( 'boilerpress-google-fonts', get_google_fonts_url($google_fonts), array(), 1 );
			}
		}

		/**
		 * Enqueue scripts and styles.
		 *
		 * @since 0.0.1
		 */
		public function scripts() {

			global $boilerpress_version;




			/**
			 * Styles
			 */
			wp_enqueue_style( 'bootstrap-style', get_template_directory_uri() . '/assets/css/vendor.css' );
			wp_enqueue_style( 'boilerpress-style', get_template_directory_uri() . '/assets/css/main.css', '', $boilerpress_version );
			wp_style_add_data( 'boilerpress-style', 'rtl', 'replace' );

			/**
			 * Scripts
			 */
			wp_enqueue_script( 'boilerpress-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array( 'jquery' ), '20151215', TRUE );
			wp_enqueue_script( 'boilerpress-js', get_template_directory_uri() . '/assets/js/main.js', array( 'jquery', 'boilerpress-plugins'), '20151215', TRUE );

			if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
				wp_enqueue_script( 'comment-reply' );
			}
		}


		/**
		 * Enqueue child theme stylesheet.
		 * A separate function is required as the child theme css needs to be enqueued _after_ the parent theme
		 * primary css and the separate WooCommerce css.
		 *
		 * @since  1.5.3
		 */
		public function child_scripts() {
			if ( is_child_theme() ) {
				wp_enqueue_style( 'boilerpress-child-style', get_stylesheet_uri(), '' );
			}
		}





		/**
		 * Filters the classes of navigation menu items. The
		 * The nav menu output is customized to match the markup used by Bootstrap 4
		 *
		 * @param $classes
		 * @param $item
		 * @param $args
		 * @param $depth
		 * @return array
		 */
		public function nav_menu_css_class( $classes, $item, $args, $depth ) {

			// menu-item               => .nav-item
			// menu-item with submenu  => .has-sub-menu
			// current menu-item       => .active
			// menu-item link          => .nav-link

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
			return $classes;
		}

		public function nav_menu_item_id( $value, $item, $args ) {
			return 'nav-item-' . $item->ID;
		}

		public function nav_menu_link_attributes( $attrs, $item, $args ) {
			$classes = array( 'nav-link' );

			if ( in_array( 'menu-item-has-children', $item->classes, true ) ) {
				$classes[]                = 'dropdown-toggle toggle-switch';
				$attrs[ 'data-toggle' ]   = 'dropdown';
				$attrs[ 'aria-expanded' ] = 'false';
				$attrs[ 'aria-haspopup' ] = 'true';
			}
			$attrs[ "class" ] = implode( ' ', $classes );
			$attrs[ "alt" ]   = $item->title;

			return $attrs;
		}

		/**
		 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
		 *
		 * @param array $args Configuration arguments.
		 * @return array
		 */
		public function page_menu_args( $args ) {
			$args['show_home'] = true;
			return $args;
		}

		/**
		 * Adds custom classes to the array of body classes.
		 *
		 * @param array $classes Classes for the body element.
		 * @return array
		 */
		public function body_classes( $classes ) {
			global $post;

			function filter_classes_array( $class = '' ) {
				if ( FALSE !== strpos($class, 'page-template') ) return FALSE;
				if ( FALSE !== strpos($class, 'page-id') ) return FALSE;
				if ( FALSE !== strpos($class, 'postid') ) return FALSE;
				return TRUE;
			}

			$classes = array_filter($classes, 'filter_classes_array');
			$classes = array_diff($classes, array( 'page', 'home' ));

			$layout = 'sidebar-right';
			$template_name = '';

			if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
				$classes[]	= 'no-wc-breadcrumb';
			}

			// If our main sidebar doesn't contain widgets, adjust the layout to be full-width.
			if ( ! is_active_sidebar( 'sidebar-1' ) ) {
				$layout = 'full-width';
			}

			// Custom classes for pages
			if ( is_page() ) {

				if ( is_front_page() ) {
					$classes[] = 'frontpage';
				}
				// Add class for page name and parent page (if there is one)
				// .page-{$page-name}
				$classes[] = 'page-' . $post->post_name;

				// .parent-{$page-name}
				if ( $post->post_parent ) {
					$classes[]   = 'parent-'. get_post( $post->post_parent )->post_name;
				}

				// Add class for page template
				if ( $template = get_page_template() ) {
					$template_name = isset( pathinfo( $template )[ 'filename' ] ) ? pathinfo( $template )[ 'filename' ] : '';
					$classes[]='page-template-template-'.$template_name.'-php';
				}
			}
			if ( 'full-width' === $template_name || is_front_page() || is_404() ) {
				$layout = 'full-width';
			}

			$classes[]= 'layout-' . $layout;

			return $classes;
		}

		/**
		 * Custom navigation markup template hooked into `navigation_markup_template` filter hook.
		 */
		public function navigation_markup_template() {
			$template  = '<nav id="post-navigation" class="navigation %1$s" role="navigation" aria-label="Post Navigation">';
			$template .= '<span class="screen-reader-text">%2$s</span>';
			$template .= '<div class="nav-links">%3$s</div>';
			$template .= '</nav>';

			return apply_filters( 'boilerpress_navigation_markup_template', $template );
		}

		/**
		 * Add styles for embeds
		 */
		public function print_embed_styles() {
			wp_enqueue_style( 'source-sans-pro', '//fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,300italic,400italic,700,900' );
			$accent_color     = get_theme_mod( 'boilerpress_accent_color' );
			$background_color = boilerpress_get_content_background_color();
			?>
			<style type="text/css">
				.wp-embed {
					padding: 2.618em !important;
					border: 0 !important;
					border-radius: 3px !important;
					font-family: "Source Sans Pro", "Open Sans", sans-serif !important;
					-webkit-font-smoothing: antialiased;
					background-color: <?php echo boilerpress_adjust_color_brightness( $background_color, -7 ); ?> !important;
				}

				.wp-embed .wp-embed-featured-image {
					margin-bottom: 2.618em;
				}

				.wp-embed .wp-embed-featured-image img,
				.wp-embed .wp-embed-featured-image.square {
					min-width: 100%;
					margin-bottom: .618em;
				}

				a.wc-embed-button {
					padding: .857em 1.387em !important;
					font-weight: 600;
					background-color: <?php echo esc_attr( $accent_color ); ?>;
					color: #fff !important;
					border: 0 !important;
					line-height: 1;
					border-radius: 0 !important;
					box-shadow:
						inset 0 -1px 0 rgba(#000,.3);
				}

				a.wc-embed-button + a.wc-embed-button {
					background-color: #60646c;
				}
			</style>
			<?php
		}

		/**
		 * Check if the passed $json variable is an array and store it into the property...
		 */
		public static function set_structured_data( $json ) {
			if ( ! is_array( $json ) ) {
				return;
			}

			self::$structured_data[] = $json;
		}

		/**
		 * If self::$structured_data is set, wrap and echo it...
		 * Hooked into the `wp_footer` action.
		 */
		public function get_structured_data() {
			if ( ! self::$structured_data ) {
				return;
			}

			$structured_data['@context'] = 'http://schema.org/';

			if ( count( self::$structured_data ) > 1 ) {
				$structured_data['@graph'] = self::$structured_data;
			} else {
				$structured_data = $structured_data + self::$structured_data[0];
			}

			$structured_data = $this->sanitize_structured_data( $structured_data );

			echo '<script type="application/ld+json">' . wp_json_encode( $structured_data ) . '</script>';
		}

		/**
		 * Sanitize structured data.
		 *
		 * @param  array $data
		 * @return array
		 */
		public function sanitize_structured_data( $data ) {
			$sanitized = array();

			foreach ( $data as $key => $value ) {
				if ( is_array( $value ) ) {
					$sanitized_value = $this->sanitize_structured_data( $value );
				} else {
					$sanitized_value = sanitize_text_field( $value );
				}

				$sanitized[ sanitize_text_field( $key ) ] = $sanitized_value;
			}

			return $sanitized;
		}

		public function wrap_embed_html( $html ) {
			return '<div class="video-container">' . $html . '</div>';
		}
	}
endif;

return new BoilerPress();
