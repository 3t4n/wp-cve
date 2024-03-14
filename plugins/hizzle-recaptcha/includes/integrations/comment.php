<?php
/**
 * Contains the main comment class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Comment integration Class.
 *
 */
class Hizzle_reCAPTCHA_Comment_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_filter( 'comment_form_submit_field', array( $this, 'append_checkbox' ) );
		add_action( 'pre_comment_on_post', array( $this, 'verify_token' ) );
	}

	/**
	 * Displays the checkbox.
	 *
	 * @since 1.0.0
	 */
	public function append_checkbox( $text ) {
		return $this->get_html() . PHP_EOL . $text;
	}

	/**
	 * Handles the submission of comments.
	 *
	 * @since 1.0.0
	 */
	public function verify_token() {

		$error = $this->is_valid();

	   if ( is_wp_error( $error ) ) {
			wp_die(
				'<p>' . esc_html( $error->get_error_message() ) . '</p>',
				esc_html__( 'Comment Submission Failure, Click the BACK button on your browser and try again.', 'hizzle-recaptcha' ),
				array(
					'response'  => 400,
					'back_link' => true,
				)
			);
	   }

	}

}
