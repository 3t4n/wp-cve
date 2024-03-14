<?php
/**
 * Contains the main MailChimp 4 WP class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * MailChimp 4 WP integration Class.
 *
 */
class Hizzle_reCAPTCHA_Mailchimp_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
		add_filter( 'mc4wp_form_messages', array( $this, 'add_error_message' ) );
		add_filter( 'mc4wp_form_content', array( $this, 'append_html' ) );
		add_filter( 'mc4wp_form_errors', array( $this, 'verify_token' ), 10, 3 );
	}

	/**
	 * Add MailChimp form error message.
	 *
	 * @param array $messages Messages.
	 *
	 * @return mixed
	 */
	public function add_error_message( $messages ) {
		$messages['hizzle_recaptcha'] = array(
			'type' => 'error',
			'text' => __( "Unable to verify that you're not a robot.", 'hizzle-recaptcha' ),
		);

		return $messages;
	}

	/**
	 * MailChimp form.
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
	 * @param  array $errors
	 * @return array|null
	 */
	public function verify_token( $errors ) {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			$errors[] = 'hizzle_recaptcha';
	    }

		return $errors;
	}

}
