<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.hardkod.ru
 * @since      1.0.0
 *
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 */

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Ya_Turbo
 * @subpackage Ya_Turbo/includes
 * @author     hardkod.ru <hello@hardkod.ru>
 */
class Ya_Turbo_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'plugin-name',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
