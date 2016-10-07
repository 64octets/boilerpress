<?php
/**
 * The template for displaying all single posts.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package BoilerPress
 */
get_header(); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

			<?php while ( have_posts() ) : the_post();

				do_action( 'boilerpress_single_post_before' );

				get_template_part( 'template-parts/content', get_post_format() ?: 'single' );

				do_action( 'boilerpress_single_post_after' );

			endwhile; // End of the loop. ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php
do_action( 'boilerpress_sidebar' );
get_footer();
