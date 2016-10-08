<?php
/**
 * Boilerpress template functions.
 *
 * @package BoilerPress
 */

if ( ! function_exists( 'boilerpress_display_comments' ) ) {
    /**
     * Boilerpress display comments
     *
     * @since 0.0.1
     */
    function boilerpress_display_comments() {
        // If comments are open or we have at least one comment, load up the comment template.
        if ( comments_open() || '0' != get_comments_number() ) :
            comments_template();
        endif;
    }
}

if ( ! function_exists( 'boilerpress_comment' ) ) {
    /**
     * Boilerpress comment template
     *
     * @since 0.0.1
     *
     * @param array $comment the comment array.
     * @param array $args    the comment args.
     * @param int   $depth   the comment depth.
     */
    function boilerpress_comment( $comment, $args, $depth ) {
        if ( 'div' == $args[ 'style' ] ) {
            $tag       = 'div';
            $add_below = 'comment';
        } else {
            $tag       = 'li';
            $add_below = 'div-comment';
        }
        ?>
        <<?php echo esc_attr( $tag ); ?><?php comment_class( empty( $args[ 'has_children' ] ) ? ''
            : 'parent' ) ?> id="comment-<?php comment_ID() ?>">
        <div class="comment-body">
        <div class="comment-meta commentmetadata">
            <div class="comment-author vcard">
                <?php echo get_avatar( $comment, 128 ); ?>
                <?php printf( wp_kses_post( '<cite class="fn">%s</cite>' ), get_comment_author_link() ); ?>
            </div>
            <?php if ( '0' == $comment->comment_approved ) : ?>
                <em class="comment-awaiting-moderation"><?php esc_attr_e( 'Your comment is awaiting moderation.', 'boilerpress' ); ?></em>
                <br/>
            <?php endif; ?>

            <a href="<?php echo esc_url( htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ); ?>" class="comment-date">
                <?php echo '<time datetime="' . get_comment_date( 'c' ) . '">' . get_comment_date() . '</time>'; ?>
            </a>
        </div>
        <?php if ( 'div' != $args[ 'style' ] ) : ?>
        <div id="div-comment-<?php comment_ID() ?>" class="comment-content">
    <?php endif; ?>
        <div class="comment-text">
            <?php comment_text(); ?>
        </div>
        <div class="reply">
            <?php comment_reply_link( array_merge( $args, array(
                'add_below' => $add_below,
                'depth'     => $depth,
                'max_depth' => $args[ 'max_depth' ]
            ) ) ); ?>
            <?php edit_comment_link( __( 'Edit', 'boilerpress' ), '  ', '' ); ?>
        </div>
        </div>
        <?php if ( 'div' != $args[ 'style' ] ) : ?>
            </div>
        <?php endif; ?>
        <?php
    }
}

if ( ! function_exists( 'boilerpress_footer_widgets' ) ) {
    /**
     * Display the footer widget regions
     *
     * @since 0.0.1
     * @return  void
     */
    function boilerpress_footer_widgets() {
        if ( is_active_sidebar( 'footer-4' ) ) {
            $widget_columns = apply_filters( 'boilerpress_footer_widget_regions', 4 );
        } elseif ( is_active_sidebar( 'footer-3' ) ) {
            $widget_columns = apply_filters( 'boilerpress_footer_widget_regions', 3 );
        } elseif ( is_active_sidebar( 'footer-2' ) ) {
            $widget_columns = apply_filters( 'boilerpress_footer_widget_regions', 2 );
        } elseif ( is_active_sidebar( 'footer-1' ) ) {
            $widget_columns = apply_filters( 'boilerpress_footer_widget_regions', 1 );
        } else {
            $widget_columns = apply_filters( 'boilerpress_footer_widget_regions', 0 );
        }

        if ( $widget_columns > 0 ) : ?>
            <div class="container">
                <div class="footer-widgets columns-<?php echo intval( $widget_columns ); ?> fix">

                    <?php
                    $i = 0;
                    while ( $i < $widget_columns ) : $i ++;
                        if ( is_active_sidebar( 'footer-' . $i ) ) : ?>

                            <div class="block footer-widget-<?php echo intval( $i ); ?>">
                                <?php dynamic_sidebar( 'footer-' . intval( $i ) ); ?>
                            </div>

                        <?php endif;
                    endwhile; ?>

                </div><!-- /.footer-widgets  -->
            </div>

        <?php endif;
    }
}

