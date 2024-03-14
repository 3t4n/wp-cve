<?php

class BWFAN_Contact_Password_Setup_Link extends BWFAN_Merge_Tag {

	private static $instance = null;

	public function __construct() {
		$this->tag_name        = 'contact_password_setup_link';
		$this->tag_description = __( 'Contact User Password Setup Link', 'autonami-automations-pro' );

		add_shortcode( 'bwfan_contact_password_setup_link', array( $this, 'parse_shortcode' ) );
		$this->priority = 14;
	}

	public static function get_instance() {
		if ( null === self::$instance ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	/**
	 * Parse the merge tag and return its value.
	 *
	 * @param $attr
	 *
	 * @return mixed|string|void
	 */
	public function parse_shortcode( $attr ) {
		if ( true === BWFAN_Merge_Tag_Loader::get_data( 'is_preview' ) ) {
			return $this->get_dummy_preview();
		}

		$get_data = BWFAN_Merge_Tag_Loader::get_data();

		$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
		if ( ! empty( $user_id ) ) {
			$link = $this->get_reset_link( $user_id );
			if ( ! empty( $link ) ) {
				return $this->parse_shortcode_output( $link, $attr );
			}
		}

		$cid = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
		if ( ! empty( $cid ) && intval( $cid ) > 0 ) {
			$contact = new WooFunnels_Contact( '', '', '', $cid );
			if ( intval( $contact->get_id() ) > 0 && intval( $contact->get_wpid() ) > 0 ) {
				$link = $this->get_reset_link( $contact->get_wpid() );
				if ( ! empty( $link ) ) {
					return $this->parse_shortcode_output( $link, $attr );
				}
			}
		}

		$email = isset( $get_data['email'] ) ? $get_data['email'] : '';
		if ( ! empty( $email ) ) {
			$user = get_user_by( 'email', $email );
			if ( $user instanceof WP_User ) {
				$link = $this->get_reset_link( $user->ID, $user );

				return $this->parse_shortcode_output( $link, $attr );
			}
		}

		return $this->parse_shortcode_output( '', $attr );
	}

	/**
	 * Return reset link of a user
	 *
	 * @param $user_id
	 * @param $user WP_User
	 *
	 * @return string
	 */
	protected function get_reset_link( $user_id, $user = false ) {
		if ( empty( $user ) ) {
			$user = new WP_User( $user_id );
			if ( ! $user instanceof WP_User ) {
				return '';
			}
		}

		$key = get_password_reset_key( $user );

		return add_query_arg( array(
			'action' => 'rp',
			'key'    => rawurlencode( $key ),
			'login'  => rawurlencode( $user->user_login ),
		), wp_login_url() );
	}

	/**
	 * Show dummy value of the current merge tag
	 *
	 * @return string
	 */
	public function get_dummy_preview() {
		return '';
	}
}

/**
 * Register this merge tag to a group
 */
BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_password_setup_link', null, 'Contact' );
