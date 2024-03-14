<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codesupply.co
 * @since      1.0.0
 *
 * @package    ABR
 * @subpackage ABR/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @package    ABR
 * @subpackage ABR/includes
 */
class ABR_i18n {


	/**
	 * Load the plugin text domain for translation.
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain( 'absolute-reviews', false, dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/' );
	}
}
