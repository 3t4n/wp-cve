<?php
/**
 * Contains the main CF7 class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * CF7 integration Class.
 *
 */
class Hizzle_reCAPTCHA_CF7_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'wpcf7_form_elements', array( $this, 'append_html' ) );
		add_filter( 'wpcf7_validate', array( $this, 'verify_token' ) );
	}

	/**
	 * Retrieves HTML.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function get_html() {
		return sprintf(
			'%s<span class="wpcf7-form-control-wrap hizzle-recaptcha-error"><span class="wpcf7-form-control"></span></span>',
			parent::get_html()
		);
	}

	/**
	 * Contact Form 7 form.
	 *
	 * @param string $content Content.
	 *
	 * @return string
	 */
	public function append_html( $content = '' ) {

		$content = str_replace(
			'<input type="submit"',
			$this->get_html() . '<input type="submit"',
			$content
		);
		return $content;
	}

	/**
	 * Validates form errors.
	 *
	 * @param  WPCF7_Validation $errors
	 * @return WPCF7_Validation
	 */
	public function verify_token( $result ) {

		if ( empty( $_POST['_wpcf7'] ) ) { // phpcs:ignore WordPress.Security.NonceVerification.Missing
			return $result;
		}

		$cf7_text = do_shortcode( '[contact-form-7 id="' . $_POST['_wpcf7'] . '"]' ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

		if ( false === strpos( $cf7_text, 'hizzle-recaptcha' ) ) {
			return $result;
		}

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			$result->invalidate(
				array(
					'type' => 'recaptcha',
					'name' => 'hizzle-recaptcha-error',
				),
				esc_html( $error->get_error_message() )
			);
	    }

		return $result;
	}

}
