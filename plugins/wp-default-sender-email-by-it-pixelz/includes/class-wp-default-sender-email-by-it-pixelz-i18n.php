<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://profiles.wordpress.org/itpixelz/
 * @since      1.0.0
 *
 * @package    Wp_Default_Sender_Email_By_It_Pixelz
 * @subpackage Wp_Default_Sender_Email_By_It_Pixelz/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Default_Sender_Email_By_It_Pixelz
 * @subpackage Wp_Default_Sender_Email_By_It_Pixelz/includes
 * @author     Umar Draz <umar.draz001@gmail.com>
 */
class Wp_Default_Sender_Email_By_It_Pixelz_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-default-sender-email-by-it-pixelz',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