if ( ! function_exists( 'boilerpress_credit' ) ) {
    /**
     * Display the theme credit
     *
     * @since 0.0.1
     * @return void
     */
    function boilerpress_credit() {
        ?>
        <div class="site-info">
            <?php echo esc_html( apply_filters( 'boilerpress_copyright_text', '&copy; ' . get_bloginfo( 'name' ) ) );
            if ( apply_filters( 'boilerpress_credit_link', TRUE ) ) {
                printf(
                    esc_attr__( '%1$s Created By %2$s.', 'boilerpress' ),
                    '<br>',
                    '<a href="http://www.lukepeavey.com" title="" rel="author">Luke Peavey</a>'
                );
            } ?>
        </div><!-- .site-info -->
        <?php
    }
}

if ( ! function_exists( 'boilerpress_header_widget_region' ) ) {
    /**
     * Display header widget region
     *
     * @since 0.0.1
     */
    function boilerpress_header_widget_region() {
        if ( is_active_sidebar( 'header-1' ) ) {
            ?>
            <div class="header-widget-region" role="complementary">
                <div class="col-full">
                    <?php dynamic_sidebar( 'header-1' ); ?>
                </div>
            </div>
            <?php
        }
    }
}

if ( ! function_exists( 'boilerpress_site_branding' ) ) {
    /**
     * Site branding wrapper and display
     *
     * @since 0.0.1
     * @return void
     */
    function boilerpress_site_branding() {
        ?>
        <div class="site-branding">
            <?php boilerpress_site_title_or_logo(); ?>
        </div>
        <?php
    }
}

if ( ! function_exists( 'boilerpress_site_title_or_logo' ) ) {
    /**
     * Display the site title or logo
     *
     * @since 0.0.1
     * @return void
     */
    function boilerpress_site_title_or_logo( $blog_id = 0 ) {
        if ( function_exists( 'the_custom_logo' ) && has_custom_logo() ) {
            $custom_logo_id = get_theme_mod( 'custom_logo' );
            $html           = '';

            // We have a logo. Logo is go.
            if ( $custom_logo_id ) {
                $html = sprintf( '<a href="%1$s" class="logo-link" rel="home" itemprop="url">%2$s</a>',
                    esc_url( home_url( '/' ) ),
                    wp_get_attachment_image( $custom_logo_id, 'full', FALSE, array(
                        'class'    => 'logo',
                        'itemprop' => 'logo',
                    ) )
                );
            } // If no logo is set but we're in the Customizer, leave a placeholder (needed for the live preview).
            elseif ( is_customize_preview() ) {
                $html =
                    sprintf( '<a href="%1$s" class="custom-logo-link" style="display:none;"><img class="custom-logo"/></a>',
                        esc_url( home_url( '/' ) )
                    );
            }
            echo $html;

        } else {
            $tag = is_home() ? 'h1' : 'div';

            echo '<' . esc_attr( $tag ) . ' class="beta site-title"><a href="' . esc_url( home_url( '/' ) ) . '" rel="home">' . esc_attr( get_bloginfo( 'name' ) ) . '</a></' . esc_attr( $tag ) . '>';

            if ( '' != get_bloginfo( 'description' ) ) { ?>
                <p class="site-description"><?php echo bloginfo( 'description' ); ?></p>
                <?php
            }
        }
    }
}

if ( ! function_exists( 'boilerpress_primary_navigation' ) ) {
    /**
     * Display Primary Navigation
     *
     * @since 0.0.1
     * @return void
     */
    function boilerpress_primary_navigation() {
        ?>
        <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_html_e( 'Primary Navigation', 'boilerpress' ); ?>">
            <?php
            do_action( 'boilerpress_before_primary_nav' );

            wp_nav_menu( array(
                'theme_location' => 'primary',
                'menu_id'        => 'main-menu',
                'menu_class'     => 'nav navbar-nav',
                'container'      => FALSE
            ) );

            do_action( 'boilerpress_after_primary_nav' );
            ?>
        </nav><!-- #site-navigation -->

        <button id="nav-toggle" class="nav-toggle toggle-switch" type="button" data-toggle="" data-target="#main-navigation" aria-controls="main-navigation" aria-expanded="false" tabindex="0">
            <div class="menu-icon">
                <span class="menu-icon-bar menu-icon-bar-top"><span class="menu-icon-bar-inner menu-icon-bar-inner-top"></span></span>
                <span class="menu-icon-bar menu-icon-bar-bottom"><span class="menu-icon-bar-inner menu-icon-bar-inner-bottom"></span></span>
            </div>
        </button> <!-- #nav-toggle -->
        <?php
    }
}

