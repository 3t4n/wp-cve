<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://walterpinem.me/
 * @since      1.0.0
 *
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Mylinks
 * @subpackage Wp_Mylinks/includes
 * @author     Walter Pinem <hello@walterpinem.me>
 */
class Wp_Mylinks_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-mylinks',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
