<?php

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @author     NGOC DANG <ngocdt@rnlab.io>
 */

namespace AppBuilder;

defined( 'ABSPATH' ) || exit;

class I18n {
	/**
	 * I18n constructor.
	 */
	public function __construct() {
		$this->load_plugin_textdomain();
	}


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {
		load_plugin_textdomain(
			APP_BUILDER_NAME,
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}
}
