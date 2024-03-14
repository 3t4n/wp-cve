<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.sanil.com.np
 * @since      1.0.0
 *
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Sticky_Social_Icons
 * @subpackage Sticky_Social_Icons/includes
 * @author     Sanil Shakya <sanilshakya@gmail.com>
 */
class Sticky_Social_Icons_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'sticky-social-icons',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
