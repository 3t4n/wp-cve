<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://www.grafreak.net
 * @since      1.0.0
 *
 * @package    Pago_Redsys_Grafreak
 * @subpackage Pago_Redsys_Grafreak/includes
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Pago_Redsys_Grafreak
 * @subpackage Pago_Redsys_Grafreak/includes
 * @author     Adrian Cobo <adrian@grafreak.net>
 */
class Pago_Redsys_Grafreak_I18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'pago-redsys-grafreak',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
