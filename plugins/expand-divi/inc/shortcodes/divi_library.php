<?php
// exit when accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Divi library shortcode
 *
 */
function expand_divi_library_shortcode( $atts ) {
	return do_shortcode( '[et_pb_section global_module="' . absint( $atts['id'] ) . '"][/et_pb_section]' );
}
add_shortcode( 'ed_library', 'expand_divi_library_shortcode');