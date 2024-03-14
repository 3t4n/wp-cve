<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://wpglob.com/
 * @since      1.0.0
 *
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Auto_Scroll_For_Reading
 * @subpackage Auto_Scroll_For_Reading/includes
 * @author     WP Glob <info@wpglob.com>
 */
class Auto_Scroll_For_Reading_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'auto-scroll-for-reading',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
