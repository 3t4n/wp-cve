<?php

if ( ! class_exists( 'BWF_Contact_Tags' ) ) {
	class BWF_Contact_Tags {

		/**
		 * @var WooFunnels_Contact $contact
		 */
		private $contact = null;
		public $shortcodes = array(
			'id',
			'first_name',
			'last_name',
			'email',
			'custom'
		);

		public function __construct() {

			add_action( 'init', array( $this, 'maybe_set_contact' ) );

			foreach ( $this->shortcodes as $code ) {
				add_shortcode( 'bwf_contact_' . $code, array( $this, 'get_' . $code ) );
			}
		}

		private static $ins = null;

		/**
		 * @return BWF_Contact_Tags|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function set_contact( $bwf_contact ) {
			$this->contact = $bwf_contact;
		}

		/**
		 * @return WooFunnels_Contact|null
		 */
		public function get_contact() {
			return $this->contact;
		}

		public function get_id() {

		}

		public function get_first_name( $attr ) {
			if ( $this->is_valid_contact() ) {

				return $this->get_contact()->get_f_name();
			}

			return $this->get_default( $attr, 'first_name' );
		}

		public function get_last_name( $attr ) {
			if ( $this->is_valid_contact() ) {
				return $this->get_contact()->get_l_name();
			}

			return $this->get_default( $attr, 'last_name' );
		}

		public function get_email( $attr ) {
			if ( $this->is_valid_contact() ) {
				return $this->get_contact()->get_email();
			}

			return $this->get_default( $attr, 'email' );
		}

		public function get_custom( $attr ) {
			$key = isset( $attr['key'] ) ? $attr['key'] : '';
			if ( $key !== '' && $this->is_valid_contact() ) {
				return $this->get_contact()->get_contact_meta( $key );
			}

			return $this->get_default( $attr, 'custom' );
		}

		public function get_default( $attr, $key ) {
			if ( isset( $attr['default'] ) ) {
				return $attr['default'];
			}

			return '';

		}

		public function is_valid_contact( $bwf_contact = '' ) {
			if ( empty( $bwf_contact ) ) {
				$bwf_contact = $this->contact;
			}
			if ( $bwf_contact instanceof WooFunnels_Contact && $bwf_contact->get_id() !== 0 ) {
				return true;
			}

			return false;
		}

		public function maybe_set_contact() {


			$content = filter_input( INPUT_GET, 'opid', FILTER_UNSAFE_RAW );

			if ( empty( $content ) ) {
				return;
			}
			$bwf_contacts = BWF_Contacts::get_instance();
			$bwf_contact  = $bwf_contacts->get_contact_by( 'opid', $content );
			if ( $this->is_valid_contact( $bwf_contact ) ) {
				$this->set_contact( $bwf_contact );
			}
		}
	}

	BWF_Contact_Tags::get_instance();
}