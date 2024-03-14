<?php
/**
 * Interface DosAttrs.
 *
 * @file dosattrs.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Interface DosAttrs
 */
interface DosAttrs {

	/**
	 * DOS File Attribute Hidden.
	 *
	 * @var int DOS_HIDDEN
	 */
	const DOS_HIDDEN = 0x02;

	/**
	 * DOS File Attribute Directory.
	 *
	 * @var int DOS_DIRECTORY
	 */
	const DOS_DIRECTORY = 0x10;

	/**
	 * DOS File Attribute Archive.
	 *
	 * @var int DOS_ARCHIVE
	 */
	const DOS_ARCHIVE = 0x20;
}
