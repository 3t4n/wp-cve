<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://webdeclic.com
 * @since      1.0.0
 *
 * @package    Universal_Honey_Pot
 * @subpackage Universal_Honey_Pot/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Universal_Honey_Pot
 * @subpackage Universal_Honey_Pot/includes
 * @author     Webdeclic <contact@webdeclic.com>
 */
class Universal_Honey_Pot_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'universal-honey-pot',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
