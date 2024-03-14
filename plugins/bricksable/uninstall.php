<?php
/**
 * This file runs when the plugin in uninstalled (deleted).
 * This will not run when the plugin is deactivated.
 * Ideally you will add all your clean-up scripts here
 * that will clean-up unused meta, options, etc. in the database.
 *
 * @package Bricksable/Uninstall
 */

// If plugin is not being uninstalled, exit (do nothing).
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

// Do something here if plugin is being uninstalled.
if ( null !== get_option( 'bricksable_uninstall_on_delete' ) && 'on' === get_option( 'bricksable_uninstall_on_delete' ) ) {
	// Remove all matching options from the database.
	foreach ( wp_load_alloptions() as $option => $value ) {
		if ( strpos( $option, 'bricksable_' ) !== false ) {
			delete_option( $option );
		}
	}
}
