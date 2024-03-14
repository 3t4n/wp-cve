<?php
defined( 'ABSPATH' ) || exit;

if ( class_exists( 'WPCleverWoonp' ) ) {
	return;
}

class WoonpHelper {
	protected static $settings = [];
	protected static $instance = null;

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function __construct() {
		self::$settings = (array) get_option( 'woonp_settings', [] );
	}

	public static function get_settings() {
		return apply_filters( 'woonp_get_settings', self::$settings );
	}

	public static function get_setting( $name, $default = false ) {
		if ( ! empty( self::$settings ) && isset( self::$settings[ $name ] ) ) {
			$setting = self::$settings[ $name ];
		} else {
			$setting = get_option( '_woonp_' . $name, $default );
		}

		return apply_filters( 'woonp_get_setting', $setting, $name, $default );
	}
}

return WoonpHelper::instance();
