<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.topinfosoft.com/
 * @since      1.0.0
 *
 * @package    Allow_Webp_Image
 * @subpackage Allow_Webp_Image/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Allow_Webp_Image
 * @subpackage Allow_Webp_Image/includes
 * @author     Top Infosoft <topinfosoft@gmail.com>
 */
class Allow_Webp_Image_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'allow-webp-image',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
