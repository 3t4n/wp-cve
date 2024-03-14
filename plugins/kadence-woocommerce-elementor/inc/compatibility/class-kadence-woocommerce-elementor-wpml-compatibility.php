<?php
/**
 * WPML Compatibility.
 *
 * @package Kadence Woocommerce Elementor
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Set up WPML Compatibiblity Class.
 *
 * @category class.
 */
class Kadence_Woocommerce_Elementor_WPML_Compatibility {

	/**
	 * Instance Control
	 *
	 * @var null
	 */
	private static $instance = null;

	/**
	 * Instance Control
	 */
	public static function get_instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}


	/**
	 * Setup filter.
	 */
	private function __construct() {
		add_filter( 'kadence_product_elementor_template', array( $this, 'get_wpml_object' ), 100 );
	}

	/**
	 * Pass the final template ID from the WPML's object filter to allow strings to be translated.
	 *
	 * @param  Int $id  Post ID of the template being rendered.
	 * @return Int $id  Post ID of the template being rendered, Passed through the `wpml_object_id` id.
	 */
	public function get_wpml_object( $id ) {
		return apply_filters( 'wpml_object_id', $id );
	}

}

/**
 * Initiate the class.
 */
Kadence_Woocommerce_Elementor_WPML_Compatibility::get_instance();
