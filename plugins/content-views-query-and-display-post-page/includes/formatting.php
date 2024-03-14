<?php
/**
 * Formatting HTML
 *
 * @subpackage	Includes
 * @license		GPL-2.0+
 * @copyright	CVPro <http://www.contentviewspro.com/>
 * @since		1.9.1
 */
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Sanitize View ID
 *
 * @since 1.9.1
 * @param string $view_id
 * @return string
 */
function cv_sanitize_vid( $view_id ) {
	return preg_replace( '/[\W]/', '', (string) $view_id );
}

/**
 * Sanitize HTML data attribute=value
 *
 * @since 1.9.1
 * @param string $data
 * @return string
 */
function cv_sanitize_html_data( $data ) {
	return strip_tags( $data );
}

/**
 * Sanitize content of HTML tag
 *
 * @since 1.9.1
 * @param string $string
 * @return string
 */
function cv_sanitize_tag_content( $string, $remove_breaks = false ) {
	$string = preg_replace( '@<(script)[^>]*?>.*?</\\1>@si', '', $string );

	if ( $remove_breaks )
		$string = preg_replace( '/[\r\n\t ]+/', ' ', $string );

	return trim( $string );
}

/**
 * For WordPress 4.8.3 and after
 */
if ( !function_exists( 'cv_esc_sql' ) ) {
	function cv_esc_sql( $data ) {
		// skip for rebuild key, prevent error
		if ( is_array( $data ) && !empty( $data[ 'cvp_replace_layout_page' ] ) ) {
			return $data;
		}

		$result = esc_sql( $data );

		global $wpdb;
		if ( method_exists( $wpdb, 'remove_placeholder_escape' ) ) {
			return cv_remove_placeholder_escape( $result );
		} else {
			return $result;
		}
	}

}

function cv_remove_placeholder_escape( $data ) {
	global $wpdb;
	if ( is_array( $data ) ) {
		foreach ( $data as $k => $v ) {
			if ( is_array( $v ) ) {
				$data[ $k ] = cv_remove_placeholder_escape( $v );
			} else {
				$data[ $k ] = $wpdb->remove_placeholder_escape( $v );
			}
		}
	} else {
		$data = $wpdb->remove_placeholder_escape( $data );
	}

	return $data;
}

