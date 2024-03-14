<?php
/**
 * Delete Log File
 *
 * Delete a log file after time expires.
 *
 * @since   2.1.1
 *
 * @package WP_Data_Sync
 */

namespace WP_DataSync\App;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Delete a log file.
 *
 * @param $data
 * @param $response
 */

add_action( 'wpds_delete_log_file', function( $error_file ) {

	if( file_exists( $error_file ) ) {
		unlink( $error_file );
	}

}, 10, 1 );
