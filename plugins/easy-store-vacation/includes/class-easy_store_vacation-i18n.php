<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://samuilmarinov.co.uk
 * @since      1.1.6
 *
 * @package    Easy_store_vacation
 * @subpackage Easy_store_vacation/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.1.6
 * @package    Easy_store_vacation
 * @subpackage Easy_store_vacation/includes
 * @author     Samuil Marinov <samuil.marinov@gmail.com>
 */
class Easy_store_vacation_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.1.6
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'easy_store_vacation',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
