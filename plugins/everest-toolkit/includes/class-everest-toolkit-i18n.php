<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://everestthemes.com
 * @since      1.0.0
 *
 * @package    Everest_Toolkit
 * @subpackage Everest_Toolkit/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Everest_Toolkit
 * @subpackage Everest_Toolkit/includes
 * @author     everestthemes <themeseverest@gmail.com>
 */
class Everest_Toolkit_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'everest-toolkit',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