if ( ! function_exists( 'boilerpress_secondary_navigation' ) ) {
    /**
     * Display Secondary Navigation
     *
     * @since 0.0.1
     * @return void
     */
    function boilerpress_secondary_navigation() {
        if ( has_nav_menu( 'secondary' ) ) {
            ?>
            <nav class="secondary-navigation" role="navigation" aria-label="<?php esc_html_e( 'Secondary Navigation', 'boilerpress' ); ?>">
                <?php
                wp_nav_menu(
                    array(
                        'theme_location' => 'secondary',
                        'fallback_cb'    => '',
                    )
                );
                ?>
            </nav><!-- #site-navigation -->
            <?php
        }
    }
}

if ( ! function_exists( 'boilerpress_skip_links' ) ) {
    /**
     * Skip links
     *
     * @since  1.4.1
     * @return void
     */
    function boilerpress_skip_links() {
        ?>
        <a class="skip-link screen-reader-text" href="#site-navigation"><?php esc_attr_e( 'Skip to navigation', 'boilerpress' ); ?></a>
        <a class="skip-link screen-reader-text" href="#content"><?php esc_attr_e( 'Skip to content', 'boilerpress' ); ?></a>
        <?php
    }
}

if ( ! function_exists( 'boilerpress_page_header' ) ) {
    /**
     * Display the post header with a link to the single post
     *
     * @uses  boilerpress_post_thumbnail
     *
     * @since 0.0.1
     */
    function boilerpress_page_header() {
        if ( get_post_meta( get_the_ID(), 'bp_show_title_bar', TRUE ) ) :
            ?>
            <header class="entry-header page-header">
                <?php
                boilerpress_post_thumbnail( 'banner' );
                the_title( '<h1 class="entry-title">', '</h1>' );
                ?>
            </header><!-- .entry-header -->
            <?php
        endif;
    }
}

