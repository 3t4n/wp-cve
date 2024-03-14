<?php

function wp3cxw_sanitize_query_var( $text ) {
	$text = wp_unslash( $text );
	$text = wp_check_invalid_utf8( $text );

	if ( false !== strpos( $text, '<' ) ) {
		$text = wp_pre_kses_less_than( $text );
		$text = wp_strip_all_tags( $text );
	}

	$text = preg_replace( '/%[a-f0-9]{2}/i', '', $text );
	$text = preg_replace( '/ +/', ' ', $text );
	$text = trim( $text, ' ' );

	return $text;
}

function wp3cxw_normalize_newline( $text, $to = "\n" ) {
	if ( ! is_string( $text ) ) {
		return $text;
	}

	$nls = array( "\r\n", "\r", "\n" );

	if ( ! in_array( $to, $nls ) ) {
		return $text;
	}

	return str_replace( $nls, $to, $text );
}

function wp3cxw_normalize_newline_deep( $arr, $to = "\n" ) {
	if ( is_array( $arr ) ) {
		$result = array();

		foreach ( $arr as $key => $text ) {
			$result[$key] = wp3cxw_normalize_newline_deep( $text, $to );
		}

		return $result;
	}

	return wp3cxw_normalize_newline( $arr, $to );
}

/**
 * Check whether a string is a valid NAME token.
 *
 * ID and NAME tokens must begin with a letter ([A-Za-z])
 * and may be followed by any number of letters, digits ([0-9]),
 * hyphens ("-"), underscores ("_"), colons (":"), and periods (".").
 *
 * @see http://www.w3.org/TR/html401/types.html#h-6.2
 *
 * @return bool True if it is a valid name, false if not.
 */
function wp3cxw_is_name( $string ) {
	return preg_match( '/^[A-Za-z][-A-Za-z0-9_:.]*$/', $string );
}

function wp3cxw_sanitize_unit_tag( $tag ) {
	$tag = preg_replace( '/[^A-Za-z0-9_-]/', '', $tag );
	return $tag;
}

function wp3cxw_is_email( $email ) {
	$result = is_email( $email );
	return apply_filters( 'wp3cxw_is_email', $result, $email );
}

function wp3cxw_is_url( $url ) {
	$result = ( false !== filter_var( $url, FILTER_VALIDATE_URL ) );
	return apply_filters( 'wp3cxw_is_url', $result, $url );
}

function wp3cxw_is_tel( $tel ) {
	$result = preg_match( '%^[+]?[0-9()/ -]*$%', $tel );
	return apply_filters( 'wp3cxw_is_tel', $result, $tel );
}

function wp3cxw_is_number( $number ) {
	$result = is_numeric( $number );
	return apply_filters( 'wp3cxw_is_number', $result, $number );
}

function wp3cxw_is_date( $date ) {
	$result = preg_match( '/^([0-9]{4,})-([0-9]{2})-([0-9]{2})$/', $date, $matches );

	if ( $result ) {
		$result = checkdate( $matches[2], $matches[3], $matches[1] );
	}

	return apply_filters( 'wp3cxw_is_date', $result, $date );
}
