<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://designinvento.net/
 * @since      1.0.0
 *
 * @package    Dicode_Icons_Pack
 * @subpackage Dicode_Icons_Pack/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Dicode_Icons_Pack
 * @subpackage Dicode_Icons_Pack/includes
 * @author     Designinvento <team@designinvento.net>
 */
class Dicode_Icons_Pack_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'dicode-icons-pack',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
