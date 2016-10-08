<?php
/**
 * Template part for displaying results in search pages.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BoilerPress
 */

?>
<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

    <?php
    do_action( 'boilerpress_single_post_top' );

    /**
     * Functions hooked into boilerpress_single_post action
     * @see    Boilerpress_Template_Hooks
     *
     * @hooked boilerpress_post_header            - 10
     * @hooked boilerpress_post_content           - 30
     * @hooked boilerpress_init_structured_data   - 40
     *
     */
    do_action( 'boilerpress_single_post' );

    /**
     * Functions hooked in to boilerpress_single_post_after action
     * @see    Boilerpress_Template_Hooks
     *
     * @hooked boilerpress_post_nav              - 10
     * @hooked boilerpress_display_comments      - 20
     *
     */
    do_action( 'boilerpress_single_post_bottom' );
    ?>

</article><!-- #post-## -->