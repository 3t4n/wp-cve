<?php
/**
 * Interface ZipCompressionLevel.
 *
 * @file zipcompressionlevel.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Compression levels for Deflate and BZIP2.
 *
 * For Methods 8 and 9 - Deflating
 * -------------------------------
 * Bit 2  Bit 1
 * 0      0    Normal (-en) compression option was used.
 * 0      1    Maximum (-exx/-ex) compression option was used.
 * 1      0    Fast (-ef) compression option was used.
 * 1      1    Super Fast (-es) compression option was used.
 *
 * Different programs encode compression level information in different ways:
 *
 * Deflate Compress Level  pkzip              zip      7z, WinRAR  WinZip
 * ----------------------  ----------------   -------  ----------  ------
 * Super Fast compression  1                                       1
 * Fast compression        2                  1, 2
 * Normal Compression      3 - 8 (5 default)  3 - 7    1 - 9
 * Maximum compression     9                  8, 9                 9
 */
interface ZipCompressionLevel {

	/**
	 * Compression level for super compression.
	 *
	 * @var int SUPER_FAST
	 */
	const SUPER_FAST = 1;

	/**
	 * Compression level for fast compression.
	 *
	 * @var int FAST
	 */
	const FAST = 2;

	/**
	 * Compression level for normal compression.
	 *
	 * @var int NORMAL
	 */
	const NORMAL = 5;

	/**
	 * Compression level for maximum compression.
	 *
	 * @var int MAXIMUM
	 */
	const MAXIMUM = 9;
}
