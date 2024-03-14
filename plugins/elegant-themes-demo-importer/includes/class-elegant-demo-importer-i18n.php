<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       elegantblogthemes.com
 * @since      1.0.0
 *
 * @package    elegant_Demo_Importer
 * @subpackage elegant_Demo_Importer/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    elegant_Demo_Importer
 * @subpackage elegant_Demo_Importer/includes
 * @author     Elegant Blog Themes <info@elegantblogthemes.com>
 */
class elegant_Demo_Importer_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'elegant-demo-importer',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
