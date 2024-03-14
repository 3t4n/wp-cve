<?php

class BWFAN_Subscribe_Link_Handler {

	private static $ins = null;

	public function __construct() {
		add_action( 'wp', [ __CLASS__, 'handle_subscribe_link' ], 999 );
	}

	/**
	 * Return the object of current class
	 *
	 * @return null|BWFAN_Subscribe_Link_Handler
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}

	/**
	 * handling subscribe link
	 */
	public static function handle_subscribe_link() {
		/** Check for pro version */
		if ( ! bwfan_is_autonami_pro_active() ) {
			return;
		}

		$uid = filter_input( INPUT_GET, 'bwfan-uid' );
		if ( empty( $uid ) ) {
			return;
		}

		$action = filter_input( INPUT_GET, 'bwfan-action' );
		if ( empty( $action ) || 'subscribe' !== sanitize_text_field( $action ) ) {
			return;
		}

		$bwf_contacts = BWF_Contacts::get_instance();
		$dbcontact    = $bwf_contacts->get_contact_by( 'uid', $uid );

		$link = filter_input( INPUT_GET, 'bwfan-link' );
		if ( empty( $dbcontact->db_contact ) ) {
			if ( ! empty( $link ) ) {
				// redirecting to bwfan-link if there
				$url = BWFAN_Common::bwfan_correct_protocol_url( $link );
				$url = BWFAN_Common::append_extra_url_arguments( $url );
				if ( false !== wp_http_validate_url( $url ) ) {
					wp_redirect( $url );
					exit;
				}
			}

			// when no contact found with the uid then redirect to the home url
			wp_redirect( home_url() );
			exit();
		}

		$contact = new BWFCRM_Contact( $dbcontact->db_contact->id );

		/** to mark the contact subscribe and remove the unsubscribe record */
		$contact->resubscribe( false );
		$contact->save();

		/** Hook after subscribe link clicked */
		do_action( 'bwfcrm_confirmation_link_clicked', $contact );

		if ( ! empty( $link ) ) {
			$url = BWFAN_Common::bwfan_correct_protocol_url( $link );
			$url = BWFAN_Common::append_extra_url_arguments( $url );
			if ( false !== wp_http_validate_url( $url ) ) {
				wp_redirect( $url );
				exit;
			}
		}

		self::display_confirmation_message();
	}

	public static function display_confirmation_message() {
		$show_message = filter_input( INPUT_GET, 'show_message' );
		if ( is_null( $show_message ) || 1 !== intval( $show_message ) ) {
			return;
		}

		$settings = BWFAN_Common::get_global_settings();

		echo isset( $settings['bwfan_confirmation_message'] ) && ! empty( $settings['bwfan_confirmation_message'] ) ? $settings['bwfan_confirmation_message'] : '';
		exit;
	}

}

/**
 * Register action handler to BWFCRM_Core
 */
if ( class_exists( 'BWFAN_Subscribe_Link_Handler' ) ) {
	BWFAN_Core::register( 'subscribe_link_handler', 'BWFAN_Subscribe_Link_Handler' );
}
