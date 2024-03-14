<?php
if ( ! bwfan_is_autonami_pro_active() || version_compare( BWFAN_PRO_VERSION, '2.0.3', '>=' ) ) {
	class BWFAN_Contact_LastName extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'contact_last_name';
			$this->tag_description = __( 'Contact Last Name', 'autonami-automations-pro' );
			add_shortcode( 'bwfan_contact_lastname', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_contact_last_name', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_customer_last_name', array( $this, 'parse_shortcode' ) );
			$this->priority = 12;
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

			/** If last name */
			if ( isset( $get_data['last_name'] ) && ! empty( $get_data['last_name'] ) ) {
				return $this->parse_shortcode_output( ucfirst( $get_data['last_name'] ), $attr );
			}

			/** If order */
			$order = $this->get_order_object( $get_data );
			if ( ! empty( $order ) ) {
				$last_name = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_last_name' );
				if ( empty( $last_name ) ) {
					$last_name = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_last_name' );
				}
				if ( ! empty( $last_name ) ) {
					return $this->parse_shortcode_output( ucwords( $last_name ), $attr );
				}
			}

			/** If Contact ID */
			$cid       = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
			$last_name = $this->get_last_name( $cid );
			if ( ! empty( $last_name ) ) {
				return $this->parse_shortcode_output( ucwords( $last_name ), $attr );
			}

			/** If $contact exist with user id or email */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			$email   = isset( $get_data['email'] ) ? trim( $get_data['email'] ) : '';

			$contact = bwf_get_contact( $user_id, $email );
			if ( $contact instanceof WooFunnels_Contact && $contact->get_id() > 0 ) {
				$last_name = $contact->get_l_name();
				if ( ! empty( $last_name ) ) {
					return $this->parse_shortcode_output( ucwords( $last_name ), $attr );
				}
			}

			/** If User ID */
			if ( absint( $user_id ) > 0 ) {
				$last_name = get_user_meta( $user_id, 'last_name', true );
				if ( ! empty( $last_name ) ) {
					return $this->parse_shortcode_output( ucwords( $last_name ), $attr );
				}
			}

			/** If email */
			if ( is_email( $email ) ) {
				$user_data = get_user_by( 'email', $email );
				$last_name = $user_data instanceof WP_User ? get_user_meta( $user_data->ID, 'last_name', true ) : '';
				if ( ! empty( $last_name ) ) {
					return $this->parse_shortcode_output( ucwords( $last_name ), $attr );
				}
			}

			/** If cart */
			if ( isset( $get_data['cart_details'] ) && ! empty( $get_data['cart_details'] ) ) {
				$data = json_decode( $get_data['cart_details']['checkout_data'], true );
				if ( isset( $data['fields'] ) && isset( $data['fields']['billing_last_name'] ) ) {
					$last_name = $data['fields']['billing_last_name'];

					return $this->parse_shortcode_output( ucwords( $last_name ), $attr );
				}
			}

			return $this->parse_shortcode_output( '', $attr );
		}

		public function get_last_name( $cid ) {
			$cid = absint( $cid );
			if ( 0 === $cid ) {
				return '';
			}
			$contact = new WooFunnels_Contact( '', '', '', $cid );
			if ( $contact->get_id() > 0 ) {
				return $contact->get_l_name();
			}

			return '';
		}

		/**
		 * Show dummy value of the current merge tag.
		 *
		 * @return string
		 *
		 */
		public function get_dummy_preview() {
			$contact   = $this->get_contact_data();
			$last_name = 'Doe';

			/** check for contact instance */
			if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
				return $last_name;
			}

			/** If empty */
			if ( empty( $contact->get_l_name() ) ) {
				return $last_name;
			}

			return $contact->get_l_name();
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_LastName', null, 'Contact' );
}