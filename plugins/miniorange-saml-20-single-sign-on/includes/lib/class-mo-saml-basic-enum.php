<?php
/**
 * Basic Enum File. This file contains the Enum Super Class.
 *
 * @package miniorange-saml-20-single-sign-on\includes\lib
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Basic Enum Class is the Enum Super Class which is used to create the Enum constant classes.
 */
abstract class Mo_SAML_Basic_Enum {
	/**
	 * Const cache array
	 *
	 * @var mixed
	 */
	private static $const_cache_array = null;

	/**
	 * Function to fetch constant classes.
	 *
	 * @return mixed|array
	 */
	public static function get_constants() {
		if ( null === self::$const_cache_array ) {
			self::$const_cache_array = array();
		}
		$called_class = get_called_class();
		if ( empty( self::$const_cache_array[ $called_class ] ) ) {
			$reflect                                  = new ReflectionClass( $called_class );
			self::$const_cache_array[ $called_class ] = $reflect->getConstants();
		}
		return self::$const_cache_array[ $called_class ];
	}

	/**
	 * Function to check for valid name of fetched constant classes.
	 *
	 * @param  mixed   $name accepts the constants name.
	 * @param  boolean $strict bool check.
	 * @return boolean
	 */
	public static function is_valid_name( $name, $strict = false ) {
		$constants = self::get_constants();

		if ( $strict ) {
			return ! empty( $constants[ $name ] );
		}

		$keys = array_map( 'strtolower', array_keys( $constants ) );
		return in_array( strtolower( $name ), $keys, true );
	}

	/**
	 * Function to check for valid value of constant classes.
	 *
	 * @param  mixed   $value accepts the constant value.
	 * @param  boolean $strict bool check.
	 * @return boolean
	 */
	public static function is_valid_value( $value, $strict = true ) {
		$values = array_values( self::get_constants() );
		return in_array( $value, $values, true );
	}
}
