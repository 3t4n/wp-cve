<?php
/**
 * Interface Zip64ExtraField.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model\Extra;

/**
 * Extra Field in a Local or Central Header of a ZIP archive.
 * It defines the common properties of all Extra Fields and how to
 * serialize/unserialize them to/from byte arrays.
 */
interface ZipExtraField {
	/**
	 * Returns the Header ID (type) of this Extra Field.
	 * The Header ID is an unsigned short integer (two bytes)
	 * which must be constant during the life cycle of this object.
	 */
	public function get_header_id(): int;

	/**
	 * The actual data to put into local file data - without Header-ID or length specifier.
	 *
	 * @return string The data.
	 */
	public function pack_local_file_data(): string;

	/**
	 * The actual data to put into central directory - without Header-ID or length specifier.
	 *
	 * @return string The data.
	 */
	public function pack_central_dir_data(): string;

	/**
	 * To string method.
	 *
	 * @return string
	 */
	public function __toString(): string;
}
