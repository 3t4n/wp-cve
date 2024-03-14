<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wbsync.com
 * @since      1.0.0
 *
 * @package    Data_Sync_X_Woocommerce
 * @subpackage Data_Sync_X_Woocommerce/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Data_Sync_X_Woocommerce
 * @subpackage Data_Sync_X_Woocommerce/includes
 * @author     Michael Pierotti <hello@wbsync.com>
 */
class Data_Sync_X_Woocommerce_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'data-sync-x-woocommerce',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
