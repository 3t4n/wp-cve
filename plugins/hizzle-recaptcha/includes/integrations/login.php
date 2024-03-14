<?php
/**
 * Contains the main login class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Login integration Class.
 *
 */
class Hizzle_reCAPTCHA_Login_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'login_form', array( $this, 'display' ) );
		add_action( 'woocommerce_login_form', array( $this, 'display' ) );
		add_filter( 'woocommerce_process_login_errors', array( $this, 'add_wp_error_if_invalid' ), 99 );
		add_action( 'authenticate', array( $this, 'confirm_login' ), 99 );
	}

	/**
	 * Displays the checkbox.
	 *
	 * @since 1.0.0
	 */
	public function confirm_login( $user ) {

		if ( is_wp_error( $user ) || defined( 'XMLRPC_REQUEST' ) || empty( $_POST['log'] ) || empty( $_POST['pwd'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return $user;
		}

		$error = $this->is_valid();

		if ( is_wp_error( $error ) ) {
			wp_clear_auth_cookie();
			return $error;
		}

		return $user;

	}

}
