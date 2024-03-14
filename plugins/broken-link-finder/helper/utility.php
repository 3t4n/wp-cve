<?php
/**
 * This file is used for parsing link.
 *
 * @package broken-link-finder/helper
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
/**
 * Function for making link relative to absolute.
 *
 * @param mixed $rel relative.
 * @param mixed $base base.
 * @return string
 */
function moblc_relative_to_absolute( $rel, $base ) {
	$parse_base = wp_parse_url( $base );
	$scheme     = $parse_base['scheme'];
	$host       = $parse_base['host'];
	$path       = $parse_base['path'];

	if ( strpos( $rel, '//' ) === 0 ) {
		return $scheme . ':' . $rel;
	}

	if ( wp_parse_url( $rel, PHP_URL_SCHEME ) !== '' ) {
		return $rel;
	}

	if ( '#' === $rel[0] || '?' === $rel[0] ) {
		return $base . $rel;
	}

	$path = preg_replace( '#/[^/]*$#', '', $path );

	if ( '/' === $rel[0] ) {
		$path = '';
	}
	$abs = $host . $path . '/' . $rel;
	$abs = preg_replace( '[(/\.?/)]', '/', $abs );
	$abs = preg_replace( '[\/(?!\.\.)[^\/]+\/(\.\.\/)+]', '/', $abs );

	return $scheme . '://' . $abs;
}

