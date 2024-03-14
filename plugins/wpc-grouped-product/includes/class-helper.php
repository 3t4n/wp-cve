<?php
defined( 'ABSPATH' ) || exit;

if ( ! class_exists( 'WPCleverWoosg_Helper' ) ) {
	class WPCleverWoosg_Helper {
		protected static $instance = null;
		protected static $settings = [];
		protected static $localization = [];

		public static function instance() {
			if ( is_null( self::$instance ) ) {
				self::$instance = new self();
			}

			return self::$instance;
		}

		function __construct() {
			// settings
			self::$settings = (array) get_option( 'woosg_settings', [] );
			// localization
			self::$localization = (array) get_option( 'woosg_localization', [] );
		}

		public static function get_settings() {
			return apply_filters( 'woosg_get_settings', self::$settings );
		}

		public static function get_setting( $name, $default = false ) {
			if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
				$setting = self::$settings[ $name ];
			} else {
				$setting = get_option( 'woosg_' . $name, $default );
			}

			return apply_filters( 'woosg_get_setting', $setting, $name, $default );
		}

		public static function localization( $key = '', $default = '' ) {
			$str = '';

			if ( ! empty( $key ) && ! empty( self::$localization[ $key ] ) ) {
				$str = self::$localization[ $key ];
			} elseif ( ! empty( $default ) ) {
				$str = $default;
			}

			return apply_filters( 'woosg_localization_' . $key, $str );
		}

		public static function clean_ids( $ids ) {
			return apply_filters( 'woosg_clean_ids', $ids );
		}

		public static function sanitize_array( $arr ) {
			foreach ( (array) $arr as $k => $v ) {
				if ( is_array( $v ) ) {
					$arr[ $k ] = self::sanitize_array( $v );
				} else {
					$arr[ $k ] = sanitize_text_field( $v );
				}
			}

			return $arr;
		}

		public static function generate_key() {
			$key         = '';
			$key_str     = apply_filters( 'woosg_key_characters', 'abcdefghijklmnopqrstuvwxyz0123456789' );
			$key_str_len = strlen( $key_str );

			for ( $i = 0; $i < apply_filters( 'woosg_key_length', 4 ); $i ++ ) {
				$key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
			}

			if ( is_numeric( $key ) ) {
				$key = self::generate_key();
			}

			return apply_filters( 'woosg_generate_key', $key );
		}
	}

	function WPCleverWoosg_Helper() {
		return WPCleverWoosg_Helper::instance();
	}
}