if ( ! function_exists( 'boilerpress_page_content' ) ) {
    /**
     * Display the post content with a link to the single post
     *
     * @since 0.0.1
     */
    function boilerpress_page_content() {
        ?>
        <div class="entry-content">
            <?php the_content(); ?>
            <?php
            wp_link_pages( array(
                'before' => '<div class="page-links">' . __( 'Pages:', 'boilerpress' ),
                'after'  => '</div>',
            ) );
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if ( ! function_exists( 'boilerpress_post_header' ) ) {
    /**
     * Display the post header with a link to the single post
     *
     * @since 0.0.1
     */
    function boilerpress_post_header() {
        ?>
        <header class="entry-header">
            <?php
            if ( is_single() ) {
                boilerpress_posted_on();
                the_title( '<h1 class="entry-title">', '</h1>' );
            } else {
                if ( 'post' == get_post_type() ) {
                    boilerpress_posted_on();
                }

                the_title( sprintf( '<h2 class="alpha entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' );
            }
            ?>
        </header><!-- .entry-header -->
        <?php
    }
}

if ( ! function_exists( 'boilerpress_post_content' ) ) {
    /**
     * Display the post content with a link to the single post
     *
     * @since 0.0.1
     */
    function boilerpress_post_content() {
        ?>
        <div class="entry-content">
            <?php
            boilerpress_post_thumbnail( 'full' );
            if ( is_single() ) {
                the_content(
                    sprintf(
                        __( 'Continue reading %s', 'boilerpress' ),
                        '<span class="screen-reader-text">' . get_the_title() . '</span>'
                    )
                );

                wp_link_pages( array(
                    'before' => '<div class="page-links">' . __( 'Pages:', 'boilerpress' ),
                    'after'  => '</div>',
                ) );
            } else {
                the_excerpt();
            }
            ?>
        </div><!-- .entry-content -->
        <?php
    }
}

if ( ! function_exists( 'boilerpress_post_meta' ) ) {

    /**
     * Display the post meta
     *
     * @since 0.0.1
     */
    function boilerpress_post_meta() {
        ?>
        <aside class="entry-meta">
            <?php if ( 'post' == get_post_type() ) : // Hide category and tag text for pages on Search.

                ?>
                <div class="author">
                    <?php
                    echo get_avatar( get_the_author_meta( 'ID' ), 128 );
                    echo '<div class="label">' . esc_attr( __( 'Written by', 'boilerpress' ) ) . '</div>';
                    the_author_posts_link();
                    ?>
                </div>
                <?php
                /* translators: used between list items, there is a space after the comma */
                $categories_list = get_the_category_list( __( ', ', 'boilerpress' ) );

                if ( $categories_list ) : ?>
                    <div class="cat-links">
                        <?php
                        echo '<div class="label">' . esc_attr( __( 'Posted in', 'boilerpress' ) ) . '</div>';
                        echo wp_kses_post( $categories_list );
                        ?>
                    </div>
                <?php endif; // End if categories.
                ?>

                <?php
                /* translators: used between list items, there is a space after the comma */
                $tags_list = get_the_tag_list( '', __( ', ', 'boilerpress' ) );

                if ( $tags_list ) : ?>
                    <div class="tags-links">
                        <?php
                        echo '<div class="label">' . esc_attr( __( 'Tagged', 'boilerpress' ) ) . '</div>';
                        echo wp_kses_post( $tags_list );
                        ?>
                    </div>
                <?php endif; // End if $tags_list.
                ?>

            <?php endif; // End if 'post' == get_post_type(). ?>

            <?php if ( ! post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
                <div class="comments-link">
                    <?php echo '<div class="label">' . esc_attr( __( 'Comments', 'boilerpress' ) ) . '</div>'; ?>
                    <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'boilerpress' ), __( '1 Comment', 'boilerpress' ), __( '% Comments', 'boilerpress' ) ); ?></span>
                </div>
            <?php endif; ?>
        </aside>
        <?php
    }
}

if ( ! function_exists( 'boilerpress_paging_nav' ) ) {
    /**
     * Display navigation to next/previous set of posts when applicable.
     */
    function boilerpress_paging_nav() {
        global $wp_query;

        $args = array(
            'type'      => 'list',
            'next_text' => _x( 'Next', 'Next post', 'boilerpress' ),
            'prev_text' => _x( 'Previous', 'Previous post', 'boilerpress' ),
        );

        the_posts_pagination( $args );
    }
}

if ( ! function_exists( 'boilerpress_post_nav' ) ) {
    /**
     * Display navigation to next/previous post when applicable.
     */
    function boilerpress_post_nav() {
        $args = array(
            'next_text' => '%title',
            'prev_text' => '%title',
        );
        the_post_navigation( $args );
    }
}

if ( ! function_exists( 'boilerpress_posted_on' ) ) {
    /**
     * Prints HTML with meta information for the current post-date/time and author.
     */
    function boilerpress_posted_on() {
        $time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
        if ( get_the_time( 'U' ) !== get_the_modified_time( 'U' ) ) {
            $time_string =
                '<time class="entry-date published" datetime="%1$s">%2$s</time> <time class="updated" datetime="%3$s">%4$s</time>';
        }

        $time_string = sprintf( $time_string,
            esc_attr( get_the_date( 'c' ) ),
            esc_html( get_the_date() ),
            esc_attr( get_the_modified_date( 'c' ) ),
            esc_html( get_the_modified_date() )
        );

        $posted_on = sprintf(
            _x( 'Posted on %s', 'post date', 'boilerpress' ),
            '<a href="' . esc_url( get_permalink() ) . '" rel="bookmark">' . $time_string . '</a>'
        );

        echo wp_kses( apply_filters( 'boilerpress_single_post_posted_on_html', '<span class="posted-on">' . $posted_on . '</span>', $posted_on ), array(
            'span' => array(
                'class' => array(),
            ),
            'a'    => array(
                'href'  => array(),
                'title' => array(),
                'rel'   => array(),
            ),
            'time' => array(
                'datetime' => array(),
                'class'    => array(),
            ),
        ) );
    }
}

if ( ! function_exists( 'boilerpress_product_categories' ) ) {
    /**
     * Display Product Categories
     * Hooked into the `homepage` action in the homepage template
     *
     * @since 0.0.1
     *
     * @param array $args the product section args.
     *
     * @return void
     */
    function boilerpress_product_categories( $args ) {

        if ( is_woocommerce_activated() ) {

            $args = apply_filters( 'boilerpress_product_categories_args', array(
                'limit'            => 3,
                'columns'          => 3,
                'child_categories' => 0,
                'orderby'          => 'name',
                'title'            => __( 'Shop by Category', 'boilerpress' ),
            ) );

            echo '<section class="boilerpress-product-section boilerpress-product-categories" aria-label="Product Categories">';

            do_action( 'boilerpress_homepage_before_product_categories' );

            echo '<h2 class="section-title">' . wp_kses_post( $args[ 'title' ] ) . '</h2>';

            do_action( 'boilerpress_homepage_after_product_categories_title' );

            echo boilerpress_do_shortcode( 'product_categories', array(
                'number'  => intval( $args[ 'limit' ] ),
                'columns' => intval( $args[ 'columns' ] ),
                'orderby' => esc_attr( $args[ 'orderby' ] ),
                'parent'  => esc_attr( $args[ 'child_categories' ] ),
            ) );

            do_action( 'boilerpress_homepage_after_product_categories' );

            echo '</section>';
        }
    }
}

if ( ! function_exists( 'boilerpress_recent_products' ) ) {
    /**
     * Display Recent Products
     * Hooked into the `homepage` action in the homepage template
     *
     * @since 0.0.1
     *
     * @param array $args the product section args.
     *
     * @return void
     */
    function boilerpress_recent_products( $args ) {

        if ( is_woocommerce_activated() ) {

            $args = apply_filters( 'boilerpress_recent_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'title'   => __( 'New In', 'boilerpress' ),
            ) );

            echo '<section class="boilerpress-product-section boilerpress-recent-products" aria-label="Recent Products">';

            do_action( 'boilerpress_homepage_before_recent_products' );

            echo '<h2 class="section-title">' . wp_kses_post( $args[ 'title' ] ) . '</h2>';

            do_action( 'boilerpress_homepage_after_recent_products_title' );

            echo boilerpress_do_shortcode( 'recent_products', array(
                'per_page' => intval( $args[ 'limit' ] ),
                'columns'  => intval( $args[ 'columns' ] ),
            ) );

            do_action( 'boilerpress_homepage_after_recent_products' );

            echo '</section>';
        }
    }
}

if ( ! function_exists( 'boilerpress_featured_products' ) ) {
    /**
     * Display Featured Products
     * Hooked into the `homepage` action in the homepage template
     *
     * @since 0.0.1
     *
     * @param array $args the product section args.
     *
     * @return void
     */
    function boilerpress_featured_products( $args ) {

        if ( is_woocommerce_activated() ) {

            $args = apply_filters( 'boilerpress_featured_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'orderby' => 'date',
                'order'   => 'desc',
                'title'   => __( 'We Recommend', 'boilerpress' ),
            ) );

            echo '<section class="boilerpress-product-section boilerpress-featured-products" aria-label="Featured Products">';

            do_action( 'boilerpress_homepage_before_featured_products' );

            echo '<h2 class="section-title">' . wp_kses_post( $args[ 'title' ] ) . '</h2>';

            do_action( 'boilerpress_homepage_after_featured_products_title' );

            echo boilerpress_do_shortcode( 'featured_products', array(
                'per_page' => intval( $args[ 'limit' ] ),
                'columns'  => intval( $args[ 'columns' ] ),
                'orderby'  => esc_attr( $args[ 'orderby' ] ),
                'order'    => esc_attr( $args[ 'order' ] ),
            ) );

            do_action( 'boilerpress_homepage_after_featured_products' );

            echo '</section>';
        }
    }
}

