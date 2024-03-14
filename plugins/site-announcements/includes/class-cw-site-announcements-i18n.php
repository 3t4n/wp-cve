<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://codewrangler.io
 * @since      1.0.0
 *
 * @package    CW_Site_Announcements
 * @subpackage CW_Site_Announcements/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    CW_Site_Announcements
 * @subpackage CW_Site_Announcements/includes
 * @author     Edward Jenkins <erjenkins1@gmail.com>
 */
class CW_Site_Announcements_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cw-site-announcements',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
