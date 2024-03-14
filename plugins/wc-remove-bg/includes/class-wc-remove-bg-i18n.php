<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://fresh-d.biz/wocommerce-remove-background.html
 * @since      1.0.0
 *
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    wc-remove-bg
 * @subpackage wc-remove-bg/includes
 * @author     Fresh-d <info@fresh-d.biz>
 */
class Remove_BG_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wc-remove-bg',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
