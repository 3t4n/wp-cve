<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       inon_kaplan
 * @since      1.0.0
 *
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Platy_Syncer_Etsy
 * @subpackage Platy_Syncer_Etsy/includes
 * @author     Inon Kaplan <inonkp@gmail.com>
 */
class Platy_Syncer_Etsy_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'platy-syncer-etsy',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
