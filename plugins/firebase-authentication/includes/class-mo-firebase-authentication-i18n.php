<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Firebase_Authentication
 * @subpackage Firebase_Authentication/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Firebase_Authentication
 * @subpackage Firebase_Authentication/includes
 * @author     miniOrange <info@miniorange.com>
 */
class MO_Firebase_Authentication_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'firebase-authentication',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
