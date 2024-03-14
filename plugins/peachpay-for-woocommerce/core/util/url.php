<?php
/**
 * PeachPay URL Utility Files.
 *
 * @package PeachPay
 */

if ( ! defined( 'PEACHPAY_ABSPATH' ) ) {
	exit;
}

/**
 * Determines if the given site is active
 *
 * @param string $site The site domain to check.
 */
function peachpay_is_site( string $site ) {
	return strpos( get_site_url(), $site ) !== false;
}

/**
 * Determines which environment we are running in so we can call
 * the correct PeachPay API.
 *
 * @param string $mode a parameter used in conjunction with testing if it's test mode if true, will use test mode routes.
 * @param bool   $force_us forces the region to be us-east-1 if true.
 */
function peachpay_api_url( $mode = 'detect', $force_us = false ) {
	require_once PEACHPAY_ABSPATH . 'core/admin/class-peachpay-account-region.php';

	$region = PeachPay_Account_Region::get_setting( 'region' );
	if ( 'us-staging' !== $region && 'local' !== $region && $force_us ) {
		$region = 'us-east-1';
	}

	$is_test_environment = ( peachpay_is_test_mode() && 'detect' === $mode ) || 'test' === $mode;

	if ( 'us-east-1' === $region ) {
		return $is_test_environment ? 'https://dev.peachpay.app/' : 'https://prod.peachpay.app/';
	} elseif ( 'ap-southeast-2' === $region ) {
		return $is_test_environment ? 'https://dev-ap-southeast-2.peachpay.app/' : 'https://prod-ap-southeast-2.peachpay.app/';
	} elseif ( 'us-staging' === $region ) {
		return $is_test_environment ? 'https://dev.peachpay.app/' : 'https://dev.peachpay.app/';
	} elseif ( 'local' === $region ) {
		return $is_test_environment ? 'https://dev.peachpay.local/' : 'https://prod.peachpay.local/';
	} else {
		return $is_test_environment ? 'https://dev.peachpay.app/' : 'https://prod.peachpay.app/';
	}
}

/**
 * Gets the left most subdomain out of a URL.
 *
 * @param string $url .
 */
function peachpay_subdomain( $url ) {
	$parsed_url = wp_parse_url( $url );
	$host       = explode( '.', $parsed_url['host'] );
	return $host[0];
}

/**
 * Gets a file version date for versioning URLs.
 *
 * @param string $file Relative path within the root plugin folder.
 */
function peachpay_file_version( $file ) {
	return gmdate( 'ymd-Gis', filemtime( PEACHPAY_ABSPATH . $file ) );
}

/**
 * Gets a files url inside the peachpay plugin. Used for css, js, imageages, and other assets.
 *
 * @example ```
 * peachpay_url( 'public/css/peachpay.css' ); // returns https://woo.store.local/wp-content/plugins/peachpay-for-woocommerce/public/css/peachpay.css
 * ```
 * @param string $file Relative path within the root plugin folder.
 */
function peachpay_url( $file ) {
	// "." is to include the current directory.
	return plugin_dir_url( PEACHPAY_ABSPATH . '.' ) . $file;
}

/**
 * Gets only the URL base from the current domain
 *
 * @param bool $scheme Determines whether to return with the scheme included.
 */
function peachpay_get_site_url( $scheme = true ) {
	$parsed_url = wp_parse_url( get_site_url() );
	return true === $scheme ? $parsed_url['scheme'] . '://' . $parsed_url['host'] : $parsed_url['host'];
}

/**
 * Gets the versioned URL for a script, style, or image attribute.
 *
 * @param string $url The URL to version.
 * @param bool   $echo Whether to echo the URL or return it.
 */
function peachpay_version_url( $url, $echo = true ) {
	$url_hash = peachpay_url( $url ) . '?v=' . peachpay_file_version( $url );
	if ( $echo ) {
		echo esc_attr( $url_hash );
	}

	return $url_hash;
}
