<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       devmaverick.com
 * @since      1.0.0
 *
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Code_Snippet_Dm
 * @subpackage Code_Snippet_Dm/includes
 * @author     George Cretu <george@devmaverick.com>
 */
class CSDM_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'code-snippet-dm',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
