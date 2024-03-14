<?php
/**
 * Helper
 *
 * @package     MailerLiteFIRSS\Helper
 * @since       1.0.0
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Better Debugging
 *
 * @param $args
 * @param bool $title
 */
function mailerlite_firss_debug( $args, $title = false ) {

	if ( defined( 'WP_DEBUG') && true === WP_DEBUG ) {

		if ( $title ) {
			echo '<h3>' . $title . '</h3>';
		}

		if ( $args ) {
			echo '<pre>';
			print_r($args);
			echo '</pre>';
		}
	}
}

/**
 * Debug logging
 *
 * @param $message
 */
function mailerlite_firss_debug_log( $message ) {

	if ( defined( 'WP_DEBUG') && true === WP_DEBUG ) {
		if (is_array( $message ) || is_object( $message ) ) {
			error_log( print_r( $message, true ) );
		} else {
			error_log( $message );
		}
	}
}