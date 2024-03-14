<?php
/**
 * Class DosCodePage.
 *
 * @file class-doscodepage.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Class DosCodePage
 */
final class DosCodePage {

	/**
	 * From UTF-8.
	 *
	 * @param string $str String.
	 * @param string $dest_encoding Destination encoding.
	 * @return string
	 */
	public static function from_utf8( string $str, string $dest_encoding ): string {
		$s = iconv( 'UTF-8', $dest_encoding, $str );
		if ( false === $s ) {
			return $str;
		}
		return $s;
	}
}
