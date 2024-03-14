<?php
/**
 * Define the internationalization functionality
 *
 * @package WP Magazine Modules Lite
 * @since 1.0.0
 */
class Wpmagazine_modules_Lite_i18n {

	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-magazine-modules-lite',
			false,
			WPMAGAZINE_MODULES_LITE_PATH . '/languages/'
		);

	}
}