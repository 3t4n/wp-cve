<?php
/**
 * Interface ZipVersion.
 *
 * @file zipversion.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Version needed to extract or software version.
 * interface ZipVersion
 */
interface ZipVersion {

	/**
	 * 1.0 - Default value.
	 *
	 * @var int V10_DEFAULT_MIN
	 */
	const V10_DEFAULT_MIN = 10;

	/**
	 * 2.0 - File is a folder (directory).
	 * 2.0 - File is compressed using Deflate compression.
	 * 2.0 - File is encrypted using traditional PKWARE encryption.
	 *
	 * @var int V20_DEFLATED_FOLDER_ZIP_CRYPTO
	 */
	const V20_DEFLATED_FOLDER_ZIP_CRYPTO = 20;

	/**
	 * 4.5 - File uses ZIP64 format extensions.
	 *
	 * @var int V45_ZIP64_EXT
	 */
	const V45_ZIP64_EXT = 45;

	/**
	 * 4.6 - File is compressed using BZIP2 compression.
	 *
	 * @var int V46_BZIP2
	 */
	const V46_BZIP2 = 46;

	/**
	 * 5.1 - File is encrypted using AES encryption
	 * 5.1 - File is encrypted using corrected RC2 encryption**.
	 *
	 * @var int V51_ENCR_AES_RC2_CORRECT
	 */
	const V51_ENCR_AES_RC2_CORRECT = 51;
}
