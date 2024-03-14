<?php
if ( ! bwfan_is_autonami_pro_active() || version_compare( BWFAN_PRO_VERSION, '2.0.3', '>=' ) ) {
	class BWFAN_Contact_Full_Name extends BWFAN_Merge_Tag {

		private static $instance = null;

		public function __construct() {
			$this->tag_name        = 'contact_full_name';
			$this->tag_description = __( 'Contact Full Name', 'wp-marketing-automations' );
			add_shortcode( 'bwfan_contact_full_name', array( $this, 'parse_shortcode' ) );
			add_shortcode( 'bwfan_customer_full_name', array( $this, 'parse_shortcode' ) );
			$this->priority = 13;
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

			/** If first name and last name */
			if ( isset( $get_data['first_name'] ) && isset( $get_data['last_name'] ) ) {
				$full_name = $this->get_full_name( $get_data['first_name'], $get_data['last_name'] );

				return $this->parse_shortcode_output( $full_name, $attr );
			}

			/** If order */
			$order = $this->get_order_object( $get_data );
			if ( ! empty( $order ) ) {
				$first_name = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_first_name' );
				if ( empty( $first_name ) ) {
					$first_name = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_first_name' );
				}
				$last_name = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_billing_last_name' );
				if ( empty( $last_name ) ) {
					$last_name = BWFAN_Woocommerce_Compatibility::get_order_data( $order, '_shipping_last_name' );
				}
				$full_name = $this->get_full_name( $first_name, $last_name );
				if ( ! empty( $full_name ) ) {
					return $this->parse_shortcode_output( $full_name, $attr );
				}
			}

			/** If Contact ID */
			$cid = isset( $get_data['contact_id'] ) ? $get_data['contact_id'] : '';
			if ( absint( $cid ) > 0 ) {
				$contact = new WooFunnels_Contact( '', '', '', $cid );
				if ( $contact->get_id() > 0 ) {
					$full_name = $this->get_full_name( $contact->get_f_name(), $contact->get_l_name() );
					if ( ! empty( $full_name ) ) {
						return $this->parse_shortcode_output( $full_name, $attr );
					}
				}
			}

			/** If user id */
			$user_id = isset( $get_data['user_id'] ) ? $get_data['user_id'] : '';
			if ( absint( $user_id ) > 0 ) {
				$first_name = get_user_meta( $user_id, 'first_name', true );
				$last_name  = get_user_meta( $user_id, 'last_name', true );
				$full_name  = $this->get_full_name( $first_name, $last_name );
				if ( ! empty( $full_name ) ) {
					return $this->parse_shortcode_output( $full_name, $attr );
				}
			}

			/** If email */
			$email = isset( $get_data['email'] ) ? trim( $get_data['email'] ) : '';
			if ( is_email( $email ) ) {
				$user_data  = get_user_by( 'email', $email );
				$first_name = $user_data instanceof WP_User ? get_user_meta( $user_data->ID, 'first_name', true ) : '';
				$last_name  = $user_data instanceof WP_User ? get_user_meta( $user_data->ID, 'last_name', true ) : '';
				$full_name  = $this->get_full_name( $first_name, $last_name );
				if ( ! empty( $full_name ) ) {
					return $this->parse_shortcode_output( ucwords( $full_name ), $attr );
				}
			}

			/** If cart */
			if ( isset( $get_data['cart_details'] ) && ! empty( $get_data['cart_details'] ) ) {
				$data       = json_decode( $get_data['cart_details']['checkout_data'], true );
				$first_name = '';
				$last_name  = '';
				if ( isset( $data['fields'] ) ) {
					if ( isset( $data['fields']['billing_first_name'] ) ) {
						$first_name = $data['fields']['billing_first_name'];
					}
					if ( isset( $data['fields']['billing_last_name'] ) ) {
						$last_name = $data['fields']['billing_last_name'];
					}
				}
				$full_name = $this->get_full_name( $first_name, $last_name );

				return $this->parse_shortcode_output( $full_name, $attr );
			}

			return $this->parse_shortcode_output( '', $attr );
		}

		public function get_full_name( $first = '', $last = '' ) {
			if ( empty( $first ) && empty( $last ) ) {
				return '';
			}
			$name = ! empty( $first ) ? trim( $first ) : '';
			$name = ! empty( $last ) ? ( ! empty( $name ) ? $name . ' ' . trim( $last ) : trim( $last ) ) : $name;

			if ( empty( $name ) ) {
				return '';
			}

			return ucwords( $name );
		}

		/**
		 * Show dummy value of the current merge tag.
		 *
		 * @return string
		 */
		public function get_dummy_preview() {
			$contact   = $this->get_contact_data();
			$full_name = 'John Doe';

			/** check for contact instance and the contact id */
			if ( ! $contact instanceof WooFunnels_Contact || 0 === absint( $contact->get_id() ) ) {
				return $full_name;
			}

			return $this->get_full_name( $contact->get_f_name(), $contact->get_l_name() );
		}
	}

	/**
	 * Register this merge tag to a group.
	 */
	BWFAN_Merge_Tag_Loader::register( 'bwf_contact', 'BWFAN_Contact_Full_Name', null, 'Contact' );
}