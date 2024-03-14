<?php
/**
 * Contains the main integration class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Main integration Class.
 *
 */
class Hizzle_reCAPTCHA_Integration {

	/**
	 * Retrieves the checkbox html code
	 *
	 * @since 1.0.0
	 */
	public function get_html() {
		ob_start();
		$this->display();
		return ob_get_clean();
	}

	/**
	 * Displays the checkbox.
	 *
	 * @since 1.0.0
	 */
	public function display() {
		Hizzle_reCAPTCHA::$load_scripts = true;

		$attributes = array(
			'class'         => 'g-recaptcha hizzle-recaptcha',
			'style'         => 'max-width: 100%; overflow: hidden; margin-top: 10px; margin-bottom: 10px;',
			'data-sitekey'  => hizzle_recaptcha_get_option( 'site_key' ),
			'data-theme'    => 'light',
			'data-size'     => 'normal',
			'data-tabindex' => '0',
		);

		echo '<div';

		foreach ( apply_filters( 'hizzle_recaptcha_attributes', $attributes ) as $key => $value ) {
			printf(
				' %s="%s"',
				esc_attr( $key ),
				esc_attr( $value )
			);
		}

		echo '></div>';
	}

	/**
	 * Checks if the reCAPTCHA was validated.
	 *
	 * @since 1.0.0
	 * @return true|WP_Error
	 */
	protected function is_valid() {

		if ( Hizzle_reCAPTCHA::$is_valid ) {
			return true;
		}

		if ( empty( $_POST['g-recaptcha-response'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return new WP_Error( 'token_not_found', __( "Please verify that you're not a robot.", 'hizzle-recaptcha' ) );
		}

		$result = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify',
			array(
				'body' => array(
					'secret'   => hizzle_recaptcha_get_option( 'secret_key' ),
					'response' => wp_unslash( $_POST['g-recaptcha-response'] ), // phpcs:ignore WordPress.Security.NonceVerification.Missing
				),
			)
		);

		// Site not reachable.
		if ( is_wp_error( $result ) ) {
			return true;
		}

		$result = json_decode( wp_remote_retrieve_body( $result ), true );

		if ( empty( $result['success'] ) && ! in_array( 'missing-input-secret', $result['error-codes'], true ) && ! in_array( 'invalid-input-secret', $result['error-codes'], true ) ) {
			return new WP_Error( 'invalid_token', __( "Unable to verify that you're not a robot. Please try again.", 'hizzle-recaptcha' ) );
		}

		Hizzle_reCAPTCHA::$is_valid = true;
		return true;
	}

	/**
	 * Verifies the token and adds a WP Error if its invalid.
	 *
	 * @since 1.0.0
	 * @param WP_Error $validation_error
	 */
	public function add_wp_error_if_invalid( $validation_error ) {
		$error = $this->is_valid();

		if ( is_wp_error( $error ) ) {
			$validation_error->add( 'hizzle_recaptcha', esc_html( $error->get_error_message() ) );
		}

		return $validation_error;
	}

	/**
	 * Verifies the token and returns a WP Error if its invalid.
	 *
	 * @since 1.0.0
	 * @param mixed $result
	 * @return WP_Error
	 */
	public function return_wp_error_if_invalid( $result ) {
		$error = $this->is_valid();

		if ( is_wp_error( $error ) ) {
			return $error;
		}

		return $result;
	}

}
