<?php
declare(strict_types=1);

namespace TreBiMeteo;

use ItalyStrap\Debug\Debug;
use function apply_filters;
use function array_key_exists;
use function array_reverse;
use function defined;
use function explode;
use function function_exists;
use function implode;
use function is_callable;
use function parse_url;

function is_debug(): bool {
	return defined( 'WP_DEBUG' ) && WP_DEBUG;
}

function is_development(): bool {
	return function_exists( 'codecept_debug' ) && apply_filters( 'trebimeteo_is_development', true );
}

function log( ...$logs ): void {
	if ( is_callable( '\ItalyStrap\Debug\Debug::log' ) ) {
		Debug::log( ...$logs );
	}
}

function assert_host_name( string $url ): string {
	$parsed = parse_url( $url );

	if ( ! array_key_exists( 'host', $parsed ) ) {
		return $url;
	}

	return $parsed['host'];
//
//	$exploded = explode( '.', $parsed['host'] );
//
//	$exploded = array_reverse( $exploded );
//
//	$new_url = '';
//	foreach ( $exploded as $key => $value ) {
//		if ( $key > 2 ) {
//			continue;
//		}
//
//		$new_url = $value . '.' . $new_url;
//	}
//
//	return \rtrim( $new_url, '.' );
}
