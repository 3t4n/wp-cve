<?php
/**
 * View
 *
 * Load a veiw template
 *
 * @since   1.0.0
 *
 * @package WP_Data_Sync_Api
 */

namespace WP_DataSync\App;

function view( $path_file, $args = [] ) {

	if( isset( $args['args'] ) && is_array( $args['args'] ) ) {
		extract( $args['args'] );
	}
	else {
		extract( $args );
	}

	$view = WPDSYNC_VIEWS . "$path_file.php";

	$view = apply_filters( 'wp_data_sync_view', $view, $path_file );

	if ( file_exists( $view ) ) {
		include $view;
	}

}