<?php
/**
 * Plugin check class.
 *
 * @package Kadence WooCommerce Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Checks if WooCommerce and Elementor is enabled
 *
 * @category class.
 */
class Kadence_Woocommerce_Elementor_Plugin_Check {

	/**
	 * Static var of active plugins.
	 *
	 * @var active plugins.
	 */
	private static $active_plugins;

	/**
	 * Init function.
	 */
	public static function init() {

		self::$active_plugins = (array) get_option( 'active_plugins', array() );

		if ( is_multisite() ) {
			self::$active_plugins = array_merge( self::$active_plugins, get_site_option( 'active_sitewide_plugins', array() ) );
		}
	}

	/**
	 * Check for woocommerce.
	 */
	public static function active_check_woo() {

		if ( ! self::$active_plugins ) {
			self::init();
		}
		return in_array( 'woocommerce/woocommerce.php', self::$active_plugins ) || array_key_exists( 'woocommerce/woocommerce.php', self::$active_plugins );
	}

	/**
	 * Check for elementor.
	 */
	public static function active_check_ele() {

		if ( ! self::$active_plugins ) {
			self::init();
		}
		return in_array( 'elementor/elementor.php', self::$active_plugins ) || array_key_exists( 'elementor/elementor.php', self::$active_plugins );
	}

}
