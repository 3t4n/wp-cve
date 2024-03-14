<?php
/**
 * Validate checkout fields.
 *
 * @package    WooCommerce
 * @category   Payment Gateways
 * @author     Revolut
 * @since      2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * WC_Revolut_Validate_Checkout class.
 */
class WC_Revolut_Validate_Checkout extends WC_Checkout {

	/**
	 * Validate Checkout fields.
	 */
	public function validate_checkout_fields() {
		$errors      = new WP_Error();
		$posted_data = $this->get_posted_data();

		// Update session for customer and totals.
		$this->update_session( $posted_data );

		// Validate posted data and cart items before proceeding.
		$this->validate_checkout( $posted_data, $errors );

		foreach ( $errors->errors as $code => $messages ) {
			$data = $errors->get_error_data( $code );
			foreach ( $messages as $message ) {
				wc_add_notice( $message, 'error', $data );
			}
		}
	}

	/**
	 * Return failure response.
	 */
	public function return_ajax_failure_response() {
		$this->send_ajax_failure_response();
	}
}
