<?php
/**
 * It fires on plugin deletion.

 * @package Freesoul Deactivate Plugins
 */

if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) && ! defined( 'FDP_RESET_SETTINGS' ) ) {
	die();
	exit;
}

delete_site_option( 'eos_dp_activation_info' );
delete_site_option( 'eos_dp_new_plugin_activated' );
delete_site_option( 'eos_dp_general_setts' );
delete_site_option( 'eos_dp_archives' );
delete_site_option( 'eos_dp_search' );
delete_site_option( 'eos_dp_mobile' );
delete_site_option( 'eos_dp_one_place' );
delete_site_option( 'eos_dp_browser' );
delete_site_option( 'eos_dp_by_url' );
delete_site_option( 'eos_post_types_plugins' );
delete_site_option( 'eos_dp_need_custom_url' );
delete_site_option( 'eos_dp_opts' );
delete_site_option( 'eos_dp_admin_theme' );
delete_site_option( 'eos_dp_admin_menu' );
delete_site_option( 'eos_dp_admin_setts' );
delete_site_option( 'eos_dp_admin_url_theme' );
delete_site_option( 'eos_dp_by_admin_url' );
delete_site_option( 'eos_dp_user_options' );
delete_site_option( 'eos_dp_integration_actions' );
delete_site_option( 'eos_dp_integretion_actions_theme' );
delete_site_option( 'eos_dp_pro_main' );
delete_site_option( 'eos_dp_roles_manager' );
delete_site_option( 'eos_dp_admin_menu' );
delete_site_option( 'eos_dp_admin_submenu' );
delete_site_option( 'eos_dp_admin_page_hooks' );
delete_site_option( 'fdp_code_profiler' );
delete_site_option( 'fdp_plugin_slug_names' );
delete_site_option( 'eos_dp_by_rest_api' );
delete_site_option( 'fdp_site_id' );
delete_site_option( 'fdp_last_save' );
delete_site_option( 'fdp_addons' );
delete_post_meta_by_key( '_eos_deactive_plugins_key' );
delete_site_transient( 'fdp_update_check_response_body' );
$timestamp = wp_next_scheduled( 'eos_dp_cron' );
if ( $timestamp ) {
	wp_unschedule_event( $timestamp, 'eos_dp_cron' );
}
remove_role( 'fdp_plugins_manager' );
remove_role( 'fdp_plugins_viewer' );
$upload_dirs = wp_upload_dir();
$dirPath     = $upload_dirs['basedir'] . '/FDP/fdp-single-options';
if ( is_dir( $dirPath ) ) {
	$files = array();
	$n     = 1;
	do {
		$files_n = glob( $dirPath . implode( '', array_fill( 0, $n, '/*' ) ) );
		if ( ! empty( $files_n ) ) {
			$files[ $n - 1 ] = $files_n;
		}
		++$n;
	} while ( ! empty( $files_n ) );
	$files = array_reverse( $files );
	foreach ( $files as $k => $values ) {
		foreach ( $values as $value ) {
			if ( is_dir( $value ) ) {
				rmdir( $value );
			} else {
				if ( file_exists( $value ) ) {
					unlink( $value );
				}
			}
		}
	}
	rmdir( $dirPath );
}
delete_metadata( 'user', 0, 'fdp_toplevel_admin_menu', '', true );
delete_metadata( 'user', 0, 'fdp_admin_notices', '', true );
