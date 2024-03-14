<?php
/**
 * Interface ZipData.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model;

/**
 * Interface ZipData
 */
interface ZipData {

	/**
	 * Get data as stream.
	 *
	 * @return resource|false Returns stream data.
	 */
	public function get_data_as_stream();

	/**
	 * Get path method.
	 *
	 * @return string
	 */
	public function get_path(): string;
}
