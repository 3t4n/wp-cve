<?php
/**
 * Fired when the plugin is delete via the WordPress admin.
 *
 * @package Clariti
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) || ! WP_UNINSTALL_PLUGIN ) {
	exit();
}

// Admin::API_KEY_OPTION.
delete_option( 'clariti_api_key' );

// Admin::API_HOST_OPTION.
delete_option( 'clariti_api_host' );

// Admin::API_SECRET_OPTION.
delete_option( 'clariti_api_secret' );

// Admin::PLUGIN_VERSION_OPTION.
delete_option( 'clariti_plugin_version' );
