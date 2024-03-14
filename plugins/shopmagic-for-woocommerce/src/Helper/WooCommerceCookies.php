<?php
declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

/**
 * Manages cookies data using wc methods.
 */
final class WooCommerceCookies {
	public static function set( string $name, string $value, int $expire = 0 ): bool {
		if ( ! headers_sent() ) {
			setcookie( $name, $value, $expire, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '', is_ssl(), false );
		}

		$_COOKIE[ $name ] = $value;

		return true;
	}

	public static function is_set( string $name ): bool {
		return isset( $_COOKIE[ $name ] );
	}

	public static function get( string $name ): string {
		return isset( $_COOKIE[ $name ] ) ? (string) $_COOKIE[ $name ] : '';
	}

	public static function clear( string $name ): void {
		if ( isset( $_COOKIE[ $name ] ) ) {
			if ( ! headers_sent() ) {
				setcookie( $name, '', time() - HOUR_IN_SECONDS, COOKIEPATH ?: '/', COOKIE_DOMAIN ?: '', is_ssl(), false );
			}

			unset( $_COOKIE[ $name ] );
		}
	}
}
