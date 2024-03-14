<?php

/**
*
* Misc functions.
* @version 3.2.0
*
**/

defined( 'ABSPATH' ) || exit;

/**
*
* Get the current plugin version.
* @return str
*
**/
function wceb_get_version() {
    return '3.2.0';
} 

/**
*
* Get the current version of the DB for Easy Booking.
* @return str
*
**/
function wceb_get_db_version() {
    return '3.0.0';
}

/**
*
* Return true if script debug is enabled.
* @return bool
*
**/
function wceb_script_debug() {
	return ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG );
}

/**
*
* Return an array of product types compatible with Easy Booking.
* @return array
*
**/
function wceb_get_allowed_product_types() {

	$allowed_types = array(
        'simple',
        'variable',
        'grouped',
        'bundle'
    );

    return apply_filters( 'easy_booking_allowed_product_types', $allowed_types );
    
}

/**
*
* Return the path to a file.
* Loads the file from the theme if it exists (path: easy-booking/$path/$file or easy-booking/$file)
* If it doesn't exist in the theme, loads the file from the plugin
* @param str $path - Path to the file (relative to the plugin directory)
* @param str $file - File name
* @return str $template - Complete path to the file
*
**/
function wceb_load_template( $path, $file ) {

    $template_path = 'easy-booking/';
    $template = '';

    // Get the template from the theme if it exists
    $template = locate_template( 
        array(
            $template_path . trailingslashit( $path ) . $file,
            trailingslashit( $template_path ) . $file
        )
    );

    // If it doesn't, get it from the plugin
    if ( ! $template || empty( $template ) ) {
        $template = plugin_dir_path( WCEB_PLUGIN_FILE ) . trailingslashit( $path ) . $file;
    }

    return apply_filters( 'easy_booking_load_template', $template, $path, $file );

}

/**
*
* Return the path to a script (minified or not)
* @param str $path - Admin or empty
* @param str $file - File name
* @param str $extension - File extension (js or css)
* @param constant - The plugin file (default: Easy Booking)
* @return str path to the file
*
**/
function wceb_get_file_path( $path, $file, $extension, $plugin = WCEB_PLUGIN_FILE ) {
    $path = empty( $path ) ? '' : trailingslashit( $path );
    return plugins_url( 'assets/' . trailingslashit( $extension ) . $path . WCEB_PATH . $file . WCEB_SUFFIX . '.' . $extension, $plugin );
}

/**
*
* Get localized start text.
* @param (optional) WC_Product
* @return str
*
**/
function wceb_get_start_text( $product = false ) {
    return apply_filters( 'easy_booking_start_text', __( 'Start', 'woocommerce-easy-booking-system' ), $product );
}

/**
*
* Get localized end text.
* @param (optional) WC_Product
* @return str
*
**/
function wceb_get_end_text( $product = false ) {
    return apply_filters( 'easy_booking_end_text', __( 'End', 'woocommerce-easy-booking-system' ), $product );
}

/**
*
* Get localized text to display when dates are not selected.
* @param WC_Product
* @return str
*
**/
function wceb_get_select_dates_error_message( $product ) {

    $number_of_dates = wceb_get_product_number_of_dates_to_select( $product );

    $error_message = $number_of_dates === 'one' ? __( 'Please select a date before adding this product to your cart.', 'woocommerce-easy-booking-system' ) : __( 'Please select dates before adding this product to your cart.', 'woocommerce-easy-booking-system' );

    return apply_filters( 'easy_booking_select_dates_error_message', $error_message, $product );

}

/**
*
* Sanitize frontend parameters.
* @param mixed - $param
* @param str - $func
* @return mixed - $param
*
**/
function wceb_sanitize_parameters( $param, $func ) {
    return is_array( $param ) ? array_map( $func, $param ) : $func( $param );
}

/**
*
* Sorts array by product ID.
* @return int
*
**/
function wceb_sort_by_product_id( $a, $b ) {
    return ( $a['product_id'] < $b['product_id'] ) ? -1 : 1;
}

/**
*
* Minifies CSS on-the-fly.
* @param str $css - Not minified CSS
* @return str $css - Minified CSS
*
**/
function wceb_minify_css( $css ) {

    // Remove comments
    $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

    // Remove space after colons
    $css = str_replace(': ', ':', $css);

    // Remove space before brackets
    $css = str_replace(' {', '{', $css);

    // Remove whitespace
    $css = str_replace( array( "\r\n", "\r", "\n", "\t", '  ', '    ', '    ' ), '', $css );

    return $css;

}