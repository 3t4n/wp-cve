<?php
/**
 * WC_CSP_Checkout_Restriction interface
 *
 * @author   Innozilla
 * @package  Innozilla Conditional Shipping and Payments for WooCommerce
 * @since    1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Checkout Restriction Interface.
 *
 * @version 1.0.0
 */
interface WC_CSP_Checkout_Restriction {

	/**
	 * Restriction validation running on the 'woocommerce_after_checkout_validation' hook.
	 *
	 * @param  array  $posted
	 * @return void
	 */
	public function validate_checkout( $posted );
}
