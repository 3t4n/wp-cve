<?php
/**
 * Interface ZipOptions.
 *
 * @file zipoptions.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Constants;

/**
 * Interface ZipOptions
 */
interface ZipOptions {

	/**
	 * Uses the specified compression method.
	 */
	const COMPRESSION_METHOD = 'compression_method';

	/**
	 * Set the specified record modification time. or a string of any format.
	 */
	const MODIFIED_TIME = 'mtime';

}
