<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
#[AllowDynamicProperties]
class BWFAN_Load_Custom_Search {

	/**
	 * Saves all the custom_search object
	 * @var array
	 */
	private static $custom_search = array();
	private static $ins = null;

	/**
	 * Return the object of current class
	 *
	 * @return null|BWFAN_Load_Custom_Search
	 */
	public static function get_instance() {
		if ( null === self::$ins ) {
			self::$ins = new self();
		}

		return self::$ins;
	}
	
	/**
	 * Register the integration when the integration file is included
	 *
	 * @param $class
	 */
	public static function register( $class ) {
		if ( class_exists( $class ) && method_exists( $class, 'get_instance' ) ) {
			$temp_integration = $class::get_instance();

			$slug                         = $temp_integration->get_slug();
			self::$custom_search[ $slug ] = $temp_integration;
			
		}
	}

	/**
	 * Return all available action registered which register by their integration
	 * @return array
	 */
	public function get_custom_search( $slug = '' ) {
		return isset( self::$custom_search[ $slug ] ) ? self::$custom_search[ $slug ] : null;
	}

}

if ( class_exists( 'BWFAN_Load_Custom_Search' ) ) {
	BWFAN_Core::register( 'custom_search', 'BWFAN_Load_Custom_Search' );
}