<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://iqonic.design/
 * @since      1.7.2
 *
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.7.2
 * @package    Marvy_Animation_Addons
 * @subpackage Marvy_Animation_Addons/includes
 * @author     Iqonic Design <hello@iqonic.design>
 */
class Marvy_Animation_Addons_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.7.2
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'marvy-animation-addons',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
