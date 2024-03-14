<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       thetechtribe.com
 * @since      1.0.0
 *
 * @package    The_Tribal_Plugin
 * @subpackage The_Tribal_Plugin/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    The_Tribal_Plugin
 * @subpackage The_Tribal_Plugin/includes
 * @author     Nigel Moore <help@thetechtribe.com>
 */
class The_Tribal_Plugin_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'the-tribal-plugin',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
