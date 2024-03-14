<?php
if ( ! bwfan_is_autonami_pro_active() || version_compare( BWFAN_PRO_VERSION, '2.0.3', '>=' ) ) {
	class BWFAN_Contact_Phone extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'contact_phone';
			$this->tag_description = __( 'Contact Phone', 'autonami-automations-pro' );
			add_shortcode( 'bwfan_contact_phone', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_customer_phone', array( $this, 'parse_shortcode' ) );
			$this->priority = 16;
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

			/** If cart */
			if ( isset( $get_data['cart_details'] ) && ! empty( $get_data['cart_details'] ) ) {
				$data = json_decode( $get_data['cart_details']['checkout_data'], true );
				if ( isset( $data['fields'] ) && isset( $data['fields']['billing_phone'] ) && ! empty( $data['fields']['billing_phone'] ) ) {
					$phone = isset( $data['fields']['billing_phone'] ) ? $data['fields']['billing_phone'] : '';
					if ( empty( $phone ) ) {
						$phone = isset( $data['fields']['shipping_phone'] ) ? $data['fields']['shipping_phone'] : '';
					}
					$country = isset( $data['fields']['billing_country'] ) ? $data['fields']['billing_country'] : '';
					if ( empty( $country ) ) {
						$country = isset( $data['fields']['shipping_country'] ) ? $data['fields']['shipping_country'] : '';
					}
					if ( ! empty( $phone ) && ! empty( $country ) ) {
						$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
					}

					if ( ! empty( $phone ) ) {
						return $this->parse_shortcode_output( $phone, $attr );
					}
				}
			}

			/** If order */
			$order = $this->get_order_object( $get_data );
			if ( ! empty( $order ) ) {
				$phone = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_phone' );
				if ( empty( $phone ) ) {
					$phone = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_phone' );
				}

				$country = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_country' );
				if ( empty( $country ) ) {
					$country = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_country' );
				}

				if ( ! empty( $phone ) && ! empty( $country ) ) {
					$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
				}

				if ( ! empty( $phone ) ) {
					return $this->parse_shortcode_output( $phone, $attr );
				}
			}

			/** If Contact ID */
			$cid   = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
			$phone = $this->get_phone_by_cid( $cid );
			if ( ! empty( $phone ) ) {
				return $this->parse_shortcode_output( $phone, $attr );
			}

			/** If phone number */
			if ( isset( $get_data['phone'] ) && ! empty( $get_data['phone'] ) ) {
				return $this->parse_shortcode_output( $get_data['phone'], $attr );
			}

			/** If user ID or email */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			$email   = isset( $get_data['email'] ) ? $get_data['email'] : '';

			$contact = bwf_get_contact( $user_id, $email );
			if ( absint( $contact->get_id() ) > 0 ) {
				$phone   = $contact->get_contact_no();
				$country = $contact->get_country();
				if ( ! empty( $phone ) && ! empty( $country ) ) {
					$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
				}

				return $this->parse_shortcode_output( $phone, $attr );
			}

			return $this->parse_shortcode_output( '', $attr );
		}

		public function get_phone_by_cid( $cid ) {
			$cid = absint( $cid );
			if ( 0 === $cid ) {
				return '';
			}

			$contact = new WooFunnels_Contact( '', '', '', $cid );
			if ( 0 === intval( $contact->get_id() ) ) {
				return '';
			}

			$phone   = $contact->get_contact_no();
			$country = $contact->get_country();
			if ( ! empty( $phone ) && ! empty( $country ) ) {
				$phone = BWFAN_Phone_Numbers::add_country_code( $phone, $country );
			}

			return $phone;
		}

		/**
		 * Show dummy value of the current merge tag.
		 *
		 * @return string
		 */
		public function get_dummy_preview() {
			$contact     = $this->get_contact_data();
			$contact_num = '8451001000';
			/** check for contact instance and the contact id */
			if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
				return $contact_num;
			}

			/** If empty */
			if ( empty( $contact->get_contact_no() ) ) {
				return $contact_num;
			}

			return $contact->get_contact_no();
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_Phone', null, 'Contact' );
}
