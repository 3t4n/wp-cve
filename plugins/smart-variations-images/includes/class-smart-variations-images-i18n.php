<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.rosendo.pt
 * @since      1.0.0
 *
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Smart_Variations_Images
 * @subpackage Smart_Variations_Images/includes
 * @author     David Rosendo <david@rosendo.pt>
 */
class Smart_Variations_Images_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'smart-variations-images',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
