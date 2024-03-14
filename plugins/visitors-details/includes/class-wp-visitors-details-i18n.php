<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://topinfosoft.com
 * @since      1.0.0
 *
 * @package    Wp_Visitors_Details
 * @subpackage Wp_Visitors_Details/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Visitors_Details
 * @subpackage Wp_Visitors_Details/includes
 * @author     Top Infosoft <topinfosoft@gmail.com>
 */
class Wp_Visitors_Details_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-visitors-details',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
