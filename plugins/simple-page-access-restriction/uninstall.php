<?php
/**
 * Uninstall Simple Page Access Restriction
 *
 * Deletes all the plugin data i.e.
 *         1. Plugin options.
 *         2. Integration.
 *
 * @package     Simple_Page_Access_Restriction
 * @subpackage  Uninstall
 * @copyright   All rights reserved Copyright (c) 2022, PluginsandSnippets.com
 * @author      PluginsandSnippets.com
 * @since       1.0.0
 */

// Exit if accessed directly.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function ps_simple_par_uninstall() {
	$ps_simple_par_settings = get_option( 'ps_simple_par_settings', array() );

	if ( is_array( $ps_simple_par_settings ) && isset( $ps_simple_par_settings['remove_data'] ) && 1 === intval( $ps_simple_par_settings['remove_data'] ) ) {
		global $wpdb;

		// Delete the option records from this plugin

		delete_option( 'ps_simple_par_settings' );
		delete_option( 'ps_simple_par_review_time' );
		delete_option( 'ps_simple_par_dismiss_review_notice' );

		// Delete the post meta (post type page) records from this plugin

		delete_metadata( 'post', 0, 'page_access_restricted', '', true );
	}
}

ps_simple_par_uninstall();