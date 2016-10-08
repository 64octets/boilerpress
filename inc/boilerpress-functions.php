<?php
/**
 * BoilerPress  functions.
 *
 * @package BoilerPress
 */

/**
 * Query WooCommerce activation
 */
function is_woocommerce_activated() {
    return class_exists( 'woocommerce' ) ? TRUE : FALSE;
}

/**
 * Call a shortcode function by tag name.
 *
 * @since  1.4.6
 *
 * @param string $tag     The shortcode whose function to call.
 * @param array  $atts    The attributes to pass to the shortcode function. Optional.
 * @param array  $content The shortcode's content. Default is null (none).
 *
 * @return string|bool False on failure, the result of the shortcode on success.
 */
function boilerpress_do_shortcode( $tag, array $atts = array(), $content = NULL ) {
    global $shortcode_tags;

    if ( ! isset( $shortcode_tags[ $tag ] ) ) {
        return FALSE;
    }

    return call_user_func( $shortcode_tags[ $tag ], $atts, $content, $tag );
}


/**
 * Adjust a hex color brightness
 * Allows us to create hover styles for custom link colors
 *
 * @param  strong  $hex   hex color e.g. #111111.
 * @param  integer $steps factor by which to brighten/darken ranging from -255 (darken) to 255 (brighten).
 *
 * @return string        brightened/darkened hex color
 * @since 0.0.1
 */
function boilerpress_adjust_color_brightness( $hex, $steps ) {
    // Steps should be between -255 and 255. Negative = darker, positive = lighter.
    $steps = max( - 255, min( 255, $steps ) );

    // Format the hex color string.
    $hex = str_replace( '#', '', $hex );

    if ( 3 == strlen( $hex ) ) {
        $hex =
            str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
    }

    // Get decimal values.
    $r = hexdec( substr( $hex, 0, 2 ) );
    $g = hexdec( substr( $hex, 2, 2 ) );
    $b = hexdec( substr( $hex, 4, 2 ) );

    // Adjust number of steps and keep it inside 0 to 255.
    $r = max( 0, min( 255, $r + $steps ) );
    $g = max( 0, min( 255, $g + $steps ) );
    $b = max( 0, min( 255, $b + $steps ) );

    $r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
    $g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
    $b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

    return '#' . $r_hex . $g_hex . $b_hex;
}

/**
 * Checkbox sanitization callback.
 *
 * Sanitization callback for 'checkbox' type controls. This callback sanitizes `$checked`
 * as a boolean value, either TRUE or FALSE.
 *
 * @param bool $checked Whether the checkbox is checked.
 *
 * @return bool Whether the checkbox is checked.
 * @since 0.0.1
 */
function boilerpress_sanitize_checkbox( $checked ) {
    return ( ( isset( $checked ) && TRUE == $checked ) ? TRUE : FALSE );
}

/**
 * Returns the "embed code" used to load fonts from Typekit.
 * The "embed code" is a snippet of javascript loads the webfonts in a specific kit.
 *
 * @param $kit_id
 *
 * @return string
 */
function get_typekit_embed_code( $kit_id ) {
    return '<script src="https://use.typekit.net/' . $kit_id . '.js"></script>' . '<script>try{Typekit.load({
	async: true });}catch(e){}</script>';
}

/**
 * Generates google fonts url string to load the specified font families and styles.
 *
 * @param $font_families array
 *
 * @return string
 *
 * @example
 * $font-families = array(
 *     'playfair'        => 'Playball',
 *     'dosis'           => 'Dosis:400, 400italic, 500, 500italic',
 *     'roboto-condensed => 'Roboto+Condensed:300, 400, 600
 *
 *  )
 */
function get_google_fonts_url( array $font_families ) {
    $query_args = array( 'family' => implode( '|', $font_families ), 'subset' => urlencode( 'latin,latin-ext' ) );

    return add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
}