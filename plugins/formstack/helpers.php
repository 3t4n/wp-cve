<?php
function formstack_get_extra_url_params( $set_options ) {
	$extras = array();
	if ( isset( $set_options['nojquery'] ) && 'true' === $set_options['nojquery'] ) {
		$extras['nojquery'] = '1';
	}
	if ( isset( $set_options['nojqueryui'] ) && 'true' === $set_options['nojqueryui'] ) {
		$extras['nojqueryui'] = '1';
	}
	if ( isset( $set_options['nomodernizr'] ) && 'true' === $set_options['nomodernizr'] ) {
		$extras['nomodernizr'] = '1';
	}
	if ( isset( $set_options['no_style'] ) && 'true' === $set_options['no_style'] ) {
		$extras['no_style'] = '1';
	}
	if ( isset( $set_options['no_style_strict'] ) && 'true' === $set_options['no_style_strict'] ) {
		$extras['no_style_strict'] = '1';
	}
	return $extras;
}

/**
 * Logging helper function.
 *
 * @since 2.0.0
 *
 * @param mixed $message message or var to append to the log.
 * @return  bool true on success, false on failure
 */
function formstack_log_message( $message ) {

	if ( ! WP_DEBUG_LOG ) {
		return false;
	}
	// Check for non-strings.
	if ( ! is_string( $message ) ) {
		$message = print_r( $message, true );
	}

	// The logging file to write to.
	$file = WP_CONTENT_DIR . '/uploads/formstack-logs/formstack-' . date( 'Y-m-d' ) . '.log';

	if ( ! is_dir( WP_CONTENT_DIR . '/uploads/logs/' ) ) {
		mkdir( WP_CONTENT_DIR . '/uploads/logs/', 0777, true );
	}

	// Either append to log or create the log file.
	if ( file_exists( $file ) ) {
		// Append to the debug log.
		$result = @file_put_contents( $file, "\n[" . date( 'd-M-Y h:i:s A', current_time( 'timestamp' ) ) . '] ' . $message, FILE_APPEND );
	} else {
		// Append to the debug log.
		$result = @file_put_contents( $file, "\n[" . date( 'd-M-Y h:i:s A', current_time( 'timestamp' ) ) . '] ' . $message );
	}

	return ( false === $result ) ? false : true;
}
