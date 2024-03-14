<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://miniorange.com/
 * @since      1.1.1
 *
 * @package    Media_Restriction
 * @subpackage Media_Restriction/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.1
 * @package    Media_Restriction
 * @subpackage Media_Restriction/includes
 * @author     test <test@test.com>
 */
class Media_Restriction_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.1
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'media-restriction',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
