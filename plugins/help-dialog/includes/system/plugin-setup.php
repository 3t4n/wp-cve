<?php

/**
 * Activate the plugin
 *
 * @copyright   Copyright (C) 2018, Echo Plugins
*/

/**
 * Activate this plugin i.e. setup tables, data etc.
 * NOT invoked on plugin updates
 *
 * @param bool $network_wide - If the plugin is being network-activated
 */
function ephd_activate_plugin( $network_wide=false ) {
	global $wpdb;

	if ( is_multisite() && $network_wide ) {
		foreach ( $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs LIMIT 100" ) as $blog_id ) {
			switch_to_blog( $blog_id );
			ephd_activate_plugin_do();
			restore_current_blog();
		}
	} else {
		ephd_activate_plugin_do();
	}
}

function ephd_activate_plugin_do() {

	// create DB table if does not exist
	$handler = new EPHD_Submissions_DB();
	$handler->create_table();
	$handler = new EPHD_Search_DB();
	$handler->create_table();
	$handler = new EPHD_Analytics_DB();
	$handler->create_table();
	$handler = new EPHD_FAQs_DB();
	$handler->create_table();
	$handler = new EPHD_Widgets_DB();
	$handler->create_table();

	// true if the plugin was activated for the first time since installation
	$plugin_version = get_option( 'ephd_version' );
	if ( empty( $plugin_version ) ) {

		EPHD_Utilities::save_wp_option( 'ephd_run_setup', true );

		set_transient( '_ephd_plugin_installed', true, HOUR_IN_SECONDS );

		// prepare Global Help Dialog config
		$config = ephd_get_instance()->global_config_obj->get_config();
		ephd_get_instance()->global_config_obj->update_config( $config );

		// prepare Widgets config
		$config = ephd_get_instance()->widgets_config_obj->get_config();
		ephd_get_instance()->widgets_config_obj->update_config( $config );

		// update Help Dialog versions
		EPHD_Utilities::save_wp_option( 'ephd_version', Echo_Help_Dialog::$version );
		EPHD_Utilities::save_wp_option( 'ephd_version_first', Echo_Help_Dialog::$version );
	}

	set_transient( '_ephd_plugin_activated', true, HOUR_IN_SECONDS );
}
register_activation_hook( Echo_Help_Dialog::$plugin_file, 'ephd_activate_plugin' );

/**
 * User deactivates this plugin so refresh the permalinks
 */
function ephd_deactivation() {

}
register_deactivation_hook( Echo_Help_Dialog::$plugin_file, 'ephd_deactivation' );
