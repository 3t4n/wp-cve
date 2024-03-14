<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @link       https://if-so.com
 * @since      1.0.0
 *
 * @package    IfSo
 */

require_once ('services/plugin-settings-service/plugin-settings-service.class.php');

use IfSo\Services\PluginSettingsService;

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

function ifso_delete_plugin() {
    require_once plugin_dir_path( __FILE__ ) . 'includes/ifso-constants.php';
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-if-so-license.php';

	global $wpdb;

	delete_option( 'wpcf7' );

	$posts = get_posts( array(
		'numberposts' => -1,
		'post_type' => 'ifso_triggers',
		'post_status' => 'any' ) );

	foreach ( $posts as $post ) {
		wp_delete_post( $post->ID, true );
		delete_post_meta($post->ID, 'ifso_trigger_default');
		delete_post_meta($post->ID, 'ifso_trigger_rules');
		delete_post_meta($post->ID, 'ifso_trigger_version');
	}

	delete_option('ifso_groups_data');  //Remove "groups" data

	// retrieve our license key & item name from the DB
	$license = get_option('edd_ifso_license_key');
    $item_name = get_option('edd_ifso_license_item_name');
    $status = get_option('edd_ifso_license_status');

    $geo_license = get_option('edd_ifso_geo_license_key');
    $geo_item_name = get_option('edd_ifso_geo_license_item_name');
    $geo_status = get_option('edd_ifso_geo_license_status');


    //Deactivate the licenses
    if ($license !== false && $item_name !== false && $status == "valid") {
        $license = trim( $license );
        If_So_License::edd_api_deactivate_item($license, $item_name);
    }

    if ($geo_license !== false && $geo_item_name !== false && $geo_status == "valid") {
        $geo_license = trim( $geo_license);
        If_So_License::edd_api_deactivate_item($geo_license, $geo_item_name);
    }

	// Remove all the options related to If-So
	delete_option('edd_ifso_license_key');
	delete_option('edd_ifso_license_item_name');
	delete_option('edd_ifso_license_status');

	// Remove all the options related to Geolocation License. muliCohen.
	delete_option('edd_ifso_geo_license_key');
	delete_option('edd_ifso_geo_license_item_name');
	delete_option('edd_ifso_geo_license_status');

	//delete_option('edd_ifso_license_item_id'); //taken from class-if-so-uninstall - Check if needed
	//delete_option('edd_ifso_had_license');


	// Remove all transients in use
	delete_transient('ifso_transient_license_validation');
	delete_transient('ifso_transient_geo_license_validation');
}

function ifso_is_remove_checked() {
	$settings_service = PluginSettingsService\PluginSettingsService::get_instance();
	$to_remove = $settings_service->removePluginDataOption->get();

	return $to_remove;
}


if ( ifso_is_remove_checked() ) {
	 ifso_delete_plugin();
}