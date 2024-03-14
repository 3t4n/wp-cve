<?php

if ( ! class_exists( 'BWF_Optin_Tags' ) ) {
	class BWF_Optin_Tags {

		/**
		 * @var BWF_Optin_Tags $optin
		 */
		private $optin = null;
		public $id;
		public $email;
		public $optin_first_name;
		public $optin_last_name;
		public $optin_phone;
		public $optin_custom;
		public $shortcodes=array(
			'id',
			'first_name',
			'last_name',
			'email',
			'phone',
			'custom'

		);

		public function __construct() {
			add_action( 'init', array( $this, 'maybe_set_optin' ) );

			foreach ( $this->shortcodes as $code ) {
				add_shortcode( 'wfop_' . $code, array( $this, 'get_' . $code ) );
			}

		}

		private static $ins = null;

		/**
		 * @return BWF_Optin_Tags|null
		 */
		public static function get_instance() {
			if ( null === self::$ins ) {
				self::$ins = new self;
			}

			return self::$ins;
		}

		public function set_optin( $bwf_optin ) {
			$this->optin = $bwf_optin;
		}

		/**
		 * @return BWF_Optin_Tags
		 */
		public function get_optin() {
			return $this->optin;
		}

		public function get_id( $attr ) {
			if ( isset( $this->get_optin()->id ) && ! empty( $this->get_optin()->id ) ) {
				return $this->get_optin()->id;
			}

			return $this->get_default( $attr, 'id' );

		}

		public function get_first_name( $attr ) {
			if ( isset( $this->get_optin()->optin_first_name ) && ! empty( $this->get_optin()->optin_first_name ) ) {
				return $this->get_optin()->optin_first_name;
			}

			return $this->get_default( $attr, 'first_name' );

		}

		public function get_last_name( $attr ) {
			if ( isset( $this->get_optin()->optin_last_name ) && ! empty( $this->get_optin()->optin_last_name ) ) {
				return $this->get_optin()->optin_last_name;
			}

			return $this->get_default( $attr, 'last_name' );

		}

		public function get_email( $attr ) {
			if ( isset( $this->get_optin()->email ) && ! empty( $this->get_optin()->email ) ) {
				return $this->get_optin()->email;
			}

			return $this->get_default( $attr, 'email' );

		}

		public function get_phone( $attr ) {
			if ( isset( $this->get_optin()->optin_phone ) && ! empty( $this->get_optin()->optin_phone ) ) {
				return $this->get_optin()->optin_phone;
			}

			return $this->get_default( $attr, 'phone' );

		}

		public function get_custom( $attr ) {
			$key = isset( $attr['key'] ) ? $attr['key'] : '';
			if ( $key !== '' && ( isset( $this->get_optin()->$key ) && ! empty( $this->get_optin()->$key ) ) ) {
				return $this->get_optin()->$key;
			}

			return $this->get_default( $attr, 'custom' );
		}

		public function get_default( $attr, $key ) {
			if ( isset( $attr['default'] ) ) {
				return $attr['default'];
			}

			return '';

		}

		public function maybe_set_optin( $opid = '' ) {

			if ( $opid === '' ) {
				$opid = filter_input( INPUT_GET, 'opid', FILTER_UNSAFE_RAW ); //phpcs:ignore WordPressVIPMinimum.Security.PHPFilterFunctions.RestrictedFilter
			}

			if ( empty( $opid ) ) {
				$opid = WFFN_Core()->data->get( 'opid' );
			}

			if ( empty( $opid ) ) {
				return;
			}
			$bwf_optin = WFFN_DB_Optin::get_instance();
			$optin     = $bwf_optin->get_contact_by_opid( $opid );

			if ( empty( $optin ) || ( $optin->id === 0 && $optin->email !== '' ) ) {
				return;
			}

			$data = json_decode( $optin->data );
			if ( ! is_object( $data ) ) {
				$data = new stdClass();
			}
			$data->id    = $optin->id;
			$data->email = $optin->email;

			$this->set_optin( $data );

		}
	}

	BWF_Optin_Tags::get_instance();
}