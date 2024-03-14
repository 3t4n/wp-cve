<?php
/**
 * Name:    Dev4Press\v43\Core\Quick\URL
 * Version: v4.3
 * Author:  Milan Petrovic
 * Email:   support@dev4press.com
 * Website: https://www.dev4press.com/
 *
 * @package Dev4Press Library
 *
 * == Copyright ==
 * Copyright 2008 - 2023 Milan Petrovic (email: support@dev4press.com)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>
 */

namespace Dev4Press\v43\Core\Quick;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class URL {
	public static function domain_name( string $url = '' ) {
		if ( empty( $url ) ) {
			return '';
		}

		return wp_parse_url( $url, PHP_URL_HOST );
	}

	public static function current_request_path() {
		$uri = isset( $_SERVER['REQUEST_URI'] ) ? sanitize_url( wp_unslash( $_SERVER['REQUEST_URI'] ) ) : ''; // phpcs:ignore WordPress.Security.EscapeOutput,WordPress.Security.NonceVerification,WordPress.Security.ValidatedSanitizedInput

		return wp_parse_url( $uri, PHP_URL_PATH );
	}

	public static function current_url_request() : string {
		$path_info = $_SERVER['PATH_INFO'] ?? ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		list( $path_info ) = explode( '?', $path_info );
		$path_info = str_replace( '%', '%25', $path_info );

		$request         = explode( '?', $_SERVER['REQUEST_URI'] ); // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		$req_uri         = $request[0];
		$req_query       = $request[1] ?? false;
		$home_path       = wp_parse_url( home_url(), PHP_URL_PATH );
		$home_path       = $home_path ? trim( $home_path, '/' ) : '';
		$home_path_regex = sprintf( '|^%s|i', preg_quote( $home_path, '|' ) );

		$req_uri = str_replace( $path_info, '', $req_uri );
		$req_uri = ltrim( $req_uri, '/' );
		$req_uri = preg_replace( $home_path_regex, '', $req_uri );
		$req_uri = ltrim( $req_uri, '/' );

		$url_request = $req_uri;

		if ( $req_query !== false ) {
			$url_request .= '?' . $req_query;
		}

		return $url_request;
	}

	public static function current_url( bool $use_wp = true ) : string {
		if ( $use_wp ) {
			return home_url( self::current_url_request() );
		} else {
			$s        = is_ssl() ? 's' : '';
			$protocol = Str::left( strtolower( $_SERVER['SERVER_PROTOCOL'] ), '/' ) . $s; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
			$port     = isset( $_SERVER['SERVER_PORT'] ) ? absint( $_SERVER['SERVER_PORT'] ) : 80;
			$port     = $port === 80 || $port === 443 ? '' : ':' . $port;

			return $protocol . '://' . sanitize_url( $_SERVER['SERVER_NAME'] ) . $port . sanitize_url( $_SERVER['REQUEST_URI'] );  // phpcs:ignore WordPress.Security.EscapeOutput,WordPress.Security.ValidatedSanitizedInput,WordPress.Security.NonceVerification
		}
	}

	public static function add_campaign_tracking( string $url, string $campaign = '', string $medium = '', string $content = '', string $term = '', $source = null ) : string {
		if ( ! empty( $campaign ) ) {
			$url = add_query_arg( 'utm_campaign', $campaign, $url );
		}

		if ( ! empty( $medium ) ) {
			$url = add_query_arg( 'utm_medium', $medium, $url );
		}

		if ( ! empty( $content ) ) {
			$url = add_query_arg( 'utm_content', $content, $url );
		}

		if ( ! empty( $term ) ) {
			$url = add_query_arg( 'utm_term', $term, $url );
		}

		if ( is_null( $source ) ) {
			$source = wp_parse_url( get_bloginfo( 'url' ), PHP_URL_HOST );
		}

		if ( ! empty( $source ) ) {
			$url = add_query_arg( 'utm_source', $source, $url );
		}

		return $url;
	}
}
