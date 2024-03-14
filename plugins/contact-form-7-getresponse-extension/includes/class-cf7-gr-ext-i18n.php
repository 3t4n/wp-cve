<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://wensolutions.com/
 * @since      1.0.0
 *
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Cf7_Gr_Ext
 * @subpackage Cf7_Gr_Ext/includes
 * @author     WEN Solutions <info@wensolutions.com>
 */
class Cf7_Gr_Ext_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'cf7-gr-ext',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
