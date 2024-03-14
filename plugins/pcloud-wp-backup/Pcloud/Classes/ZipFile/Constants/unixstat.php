<?php
/**
 * Interface UnixStat.
 *
 * @file unixstat.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Unix stat constants.
 */
interface UnixStat {

	/**
	 * Unix symbolic link (not SysV, Amiga).
	 *
	 * @var int UNX_IFLNK
	 */
	const UNX_IFLNK = 0120000;

	/**
	 * Unix write permission: owner.
	 *
	 * @var int UNX_IWUSR
	 */
	const UNX_IWUSR = 00200;
}
