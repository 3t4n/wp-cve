<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://atikul99.github.io/atikul
 * @since      1.0.0
 *
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ai_Preloader
 * @subpackage Ai_Preloader/includes
 * @author     Atikul Islam <atikulislam94@gmail.com>
 */
class Ai_Preloader_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'ai-preloader',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
