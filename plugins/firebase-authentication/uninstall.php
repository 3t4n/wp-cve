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
 * @link       https://miniorange.com
 * @since      1.0.0
 *
 * @package    Firebase_Authentication
 */

// If uninstall not called from WordPress, then exit.
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	exit;
}

delete_option( 'mo_firebase_auth_project_id' );
delete_option( 'mo_firebase_auth_api_key' );
delete_option( 'mo_enable_firebase_auth' );
delete_option( 'mo_firebase_auth_disable_wordpress_login' );
delete_option( 'mo_firebase_auth_enable_admin_wp_login' );
delete_option( 'mo_firebase_auth_api_key' );
delete_option( 'mo_firebase_auth_kid1' );
delete_option( 'mo_firebase_auth_cert1' );
delete_option( 'mo_firebase_auth_kid2' );
delete_option( 'mo_firebase_auth_cert2' );
delete_option( 'mo_firebase_auth_woocommerce_intigration' );
delete_option( 'mo_enable_firebase_auto_register' );
delete_option( 'mo_firebase_auth_buddypress_intigration' );
delete_option( 'mo_fb_host_name' );
