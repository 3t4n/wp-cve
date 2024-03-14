<?php
/**
 * Plugin Update
 *
 * Run functions on plugin update.
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'init', 'WP_DataSync\App\plugin_update' );
add_action( 'admin_init', 'WP_DataSync\App\plugin_update' );

/**
 * Run plugin update functions.
 */

function plugin_update() {

	$current_version = get_option( 'WPDSYNC_VERSION', '1.0.0' );

	if ( WPDSYNC_VERSION !== $current_version ) {

		ItemRequest::create_table();
		$settings = Settings::instance();
		$settings->set_option_defaults();

		if ( version_compare( $current_version, '2.1.5', '<' ) ) {
			$settings->delete_all_log_files();
			Log::write( 'deleted-error-logs', 'Old Log Files Deleted' );
		}

		update_option( 'WPDSYNC_VERSION', WPDSYNC_VERSION );

	}

}