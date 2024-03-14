<?php

namespace Vimeotheque\Options;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Options_Factory
 * Use it to create unique instances of Options class to manipulate WP options
 * @ignore
 */
class Options_Factory{
	/**
	 * Stores references to all instantiated Options classes
	 * Used internally
	 * @var array Options
	 */
	private static $storage = [];
	/**
	 * Store class reference
	 * @var Options_Factory
	 */
	private static $instance;

	/**
	 * Get the object for current option
	 *
	 * @param $option_name
	 * @param $defaults
	 *
	 * @return Options
	 */
	public static function get( $option_name, $defaults ){
		if( null === self::$instance ){
			self::$instance = new Options_Factory;
		}
		return self::_get( $option_name, $defaults );
	}

	/**
	 * Private constructor to prevent instantiation
	 * Options_Factory constructor.
	 */
	private function __construct(){}

	/**
	 * Gets the Options object for the key passed.
	 * If already existing, returns existing instance, otherwise will create it
	 * @param $option_name
	 * @param $defaults
	 *
	 * @return mixed
	 */
	private static function _get( $option_name, $defaults ){
		if( !array_key_exists( $option_name, self::$storage ) ){
			self::_add( $option_name, $defaults );
		}
		return self::$storage[ $option_name ];
	}

	/**
	 * Add new Options instance to storage
	 * @param $option_name
	 * @param $defaults
	 */
	private static function _add( $option_name, $defaults ){
		self::$storage[ $option_name ] = new Options( $option_name, $defaults );
	}
}