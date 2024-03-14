<?php
/**
 * Contains the main password reset class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Resetpass integration Class.
 *
 */
class Hizzle_reCAPTCHA_Resetpass_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'resetpass_form', array( $this, 'display' ) );
		add_action( 'woocommerce_resetpassword_form', array( $this, 'display' ) );
		add_action( 'validate_password_reset', array( $this, 'add_wp_error_if_invalid' ) );
	}

}
