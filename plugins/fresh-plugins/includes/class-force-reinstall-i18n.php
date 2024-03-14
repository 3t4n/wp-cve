<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Force_Reinstall
 * @subpackage Force_Reinstall/includes
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Force_Reinstall_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'force-reinstall',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