if ( ! function_exists( 'boilerpress_popular_products' ) ) {
    /**
     * Display Popular Products
     * Hooked into the `homepage` action in the homepage template
     *
     * @since 0.0.1
     *
     * @param array $args the product section args.
     *
     * @return void
     */
    function boilerpress_popular_products( $args ) {

        if ( is_woocommerce_activated() ) {

            $args = apply_filters( 'boilerpress_popular_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'title'   => __( 'Fan Favorites', 'boilerpress' ),
            ) );

            echo '<section class="boilerpress-product-section boilerpress-popular-products" aria-label="Popular Products">';

            do_action( 'boilerpress_homepage_before_popular_products' );

            echo '<h2 class="section-title">' . wp_kses_post( $args[ 'title' ] ) . '</h2>';

            do_action( 'boilerpress_homepage_after_popular_products_title' );

            echo boilerpress_do_shortcode( 'top_rated_products', array(
                'per_page' => intval( $args[ 'limit' ] ),
                'columns'  => intval( $args[ 'columns' ] ),
            ) );

            do_action( 'boilerpress_homepage_after_popular_products' );

            echo '</section>';
        }
    }
}

if ( ! function_exists( 'boilerpress_on_sale_products' ) ) {
    /**
     * Display On Sale Products
     * Hooked into the `homepage` action in the homepage template
     *
     * @param array $args the product section args.
     *
     * @since 0.0.1
     * @return void
     */
    function boilerpress_on_sale_products( $args ) {

        if ( is_woocommerce_activated() ) {

            $args = apply_filters( 'boilerpress_on_sale_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'title'   => __( 'On Sale', 'boilerpress' ),
            ) );

            echo '<section class="boilerpress-product-section boilerpress-on-sale-products" aria-label="On Sale Products">';

            do_action( 'boilerpress_homepage_before_on_sale_products' );

            echo '<h2 class="section-title">' . wp_kses_post( $args[ 'title' ] ) . '</h2>';

            do_action( 'boilerpress_homepage_after_on_sale_products_title' );

            echo boilerpress_do_shortcode( 'sale_products', array(
                'per_page' => intval( $args[ 'limit' ] ),
                'columns'  => intval( $args[ 'columns' ] ),
            ) );

            do_action( 'boilerpress_homepage_after_on_sale_products' );

            echo '</section>';
        }
    }
}

