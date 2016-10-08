<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @link    https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package BoilerPress
 */

get_header(); ?>

    <div id="primary" class="content-area error-page-wrapper">

        <section class="section error-page-content">
            <div class="container">
                <div class="error-display p-y-1">
                    <span class="four-o-four">404</span>
                    <p class="error-message heading">The page could not found...</p>
                </div>
                <div class="searchbar">
                    <?php get_search_form(); ?>
                </div>
            </div>
        </section>

    </div><!-- #primary -->

<?php
get_footer();
