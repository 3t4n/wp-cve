<?php
/**
 * Functions
 *
 * @since  1.0.0
 * @package Demo Importer Plus
 */

if ( ! function_exists( 'demo_importer_plus_error_log' ) ) :

	/**
	 * Demo Importer Error Log
	 *
	 * @param string $message Message.
	 */
	function demo_importer_plus_error_log( $message = '' ) {
		if ( defined( 'WP_DEBUG_LOG' ) && WP_DEBUG_LOG ) {
			if ( is_array( $message ) ) {
				$message = wp_json_encode( $message );
			}

			error_log( '[' . date( 'd-m-Y H:i:s' ) . ']  ' . $message . "\n", 3, WP_CONTENT_DIR . DIRECTORY_SEPARATOR . 'demo-importer-plus.log' ); // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_error_log
		}
	}

endif;

/**
 * Clean variables using sanitize_text_field. Arrays are cleaned recursively.
 * Non-scalar values are ignored.
 *
 * @param string|array $var Data to sanitize.
 * @return string|array
 */
function demo_importer_plus_clean_vars( $var ) {
	if ( is_array( $var ) ) {
		return array_map( 'demo_importer_plus_clean_vars', $var );
	} else {
		return is_scalar( $var ) ? sanitize_text_field( $var ) : $var;
	}
}
