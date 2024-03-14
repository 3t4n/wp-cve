<?php
/**
 * Add filter to WooCommerce redirect.
 *
 * @author Kunoichi INC
 */

// Only redirect.
defined( 'ABSPATH' ) || die();

// If already loaded, simply return.
if ( defined( 'REDIRECT_TO_WOOCOMMERCE' ) ) {
	return;
}
define( 'REDIRECT_TO_WOOCOMMERCE', true );

/**
 * Add redirect_to field.
 */
function redirect_to_woocommerce_input_field() {
	$redirect_to = trim( filter_input( INPUT_GET, 'redirect_to' ) );
	if ( $redirect_to ) {
		printf( '<input type="hidden" name="redirect" value="%s" />', esc_attr( $redirect_to ) );
	}
}
add_action( 'woocommerce_login_form_end', 'redirect_to_woocommerce_input_field' );
add_action( 'woocommerce_register_form_end', 'redirect_to_woocommerce_input_field' );