if ( ! function_exists( 'boilerpress_best_selling_products' ) ) {
    /**
     * Display Best Selling Products
     * Hooked into the `homepage` action in the homepage template
     *
     * @since 0.0.1
     *
     * @param array $args the product section args.
     *
     * @return void
     */
    function boilerpress_best_selling_products( $args ) {
        if ( is_woocommerce_activated() ) {
            $args = apply_filters( 'boilerpress_best_selling_products_args', array(
                'limit'   => 4,
                'columns' => 4,
                'title'   => esc_attr__( 'Best Sellers', 'boilerpress' ),
            ) );
            echo '<section class="boilerpress-product-section boilerpress-best-selling-products" aria-label="Best Selling Products">';
            do_action( 'boilerpress_homepage_before_best_selling_products' );
            echo '<h2 class="section-title">' . wp_kses_post( $args[ 'title' ] ) . '</h2>';
            do_action( 'boilerpress_homepage_after_best_selling_products_title' );
            echo boilerpress_do_shortcode( 'best_selling_products', array(
                'per_page' => intval( $args[ 'limit' ] ),
                'columns'  => intval( $args[ 'columns' ] ),
            ) );
            do_action( 'boilerpress_homepage_after_best_selling_products' );
            echo '</section>';
        }
    }
}

if ( ! function_exists( 'boilerpress_homepage_content' ) ) {
    /**
     * Display homepage content
     * Hooked into the `homepage` action in the homepage template
     *
     * @since 0.0.1
     * @return  void
     */
    function boilerpress_homepage_content() {
        while ( have_posts() ) : the_post();

            the_content();

        endwhile;
    }
}

if ( ! function_exists( 'boilerpress_social_icons' ) ) {
    /**
     * Display social icons
     * If the subscribe and connect plugin is active, display the icons.
     *
     * @link  http://wordpress.org/plugins/subscribe-and-connect/
     * @since 0.0.1
     */
    function boilerpress_social_icons() {
        if ( class_exists( 'Subscribe_And_Connect' ) ) {
            echo '<div class="subscribe-and-connect-connect">';
            subscribe_and_connect_connect();
            echo '</div>';
        }
    }
}

if ( ! function_exists( 'boilerpress_get_sidebar' ) ) {
    /**
     * Display boilerpress sidebar
     *
     * @uses  get_sidebar()
     * @since 0.0.1
     */
    function boilerpress_get_sidebar() {
        get_sidebar();
    }
}

if ( ! function_exists( 'boilerpress_post_thumbnail' ) ) {
    /**
     * Display post thumbnail
     *
     * @uses  has_post_thumbnail()
     * @uses  the_post_thumbnail()
     *
     * @param string $size the post thumbnail size.
     *
     * @since 0.0.1
     */
    function boilerpress_post_thumbnail( $size ) {
        if ( has_post_thumbnail() ) {
            the_post_thumbnail( $size );
        }
    }
}

