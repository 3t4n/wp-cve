<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.catchplugins.com
 * @since      1.0.0
 *
 * @package    Catch_Scroll_Progress_Bar
 * @subpackage Catch_Scroll_Progress_Bar/includes
 */


class Catch_Scroll_Progress_Bar_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'catch-scroll-progress-bar',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
