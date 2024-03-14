<?php

namespace PDFEmbedder\Helpers;

/**
 * Helper methods to perform various checks across the plugin.
 *
 * @since 4.7.0
 */
class Check {

	/**
	 * Check whether the string is json-encoded.
	 *
	 * @since 4.7.0
	 *
	 * @param string $json A string.
	 *
	 * @return bool
	 */
	public static function is_json( $json ): bool {

		return (
			is_string( $json ) &&
			is_array( json_decode( $json, true ) ) &&
			json_last_error() === JSON_ERROR_NONE
		);
	}

	/**
     * Check whether the site is in debug mode.
	 *
	 * @since 4.7.0
	 */
	public function is_debug(): bool {

		return defined( 'WP_DEBUG' ) && WP_DEBUG;
	}
}
