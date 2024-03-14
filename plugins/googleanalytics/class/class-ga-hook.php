<?php
/**
 * Google Analytics hook class.
 *
 * @package GoogleAnalytics
 */

/**
 * Hook class.
 */
class Ga_Hook {

	/**
	 * Adds WordPress hooks.
	 *
	 * @param string $plugin_file_path Plugin file path string.
	 */
	public static function add_hooks( $plugin_file_path ) {
		register_activation_hook( $plugin_file_path, 'Ga_Admin::activate_googleanalytics' );
		register_deactivation_hook( $plugin_file_path, 'Ga_Admin::deactivate_googleanalytics' );
		register_uninstall_hook( $plugin_file_path, 'Ga_Admin::uninstall_googleanalytics' );
	}
}
