<?php
/**
 * Contains the main woocommerce class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WooCommerce integration Class.
 *
 */
class Hizzle_reCAPTCHA_WooCommerce_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'woocommerce_review_order_before_payment', array( $this, 'display' ) );
		add_action( 'woocommerce_checkout_process', array( $this, 'verify_token' ) );
	}

	/**
	 * Handles the submission of comments.
	 *
	 * @since 1.0.0
	 */
	public function verify_token() {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			throw new Exception( $error->get_error_message() );
	    }

	}

}
