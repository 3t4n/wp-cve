<?php

declare( strict_types=1 );

namespace WPDesk\ShopMagic\Helper;

class RestRequestUtil {

	/**
	 * @deprecated 3.0.9 Use RestUrlGenerator
	 * @codeCoverageIgnore
	 */
	public static function get_url( string $path = '' ): string {
		return get_rest_url( null, '/shopmagic/v1/' . ltrim( $path, '/' ) );
	}

	public static function is_rest_request(): bool {
		if ( empty( $_SERVER['REQUEST_URI'] ) ) {
			return false;
		}

		$rest_prefix         = trailingslashit( rest_get_url_prefix() );

		// phpcs:disable WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		return ( false !== strpos( $_SERVER['REQUEST_URI'], $rest_prefix ) );
	}

}
