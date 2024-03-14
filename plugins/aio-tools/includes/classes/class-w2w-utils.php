<?php

namespace AIOTools;

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

class W2W_Utils extends W2W_Abstract_Module {
	
	
	/**
	 * Check if a plugin is active or not.
	 * @since 3.8.3
	 */
	public static function is_plugin_active( $plugin ) {
		$is_plugin_active_for_network = false;

		$plugins = get_site_option( 'active_sitewide_plugins' );
		if ( isset( $plugins[ $plugin ] ) ) {
			$is_plugin_active_for_network = true;
		}

		return in_array( $plugin, (array) get_option( 'active_plugins', array() ), true ) || $is_plugin_active_for_network;
	}
}