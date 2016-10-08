<?php

/**
 * BoilerPress Template Hooks
 *
 * @package BoilerPress
 */
class Boilerpress_Template_Hooks {
    public function __construct() {
        /**
         * General
         *
         * @see  boilerpress_header_widget_region()
         * @see  boilerpress_get_sidebar()
         */
        add_action( 'boilerpress_before_content', 'boilerpress_header_widget_region', 10 );
        add_action( 'boilerpress_sidebar', 'boilerpress_get_sidebar', 10 );

        /**
         * Header
         *
         * @see  boilerpress_skip_links()
         * @see  boilerpress_secondary_navigation()
         * @see  boilerpress_site_branding()
         * @see  boilerpress_primary_navigation()
         */
        add_action( 'boilerpress_header', 'boilerpress_skip_links', 0 );
        add_action( 'boilerpress_header', 'boilerpress_site_branding', 20 );
        add_action( 'boilerpress_header', 'boilerpress_secondary_navigation', 30 );
        add_action( 'boilerpress_header', 'boilerpress_primary_navigation_wrapper', 42 );
        add_action( 'boilerpress_header', 'boilerpress_primary_navigation', 50 );
        add_action( 'boilerpress_header', 'boilerpress_primary_navigation_wrapper_close', 68 );

        /**
         * Footer
         *
         * @see  boilerpress_footer_widgets()
         * @see  boilerpress_credit()
         */
        add_action( 'boilerpress_footer', 'boilerpress_footer_widgets', 10 );
        add_action( 'boilerpress_footer', 'boilerpress_credit', 20 );


        /**
         * Homepage
         *
         * @see  boilerpress_homepage_content()
         * @see  boilerpress_product_categories()
         * @see  boilerpress_recent_products()
         * @see  boilerpress_featured_products()
         * @see  boilerpress_popular_products()
         * @see  boilerpress_on_sale_products()
         * @see  boilerpress_best_selling_products()
         */
        add_action( 'homepage', 'boilerpress_homepage_content', 10 );
        add_action( 'homepage', 'boilerpress_featured_products', 40 );


        /**
         * Posts Loop
         *
         * @see  boilerpress_post_header()
         * @see  boilerpress_post_meta()
         * @see  boilerpress_post_content()
         * @see  boilerpress_init_structured_data()
         * @see  boilerpress_paging_nav()
         */
        add_action( 'boilerpress_loop_post', 'boilerpress_post_header', 10 );
        add_action( 'boilerpress_loop_post', 'boilerpress_post_meta', 20 );
        add_action( 'boilerpress_loop_post', 'boilerpress_post_content', 30 );
        add_action( 'boilerpress_loop_post', 'boilerpress_init_structured_data', 40 );
        add_action( 'boilerpress_loop_after', 'boilerpress_paging_nav', 10 );


        /**
         * Single Posts
         * @see  boilerpress_post_header()
         * @see  boilerpress_post_content()
         * @see  boilerpress_init_structured_data()
         * @see  boilerpress_post_nav()
         * @see  boilerpress_display_comments()
         */
        add_action( 'boilerpress_single_post', 'boilerpress_post_header', 10 );
        add_action( 'boilerpress_single_post', 'boilerpress_post_content', 30 );
        add_action( 'boilerpress_single_post', 'boilerpress_init_structured_data', 40 );
        add_action( 'boilerpress_single_post_bottom', 'boilerpress_post_nav', 10 );
        add_action( 'boilerpress_single_post_bottom', 'boilerpress_display_comments', 20 );


        /**
         * Pages
         *
         * @see  boilerpress_page_header()
         * @see  boilerpress_page_content()
         * @see  boilerpress_init_structured_data()
         * @see  boilerpress_display_comments()
         */
        add_action( 'boilerpress_page', 'boilerpress_page_header', 10 );
        add_action( 'boilerpress_page', 'boilerpress_page_content', 20 );
        add_action( 'boilerpress_page', 'boilerpress_init_structured_data', 30 );
        add_action( 'boilerpress_page_after', 'boilerpress_display_comments', 10 );
    }
}

return new Boilerpress_Template_Hooks();