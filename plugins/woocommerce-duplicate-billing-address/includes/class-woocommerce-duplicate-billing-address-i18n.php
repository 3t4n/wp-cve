<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://eversionsystems.com/
 * @since      1.0.0
 *
 * @package    Woocommerce_Duplicate_Billing_Address
 * @subpackage Woocommerce_Duplicate_Billing_Address/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Woocommerce_Duplicate_Billing_Address
 * @subpackage Woocommerce_Duplicate_Billing_Address/includes
 * @author     Andrew Schultz <contact@eversionsystems.com>
 */
class Woocommerce_Duplicate_Billing_Address_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'woocommerce-duplicate-billing-address',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
