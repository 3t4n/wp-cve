<?php
/**
 * Contains the main bbpress reply class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * bbpress reply integration Class.
 *
 */
class Hizzle_reCAPTCHA_bbPress_Reply_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'bbp_theme_before_reply_form_submit_wrapper', array( $this, 'display' ) );
		add_action( 'bbp_new_reply_pre_extras', array( $this, 'verify_token' ) );
	}

	/**
	 * Handles the submission of groups.
	 *
	 * @since 1.0.0
	 */
	public function verify_token() {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			bbp_add_error( 'hizzle_recaptcha', esc_html( $error->get_error_message() ) );
	    }

	}

}
