<?php
/**
 * Interface ZipConstants.
 *
 * @file zipconstants.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Zip Constants.
 */
interface ZipConstants {

	/**
	 * End Of Central Directory Record signature.
	 *
	 * @var int END_CD
	 */
	const END_CD = 0x06054B50; // "PK\005\006"

	/**
	 * Zip64 End Of Central Directory Record.
	 *
	 * @var int ZIP64_END_CD
	 */
	const ZIP64_END_CD = 0x06064B50; // "PK\006\006"

	/**
	 * Zip64 End Of Central Directory Locator.
	 *
	 * @var int ZIP64_END_CD_LOC
	 */
	const ZIP64_END_CD_LOC = 0x07064B50; // "PK\006\007"

	/**
	 * Central File Header signature.
	 *
	 * @var int CENTRAL_FILE_HEADER
	 */
	const CENTRAL_FILE_HEADER = 0x02014B50; // "PK\001\002"

	/**
	 * Local File Header signature.
	 *
	 * @var int LOCAL_FILE_HEADER
	 */
	const LOCAL_FILE_HEADER = 0x04034B50; // "PK\003\004"

	/**
	 * Data Descriptor signature.
	 *
	 * @var int DATA_DESCRIPTOR
	 */
	const DATA_DESCRIPTOR = 0x08074B50; // "PK\007\008"

	/**
	 * Value stored in four-byte size and similar fields if ZIP64 extensions are used.
	 *
	 * @var int ZIP64_MAGIC
	 */
	const ZIP64_MAGIC = 0xFFFFFFFF;

	/**
	 * The minimum length of the Local File Header record.
	 *
	 * Local file header signature      4.
	 * Version needed to extract        2.
	 * General purpose bit flag         2.
	 * Compression method               2.
	 * Last mod file time               2.
	 * Last mod file date               2.
	 * Crc-32                           4.
	 * Compressed size                  4.
	 * Uncompressed size                4.
	 * File name length                 2.
	 * Extra field length               2.
	 *
	 * @var int LFH_FILENAME_POS
	 */
	const LFH_FILENAME_POS = 30;

	/**
	 * The minimum length of the Zip64 End Of Central Directory Record.
	 *
	 * Zip64 end of central dir.
	 * Signature                        4.
	 * Size of zip64 end of central.
	 * Directory record                 8.
	 * Version made by                  2.
	 * Version needed to extract        2.
	 * Number of this disk              4.
	 * Number of the disk with the start of the central directory 4.
	 * Total number of entries in the central directory on this disk   8.
	 * Total number of entries in the central directory 8.
	 * Size of the central directory    8.
	 * Offset of start of central.
	 * Directory with respect to.
	 * The starting disk number         8.
	 *
	 * @var int ZIP64_END_OF_CD_LEN
	 */
	const ZIP64_END_OF_CD_LEN = 56;
}
