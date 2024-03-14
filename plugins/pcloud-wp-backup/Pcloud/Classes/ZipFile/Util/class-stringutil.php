<?php
/**
 * Class StringUtil
 *
 * @file class-stringutil.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Util;

/**
 * String Util.
 *
 * @internal
 */
final class StringUtil {

	/**
	 * Ends with.
	 *
	 * @param string $haystack Haystack.
	 * @param string $needle Needle.
	 * @return bool
	 */
	public static function ends_with( string $haystack, string $needle ): bool {
		return '' === $needle || ( '' !== $haystack && 0 === substr_compare( $haystack, $needle, - strlen( $needle ) ) );
	}

	/**
	 * Is ASCII ?.
	 *
	 * @param string $name Item name.
	 * @return bool
	 */
	public static function is_ascii( string $name ): bool {
		return preg_match( '~[^\x20-\x7e]~', $name ) === 0;
	}
}
