<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://about.me/bharatkambariya
 * @since      2.1.0
 *
 * @package    Donations_Block
 * @subpackage Donations_Block/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      2.1.0
 * @package    Donations_Block
 * @subpackage Donations_Block/includes
 * @author     bharatkambariya <bharatkambariya@gmail.com>
 */
class Donations_Block_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    2.1.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'donations-block',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
