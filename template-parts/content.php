<?php
/**
 * Template part for displaying posts.
 *
 * @link    https://codex.wordpress.org/Template_Hierarchy
 *
 * @package BoilerPress
 */

?>

<article id="post-<?php the_ID(); ?>" <?php post_class( 'CILFFFFF' ); ?>>

    <?php
    /**
     * Functions hooked in to boilerpress_loop_post action.
     *
     * @hooked boilerpress_post_header          - 10
     * @hooked boilerpress_post_meta            - 20
     * @hooked boilerpress_post_content         - 30
     * @hooked boilerpress_init_structured_data - 40
     */
    do_action( 'boilerpress_loop_post' );
    ?>

</article><!-- #post-## -->