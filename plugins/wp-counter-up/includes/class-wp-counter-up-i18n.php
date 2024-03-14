<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://logichunt.com
 * @since      1.0.0
 *
 * @package    Wp_Counter_Up
 * @subpackage Wp_Counter_Up/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Counter_Up
 * @subpackage Wp_Counter_Up/includes
 * @author     LogicHunt <logichunt.info@gmail.com>
 */
class Wp_Counter_Up_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-counter-up',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
