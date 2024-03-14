<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://themepure.net
 * @since      1.1.9
 *
 * @package    tpmeta
 * @subpackage tpmeta/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.9
 * @package    tpmeta
 * @subpackage tpmeta/includes
 * @author     ThemePure <basictheme400@gmail.com>
 */
class tpmeta_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.9
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pure-metafields',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
