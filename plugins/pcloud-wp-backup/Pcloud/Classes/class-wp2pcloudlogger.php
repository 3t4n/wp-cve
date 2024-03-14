<?php
/**
 * WP2PcloudLogger class
 *
 * @file class-wp2pcloudlogger.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes;

/**
 * Class WP2PcloudLogger
 */
class WP2PcloudLogger {

	/**
	 * Generate new log
	 *
	 * @param string|null $initial_message Initial message ot be saved in the log.
	 *
	 * @return void
	 */
	public static function generate_new( ?string $initial_message = '' ) {

		wp2pcloudfuncs::set_storred_val( PCLOUD_LOG, $initial_message );

	}

	/**
	 * Get last log content
	 *
	 * @param bool $as_json Choose the see the last log data as JSON or raw string.
	 *
	 * @return string
	 */
	public static function read_last_log( ?bool $as_json = true ): string {
		$log = wp2pcloudfuncs::get_storred_val( PCLOUD_LOG );

		if ( $as_json ) {
			return wp_json_encode( array( 'log' => $log ) );
		} else {
			return $log;
		}
	}

	/**
	 * Updates the log records
	 *
	 * @param string|null $new_data String or HTML data to be added to the log.
	 *
	 * @return void
	 */
	public static function info( ?string $new_data ) {

		$new_data = trim( strip_tags( $new_data, '<span><strong><br><em>' ) );
		if ( strlen( $new_data ) < 2 ) {
			return;
		}

		$current_data = wp2pcloudfuncs::get_storred_val( PCLOUD_LOG );

		$current_data .= '<br/>' . gmdate( 'Y-m-d H:i:s' ) . ' - ' . $new_data;

		wp2pcloudfuncs::set_storred_val( PCLOUD_LOG, $current_data );

	}

	/**
	 * Clear log method, clears the log data
	 *
	 * @return void
	 */
	public static function clear_log() {
		wp2pcloudfuncs::set_storred_val( PCLOUD_LOG, '' );
	}
}
