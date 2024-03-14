<?php
/**
 * Class ZipCompressionMethod.
 *
 * @file class-zipcompressionmethod.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Class ZipCompressionMethod
 */
final class ZipCompressionMethod {

	/**
	 * Compression method Store.
	 *
	 * @var int STORED
	 */
	const STORED = 0;

	/**
	 * Compression method Deflate.
	 *
	 * @var int DEFLATED
	 */
	const DEFLATED = 8;

	/**
	 * Compression method Bzip2.
	 *
	 * @var int BZIP2
	 */
	const BZIP2 = 12;

	/**
	 * Compression method AES-Encryption.
	 *
	 * @var int WINZIP_AES
	 */
	const WINZIP_AES = 99;

	/**
	 * Compression Methods.
	 *
	 * @var array ZIP_COMPRESSION_METHODS
	 */
	const ZIP_COMPRESSION_METHODS = array(
		self::STORED     => 'Stored',
		1                => 'Shrunk',
		2                => 'Reduced compression factor 1',
		3                => 'Reduced compression factor 2',
		4                => 'Reduced compression factor 3',
		5                => 'Reduced compression factor 4',
		6                => 'Imploded',
		7                => 'Reserved for Tokenizing compression algorithm',
		self::DEFLATED   => 'Deflated',
		9                => 'Enhanced Deflating using Deflate64(tm)',
		10               => 'PKWARE Data Compression Library Imploding',
		11               => 'Reserved by PKWARE',
		self::BZIP2      => 'BZIP2',
		13               => 'Reserved by PKWARE',
		14               => 'LZMA',
		15               => 'Reserved by PKWARE',
		16               => 'Reserved by PKWARE',
		17               => 'Reserved by PKWARE',
		18               => 'File is compressed using IBM TERSE (new)',
		19               => 'IBM LZ77 z Architecture (PFS)',
		96               => 'WinZip JPEG Compression',
		97               => 'WavPack compressed data',
		98               => 'PPMd version I, Rev 1',
		self::WINZIP_AES => 'AES Encryption',
	);

	/**
	 * Get compression method name.
	 *
	 * @param int $value Value to check.
	 * @return string
	 */
	public static function get_compression_method_name( int $value ): string {
		return self::ZIP_COMPRESSION_METHODS[ $value ] ?? 'Unknown Method';
	}
}
