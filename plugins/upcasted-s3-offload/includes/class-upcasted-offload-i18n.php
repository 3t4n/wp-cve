<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.upcasted.com
 * @since      1.0.0
 *
 * @package    Upcasted_S3_Offload
 * @subpackage Upcasted_S3_Offload/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Upcasted_S3_Offload
 * @subpackage Upcasted_S3_Offload/includes
 * @author     Upcasted <contact@upcasted.com>
 */
class Upcasted_S3_Offload_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'upcasted-s3-offload',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
}
