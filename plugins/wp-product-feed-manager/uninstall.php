<?php

/**
 * The uninstall functions.
 *
 * @package WP Product Feed Manager/Functions
 * @version 3.5.0
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

global $wpdb;

$upload_dir = wp_get_upload_dir();

if ( ! class_exists( 'WPPFM_Folders' ) ) {
	require_once __DIR__ . '/includes/setup/class-wppfm-folders.php';
}

// Stop the scheduled feed update actions.
wp_clear_scheduled_hook( 'wppfm_feed_update_schedule' );

// Remove the support folders.
WPPFM_Folders::delete_folder( $upload_dir['basedir'] . '/wppfm-channels' );
WPPFM_Folders::delete_folder( $upload_dir['basedir'] . '/wppfm-feeds' );
WPPFM_Folders::delete_folder( $upload_dir['basedir'] . '/wppfm-logs' );

$tables = array(
	$wpdb->prefix . 'feedmanager_country',
	$wpdb->prefix . 'feedmanager_feed_status',
	$wpdb->prefix . 'feedmanager_field_categories',
	$wpdb->prefix . 'feedmanager_channel',
	$wpdb->prefix . 'feedmanager_product_feed',
	$wpdb->prefix . 'feedmanager_product_feedmeta',
	$wpdb->prefix . 'feedmanager_source',
	$wpdb->prefix . 'feedmanager_errors',
);

// Remove the feedmanager tables.
foreach ( $tables as $table ) {
	//phpcs:ignore
	$wpdb->query( "DROP TABLE IF EXISTS $table" );
}

// unregister the plugin
unregister_plugin();

/**
 * Removes the registration info from the database
 */
function unregister_plugin() {
	// Retrieve the license from the database.
	$license = get_option( 'wppfm_lic_key' );

	foreach( wp_load_alloptions() as $option => $value ) {
		if( false !== strpos( $option, 'wppfm_' ) ) { delete_option( $option );	}
	}

	if ( $license ) { // If the plugin is a licensed version then deactivate it on the license server.
		// Data to send in our API request.
		$api_params = array(
			'edd_action' => 'deactivate_license',
			'license'    => $license,
			'item_name'  => rawurlencode( 'Woocommerce Google Feed Manager' ), // the name of the plugin in EDD.
			'url'        => home_url(),
		);

		// Call the custom API.
		wp_remote_post(
			'https://www.wpmarketingrobot.com/',
			array(
				'timeout' => 15,
				'body'    => $api_params,
			)
		);
	}
}
