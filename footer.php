<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link    https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package BoilerPress
 */

?>
</div> <!-- #content -->
</div>
<?php do_action( 'boilerpress_before_footer' ); ?>

<footer id="site-footer" class="site-footer footer inverse" role="contentinfo">
    <?php
    /**
     * Functions hooked in to boilerpress_footer action
     *
     * @hooked boilerpress_footer_widgets   - 10
     * @hooked boilerpress_credit           - 20
     */
    do_action( 'boilerpress_footer' ); ?>

</footer><!-- #site-footer -->

<?php do_action( 'boilerpress_after_footer' ); ?>

</div><!-- #page -->

<?php wp_footer(); ?>

</body>
</html>
