<?php
/**
 * Contains the main register class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Register integration Class.
 *
 */
class Hizzle_reCAPTCHA_Registration_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {

		add_action( 'bp_before_registration_submit_buttons', array( $this, 'display_buddypress' ) );
		add_action( 'bp_signup_validate', array( $this, 'verify_buddypress_token' ) );
		add_action( 'woocommerce_register_form', array( $this, 'display' ) );
		add_filter( 'woocommerce_process_registration_errors', array( $this, 'add_wp_error_if_invalid' ), 99 );

		if ( ! is_multisite() ) {
			add_action( 'register_form', array( $this, 'display' ) );
			add_action( 'registration_errors', array( $this, 'add_wp_error_if_invalid' ), 99 );
		} else {
			add_action( 'signup_extra_fields', array( $this, 'display' ) );
			add_action( 'signup_blogform', array( $this, 'display' ) );
			add_filter( 'wpmu_validate_user_signup', array( $this, 'verify_multisite_token' ), 99, 3 );
		}

	}

	/**
	 * Displays on the buddpress registration form.
	 *
	 * @since 1.0.0
	 */
	public function display_buddypress() {
		do_action( 'bp_hizzle_recaptcha_errors' );
		$this->display();
	}

	/**
	 * Verifies the token for multisites.
	 *
	 * @param array $result
	 * @since 1.0.0
	 */
	public function verify_multisite_token( $result ) {
		$error = $this->is_valid();

		if ( is_wp_error( $error ) ) {
			$result['errors']->add( 'hizzle_recaptcha', esc_html( $error->get_error_message() ) );
		}

		return $result;
	}

	/**
	 * Verifies the buddpress registration token.
	 *
	 * @since 1.0.0
	 */
	public function verify_buddypress_token() {
		global $bp;

		$error = $this->is_valid();

		if ( is_wp_error( $error ) ) {
			$bp->signup->errors['hizzle_recaptcha'] = esc_html( $error->get_error_message() );
		}

	}

}
