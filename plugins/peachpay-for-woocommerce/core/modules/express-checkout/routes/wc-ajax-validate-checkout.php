<?php
/**
 * WC ajax request for validating a PeachPay express checkout customer data.
 *
 * @package PeachPay
 */

defined( 'ABSPATH' ) || exit;

/**
 * Ajax hook for validating checkout field addresses (shipping & billing).
 */
function pp_checkout_wc_ajax_validate_checkout() {
	// phpcs:ignore
	$request = $_POST;
    //PHPCS:ignore
	$request['ship_to_different_address'] = isset( $_POST['ship_to_different_address'] ) ;

	apply_filters( 'peachpay_validation_checks', $request );

	include_once PEACHPAY_ABSPATH . 'core/modules/express-checkout/util/class-peachpay-wc-checkout.php';
	$checkout_validator = new PeachPay_WC_Checkout();
	$errors             = new WP_Error();
	$checkout_validator->validate_posted_data( $request, $errors );
	if ( $errors->has_errors() ) {
		wp_send_json(
			array(
				'success'        => false,
				'error_messages' => $errors->get_error_messages(),
				'notices'        => wc_get_notices(),
			)
		);
	}

	wp_send_json(
		array(
			'success' => true,
			'notices' => wc_get_notices(),
		)
	);
}
