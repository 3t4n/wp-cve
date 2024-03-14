<?php
if ( ! bwfan_is_autonami_pro_active() || version_compare( BWFAN_PRO_VERSION, '2.0.3', '>=' ) ) {
	class BWFAN_Contact_State extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'contact_state';
			$this->tag_description = __( 'Contact State', 'autonami-automations-pro' );
			add_shortcode( 'bwfan_contact_state', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_customer_state', array( $this, 'parse_shortcode' ) );
			$this->priority = 20;
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

			/** If Contact ID available */
			$cid   = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
			$state = $this->get_state( $cid );
			if ( ! empty( $state ) ) {
				return $this->parse_shortcode_output( $state, $attr );
			}

			/** If order */
			$order = $this->get_order_object( $get_data );
			if ( ! empty( $order ) ) {
				$state = BWFAN_Woocommerce_Compatibility::get_order_billing_state( $order );
				if ( empty( $state ) ) {
					$state = BWFAN_Woocommerce_Compatibility::get_order_shipping_state( $order );
				}
				$country = BWFAN_Woocommerce_Compatibility::get_billing_country_from_order( $order );
				if ( empty( $country ) ) {
					$country = BWFAN_Woocommerce_Compatibility::get_shipping_country_from_order( $order );
				}
				if ( ! empty( $country ) ) {
					$states = WC()->countries->get_states( $country );
					$state  = ( is_array( $states ) && isset( $states[ $state ] ) ) ? $states[ $state ] : $state;
				}
				if ( ! empty( $state ) ) {
					return $this->parse_shortcode_output( $state, $attr );
				}
			}

			/** If user ID or email */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			$email   = isset( $get_data['email'] ) ? $get_data['email'] : '';

			$contact = bwf_get_contact( $user_id, $email );
			if ( absint( $contact->get_id() ) > 0 ) {
				$state = $contact->get_state();

				return $this->parse_shortcode_output( $state, $attr );
			}

			return $this->parse_shortcode_output( '', $attr );
		}

		public function get_state( $cid ) {
			$cid = absint( $cid );
			if ( 0 === $cid ) {
				return '';
			}
			$contact = new WooFunnels_Contact( '', '', '', $cid );
			if ( $contact->get_id() > 0 ) {
				return $contact->get_state();
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
			$contact       = $this->get_contact_data();
			$contact_state = 'NE';

			/** check for contact instance and the contact id */
			if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
				return $contact_state;
			}

			/** If empty */
			if ( empty( $contact->get_state() ) ) {
				return $contact_state;
			}

			return $contact->get_state();
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_State', null, 'Contact' );
}