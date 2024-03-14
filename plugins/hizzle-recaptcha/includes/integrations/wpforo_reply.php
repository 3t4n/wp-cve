<?php
/**
 * Contains the main wp foro reply class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WP Foro reply integration Class.
 *
 */
class Hizzle_reCAPTCHA_WPforo_Reply_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'wpforo_editor_post_submit_before', array( $this, 'display' ), 99 );
		add_filter( 'wpforo_add_post_data_filter', array( $this, 'verify_token' ) );
	}

	/**
	 * Displays the checkbox..
	 *
	 * @since 1.0.0
	 */
	public function display() {
		echo '<div style="padding: 20px;">';
		parent::display();
		echo '</div>';
	}

	/**
	 * Verifies new topic tokens.
	 *
	 * @since 1.0.0
	 */
	public function verify_token( $data ) {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			WPF()->notice->add( esc_html( $error->get_error_message() ), 'error' );
			return false;
	    }

		return $data;
	}

}
