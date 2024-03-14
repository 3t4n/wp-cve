<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://http://phoeniixx.com/
 * @since      1.0.0
 *
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Phoen_Pincode_Zipcode
 * @subpackage Phoen_Pincode_Zipcode/includes
 * @author     PHOENIIXX TEAM <raghavendra@phoeniixx.com>
 */
class Phoen_Pincode_Zipcode_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'phoen-pincode-zipcode',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
