<?php
/**
 * Contains the main password lost class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Lost password integration Class.
 *
 */
class Hizzle_reCAPTCHA_Lost_Password_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'lostpassword_form', array( $this, 'display' ) );
		add_action( 'woocommerce_lostpassword_form', array( $this, 'display' ) );
		add_action( 'lostpassword_post', array( $this, 'add_wp_error_if_invalid' ) );
	}

}
