<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       www.mydirtyhobby.com/registrationplugin
 * @since      1.0.0
 *
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mdh_Promote
 * @subpackage Mdh_Promote/includes
 * @author     Mg <info@mindgeek.com>
 */
class Mdh_Promote_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mdh-promote',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
