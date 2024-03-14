<?php
if ( ! bwfan_is_autonami_pro_active() || version_compare( BWFAN_PRO_VERSION, '2.0.3', '>=' ) ) {
	class BWFAN_Contact_WPID extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'contact_user_id';
			$this->tag_description = __( 'Contact User ID', 'autonami-automations-pro' );
			add_shortcode( 'bwfan_contact_user_id', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_customer_user_id', array( $this, 'parse_shortcode' ) );
			$this->priority = 22;
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
			$get_data = BWFAN_Merge_Tag_Loader::get_data();
			if ( true === $get_data['is_preview'] ) {
				return $this->parse_shortcode_output( $this->get_dummy_preview(), $attr );
			}

			/** If user */
			$user = isset( $get_data['wp_user'] ) ? $get_data['wp_user'] : '';
			if ( $user instanceof WP_User ) {
				return $this->parse_shortcode_output( $user->ID, $attr );
			}

			/** If user id */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			if ( absint( $user_id ) > 0 ) {
				return $this->parse_shortcode_output( absint( $user_id ), $attr );
			}

			/** If order */
			$order = $this->get_order_object( $get_data );
			if ( ! empty( $order ) ) {
				$user_id = $order->get_user_id();
				if ( absint( $user_id ) > 0 ) {
					return $this->parse_shortcode_output( absint( $user_id ), $attr );
				}
			}

			/** If user ID or email */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			$email   = isset( $get_data['email'] ) ? $get_data['email'] : '';

			$contact = bwf_get_contact( $user_id, $email );
			if ( absint( $contact->get_id() ) > 0 ) {
				$user_id = $contact->get_wpid();
				if ( absint( $user_id ) > 0 ) {
					return $this->parse_shortcode_output( absint( $user_id ), $attr );
				}
			}

			return $this->parse_shortcode_output( '', $attr );
		}

		/**
		 * Show dummy value of the current merge tag.
		 *
		 * @return string
		 *
		 */
		public function get_dummy_preview() {
			$contact       = $this->get_contact_data();
			$contact_wp_id = 1;

			/** check for contact instance and the contact id */
			if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
				return $contact_wp_id;
			}

			/** If empty */
			if ( empty( $contact->get_wpid() ) ) {
				return $contact_wp_id;
			}

			return $contact->get_wpid();
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_WPID', null, 'Contact' );
}