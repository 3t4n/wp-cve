<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ays_Chatgpt_Assistant
 * @subpackage Ays_Chatgpt_Assistant/includes
 * @author     Ays_ChatGPT Assistant Team <info@ays-pro.com>
 */
class Chatgpt_Assistant_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			"ays-chatgpt-assistant",
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
