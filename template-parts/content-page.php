<?php
/**
 * The template used for displaying page content in page.php
 *
 * @package BoilerPress
 */

?>

<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<?php
	/**
	 * Functions hooked in to boilerpress_page add_action
	 *
	 * @see boilerpress_page_header           - 10
	 * @see boilerpress_page_content          - 20
	 * @see boilerpress_init_structured_data  - 30
	 */
	do_action( 'boilerpress_page' );
	?>
</div><!-- #post-## -->