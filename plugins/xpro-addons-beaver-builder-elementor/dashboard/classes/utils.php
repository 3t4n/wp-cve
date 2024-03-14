<?php


class Xpro_Beaver_Dashboard_Utils {


	public static $instance = null;
	private static $key     = 'xpro_beaver_addons_options';

	public static function strify( $str ) {
		return strtolower( preg_replace( '/[^A-Za-z0-9]/', '-', $str ) );
	}

	public static function instance() {
		if ( is_null( self::$instance ) ) {
			// Fire the class instance
			self::$instance = new self();
		}

		return self::$instance;
	}

	public function get_settings( $key, $default = '' ) {
		$data_all = $this->get_option( 'xpro_beaver_settings', array() );

		return ( isset( $data_all[ $key ] ) && '' !== $data_all[ $key ] ) ? $data_all[ $key ] : $default;
	}

	public function get_option( $key, $default = '' ) {
		$data_all = get_option( self::$key );
		return ( isset( $data_all[ $key ] ) && '' !== $data_all[ $key ] ) ? $data_all[ $key ] : $default;
	}

	public function save_option( $key, $value = '' ) {
		$data_all         = get_option( self::$key );
		$data_all[ $key ] = $value;
		update_option( 'xpro_beaver_addons_options', $data_all );
	}

	public function is_widget_active_class( $widget_name, $pro_active ) {
		if ( $pro_active ) {
			return 'label-' . $widget_name;
		} else {
			return 'label-' . $widget_name . ' pro-disabled';
		}
	}
}


Xpro_Beaver_Dashboard_Utils::instance();
