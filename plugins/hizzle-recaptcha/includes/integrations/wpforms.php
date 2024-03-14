<?php
/**
 * Contains the main wpforms class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * WPForms integration Class.
 *
 */
class Hizzle_reCAPTCHA_WPForms_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'wpforms_display_submit_before', array( $this, 'display' ), 99 );
		add_action( 'wpforms_process', array( $this, 'verify_token' ), 10, 3 );
	}

	/**
	 * Action that fires during form entry processing after initial field validation.
	 *
	 * @link   https://wpforms.com/developers/wpforms_process/
	 *
	 * @param  array $fields    Sanitized entry field. values/properties.
	 * @param  array $entry     Original $_POST global.
	 * @param  array $form_data Form data and settings.
	 *
	 * @return array|null
	 */
	public function verify_token( $fields, $entry, $form_data ) {

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			wpforms()->process->errors[ $form_data['id'] ]['footer'] = esc_html( $error->get_error_message() );
	    }

	}

}
