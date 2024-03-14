<?php
/**
 * Auto Update
 *
 * Auto update this plugin
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Auto Update Plugin
 *
 * @param $update
 * @param $item
 *
 * @return bool
 */

add_filter( 'auto_update_plugin', 'WP_DataSync\App\auto_update_plugin', 10, 2 );

function auto_update_plugin( $update, $item ) {

	if ( isset( $item->slug ) && 'wp-data-sync' === $item->slug ) {

		if ( Settings::is_checked( 'wp_data_sync_auto_update' ) ) {
			return true;
		}

	}

	return $update;

}
