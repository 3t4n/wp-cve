<?php
namespace CatFolders\Models;

defined( 'ABSPATH' ) || exit;

class OptionModel {

	private static $defaults = array(
		'userrestriction' => '', // can't use userRestriction due to sanitize_key func
		'allowsvgupload'  => '',
	);

	public static function update_option( $values = array() ) {
		$options = get_option( 'catf_settings', array() );
		if ( ! is_array( $options ) ) {
			$options = array();
		}
		foreach ( $values as $key => $val ) {
			if ( in_array( $key, array_keys( self::$defaults ), true ) ) {
				$options[ $key ] = $val;
			}
		}
		update_option( 'catf_settings', $options );
	}

	public static function get_option( $name = '', $default = '' ) {
		$options = get_option( 'catf_settings', array() );

		if ( ! is_array( $options ) ) {
			$options = array();
		}

		if ( '' === $name ) {
			return wp_parse_args( $options, self::$defaults );
		}

		return isset( $options[ $name ] ) ? $options[ $name ] : $default;
	}
}