if ( ! function_exists( 'boilerpress_primary_navigation_wrapper' ) ) {
    /**
     * The primary navigation wrapper
     */
    function boilerpress_primary_navigation_wrapper() {
        echo '<div class="boilerpress-primary-navigation">';
    }
}

if ( ! function_exists( 'boilerpress_primary_navigation_wrapper_close' ) ) {
    /**
     * The primary navigation wrapper close
     */
    function boilerpress_primary_navigation_wrapper_close() {
        echo '</div>';
    }
}

if ( ! function_exists( 'boilerpress_init_structured_data' ) ) {
    /**
     * Generate the structured data...
     * Initialize Boilerpress::$structured_data via Boilerpress::set_structured_data()...
     * Hooked into:
     * `boilerpress_loop_post`
     * `boilerpress_single_post`
     * `boilerpress_page`
     * Apply `boilerpress_structured_data` filter hook for structured data customization :)
     */
    function boilerpress_init_structured_data() {
        if ( is_home() || is_category() || is_date() || is_search() || is_single() && ( is_woocommerce_activated() && ! is_woocommerce() ) ) {
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID() ), 'normal' );
            $logo  = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' );

            $json[ '@type' ] = 'BlogPosting';

            $json[ 'mainEntityOfPage' ] = array(
                '@type' => 'webpage',
                '@id'   => get_the_permalink(),
            );

            $json[ 'image' ] = array(
                '@type'  => 'ImageObject',
                'url'    => $image[ 0 ],
                'width'  => $image[ 1 ],
                'height' => $image[ 2 ],
            );

            $json[ 'publisher' ] = array(
                '@type' => 'organization',
                'name'  => get_bloginfo( 'name' ),
                'logo'  => array(
                    '@type'  => 'ImageObject',
                    'url'    => $logo[ 0 ],
                    'width'  => $logo[ 1 ],
                    'height' => $logo[ 2 ],
                ),
            );

            $json[ 'author' ] = array(
                '@type' => 'person',
                'name'  => get_the_author(),
            );

            $json[ 'datePublished' ] = get_post_time( 'c' );
            $json[ 'dateModified' ]  = get_the_modified_date( 'c' );
            $json[ 'name' ]          = get_the_title();
            $json[ 'headline' ]      = get_the_title();
            $json[ 'description' ]   = get_the_excerpt();
        } elseif ( is_page() ) {
            $json[ '@type' ]       = 'WebPage';
            $json[ 'url' ]         = get_the_permalink();
            $json[ 'name' ]        = get_the_title();
            $json[ 'description' ] = get_the_excerpt();
        }

        if ( isset( $json ) ) {
            BoilerPress::set_structured_data( apply_filters( 'boilerpress_structured_data', $json ) );
        }
    }
}

if ( ! function_exists( 'boilerpress_categorized_blog' ) ) {

    /**
     * Returns true if a blog has more than 1 category.
     *
     * @return bool
     */
    function boilerpress_categorized_blog() {
        if ( FALSE === ( $all_the_cool_cats = get_transient( 'boilerpress_categories' ) ) ) {
            // Create an array of all the categories that are attached to posts.
            $all_the_cool_cats = get_categories(
                array(
                    'fields'     => 'ids',
                    'hide_empty' => 1,
                    // We only need to know if there is more than one category.
                    'number'     => 2,
                )
            );

            // Count the number of categories that are attached to the posts.
            $all_the_cool_cats = count( $all_the_cool_cats );

            set_transient( 'boilerpress_categories', $all_the_cool_cats );
        }

        if ( $all_the_cool_cats > 1 ) {
            // This blog has more than 1 category so boilerpress_categorized_blog should return true.
            return TRUE;
        } else {
            // This blog has only 1 category so boilerpress_categorized_blog should return false.
            return FALSE;
        }
    }
}

if ( ! function_exists( 'boilerpress_category_transient_flusher' ) ) {
    /**
     * Flush out the transients used in boilerpress_categorized_blog.
     */
    function boilerpress_category_transient_flusher() {
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        // Like, beat it. Dig?
        delete_transient( 'boilerpress_categories' );
    }
}