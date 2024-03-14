<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.linkedin.com/in/tomas-groulik/
 * @since      1.0.0
 *
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    GG_Monarch_Sidebar_Minimized_On_Mobile
 * @subpackage GG_Monarch_Sidebar_Minimized_On_Mobile/includes
 * @author     Tomas Groulik <tomas.groulik@gmail.com>
 */
class GG_Monarch_Sidebar_Minimized_On_Mobile_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'gg-monarch-sidebar-minimized-on-mobile',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
