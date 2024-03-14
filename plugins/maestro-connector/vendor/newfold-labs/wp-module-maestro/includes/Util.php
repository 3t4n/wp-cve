<?php

namespace NewfoldLabs\WP\Module\Maestro;

/**
 * A utility class for generic functions to use across the codebase
 *
 * @since 0.0.1
 */
class Util {
	/**
	 *
	 * A function to check if this is a BH site, checks for the plugin
	 * in the plugins list call.
	 *
	 * @return boolean If this is a bluehost site
	 */
	public function is_bluehost() {
		if ( ! function_exists( 'get_plugins' ) ) {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
		}
		$installed_plugins = get_plugins();
		if ( ! empty( $installed_plugins['bluehost-wordpress-plugin/bluehost-wordpress-plugin.php'] ) ) {
			if ( is_plugin_active( 'bluehost-wordpress-plugin/bluehost-wordpress-plugin.php' ) ) {
				return true;
			}
			return false;
		}
		return false;
	}

	/**
	 * A utility function to get the plugin file from plugin list
	 *
	 * @since 0.0.1
	 *
	 * @param array  $installed_plugins the list of installed plugins
	 * @param String $plugin_slug The slug for plugin
	 *
	 * @return String The plugin file
	 */
	public function get_plugin_file_from_slug( $installed_plugins, $plugin_slug ) {
		foreach ( $installed_plugins as $installed_plugin => $plugin_details ) {
			$installed_plugin_slug_array = explode( '/', $installed_plugin );
			$installed_plugin_slug       = reset( $installed_plugin_slug_array );
			if ( $installed_plugin_slug === $plugin_slug ) {
				return "$installed_plugin";
			}
		}
		return null;
	}
}
