<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://cyberfoxdigital.co.uk
 * @since      1.0.0
 *
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cf_Christmasification
 * @subpackage Cf_Christmasification/includes
 * @author     Cyber Fox <info@cyberfoxdigital.co.uk>
 */
class Cf_Christmasification_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cf-christmasification',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
