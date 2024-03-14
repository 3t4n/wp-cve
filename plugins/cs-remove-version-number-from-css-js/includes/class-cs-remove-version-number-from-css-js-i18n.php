<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://chetansatasiya.com/blog
 * @since      1.0.0
 *
 * @package    Cs_Remove_Version_Number_From_Css_Js
 * @subpackage Cs_Remove_Version_Number_From_Css_Js/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cs_Remove_Version_Number_From_Css_Js
 * @subpackage Cs_Remove_Version_Number_From_Css_Js/includes
 * @author     Chetan Satasiya <chetansatasiya88@gmail.com>
 */
class Cs_Remove_Version_Number_From_Css_Js_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cs-remove-version-number-from-css-js',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
