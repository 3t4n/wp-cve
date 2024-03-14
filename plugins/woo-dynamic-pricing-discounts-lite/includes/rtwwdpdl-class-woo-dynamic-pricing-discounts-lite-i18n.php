<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.redefiningtheweb.com
 * @since      1.0.0
 *
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite
 * @subpackage Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite/includes
 * @author     RedefiningTheWeb <developer@redefiningtheweb.com>
 */
class Rtwwdpdl_Woo_Dynamic_Pricing_Discounts_Lite_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function rtwwdpdl_load_plugin_textdomain() {

		load_plugin_textdomain(
			'rtwwdpdl-woo-dynamic-pricing-discounts-lite',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
