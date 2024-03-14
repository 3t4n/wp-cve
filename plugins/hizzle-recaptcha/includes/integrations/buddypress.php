<?php
/**
 * Contains the main buddypress class.
 *
 * @package Hizzle
 * @subpackage ReCaptcha
 * @since   1.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * buddypress integration Class.
 *
 */
class Hizzle_reCAPTCHA_BuddyPress_Integration extends Hizzle_reCAPTCHA_Integration {

	/**
	 * Class constructor
	 *
	 * @since 1.0.0
	 */
	public function __construct() {
        add_action( 'bp_after_group_details_creation_step', array( $this, 'display' ) );
		add_action( 'groups_group_before_save', array( $this, 'verify_token' ) );
	}

	/**
	 * Handles the submission of groups.
	 *
	 * @since 1.0.0
	 */
	public function verify_token() {

		if ( ! bp_is_group_creation_step( 'group-details' ) ) {
			return;
		}

		$error = $this->is_valid();

	    if ( is_wp_error( $error ) ) {
			bp_core_add_message( esc_html( $error->get_error_message() ), 'error' );
			bp_core_redirect( bp_get_root_domain() . '/' . bp_get_groups_root_slug() . '/create/step/group-details/' );
	    }

	}

}
