<?php
/**
 * Class ZipEncryptionMethod.
 *
 * @file class-zipencryptionmethod.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Class ZipEncryptionMethod
 */
final class ZipEncryptionMethod {

	/**
	 * Option NONE.
	 *
	 * @var int NONE
	 */
	const NONE = - 1;

	/**
	 * Traditional PKWARE encryption.
	 *
	 * @var int PKWARE
	 */
	const PKWARE = 0;

	/**
	 * WinZip AES-256.
	 *
	 * @var int WINZIP_AES_256
	 */
	const WINZIP_AES_256 = 1;

	/**
	 * WinZip AES-128.
	 *
	 * @var int WINZIP_AES_128
	 */
	const WINZIP_AES_128 = 2;

	/**
	 * WinZip AES-192.
	 *
	 * @var int WINZIP_AES_192
	 */
	const WINZIP_AES_192 = 3;

	/**
	 * Is winzip aes method ?.
	 *
	 * @param int $encryption_method Encryption method.
	 * @return bool
	 */
	public static function is_win_zip_aes_method( int $encryption_method ): bool {
		return in_array(
			$encryption_method,
			array(
				self::WINZIP_AES_256,
				self::WINZIP_AES_192,
				self::WINZIP_AES_128,
			),
			true
		);
	}
}
