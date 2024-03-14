<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       nakunakifi.com
 * @since      4.0.0
 *
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      4.0.0
 * @package    Google_Photos_Albums_Gallery
 * @subpackage Google_Photos_Albums_Gallery/includes
 * @author     Ian Kennerley <iankennerley@gmail.com>
 */
class Google_Photos_Albums_Gallery_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    4.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'google-photos-albums-gallery',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
