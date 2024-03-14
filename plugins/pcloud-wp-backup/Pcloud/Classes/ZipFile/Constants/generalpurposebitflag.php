<?php
/**
 * Interface GeneralPurposeBitFlag.
 *
 * @file generalpurposebitflag.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Interface GeneralPurposeBitFlag
 */
interface GeneralPurposeBitFlag {

	/**
	 * General Purpose Bit Flag mask for encrypted data.
	 * Bit 0: If set, indicates that the file is encrypted.
	 */
	const ENCRYPTION = 1 << 0;

	/**
	 * Compression Flag Bit 1 for method Deflating.
	 *
	 * Bit 2  Bit 1
	 * 0      0    Normal compression
	 * 0      1    Maximum compression
	 * 1      0    Fast compression
	 * 1      1    Super Fast compression
	 */
	const COMPRESSION_FLAG1 = 1 << 1;

	/**
	 * Compression Flag Bit 2 for method Deflating.
	 *
	 * Bit 2  Bit 1
	 * 0      0    Normal compression
	 * 0      1    Maximum compression
	 * 1      0    Fast compression
	 * 1      1    Super Fast compression
	 */
	const COMPRESSION_FLAG2 = 1 << 2;

	/**
	 * General Purpose Bit Flag mask for data descriptor.
	 *
	 * Bit 3: If this bit is set, the fields crc-32, compressed
	 * size and uncompressed size are set to zero in the
	 * local header. The correct values are put in the data
	 * descriptor immediately following the compressed data.
	 */
	const DATA_DESCRIPTOR = 1 << 3;

	/**
	 * General Purpose Bit Flag mask for UTF-8.
	 *
	 * Bit 11: Language encoding flag (EFS).
	 * If this bit is set, the filename and comment fields
	 * for this file MUST be encoded using UTF-8. (see APPENDIX D)
	 */
	const UTF8 = 1 << 11;
}
