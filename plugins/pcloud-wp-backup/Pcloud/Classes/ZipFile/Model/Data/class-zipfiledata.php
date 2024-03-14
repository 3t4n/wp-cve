<?php
/**
 * Class ZipFileData
 *
 * @file class-zipfiledata.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model\Data;

use Exception;
use Pcloud\Classes\ZipFile\Model\ZipData;
use Pcloud\Classes\ZipFile\Model\ZipEntry;
use SplFileInfo;

/**
 * Class ZipFileData
 */
class ZipFileData implements ZipData {

	/**
	 * The SPLfile.
	 *
	 * @var SplFileInfo $file
	 */
	private $file;

	/**
	 * Class constructor.
	 *
	 * @param ZipEntry    $zip_entry Zip Entry.
	 * @param SplFileInfo $file_info Zip Entry.
	 * @throws Exception Throws Exception.
	 */
	public function __construct( ZipEntry $zip_entry, SplFileInfo $file_info ) {
		if ( ! $file_info->isFile() ) {
			throw new Exception( '$file_info is not a file.' );
		}
		if ( ! $file_info->isReadable() ) {
			throw new Exception( '$file_info is not readable.' );
		}
		$this->file = $file_info;
		$zip_entry->set_uncompressed_size( $file_info->getSize() );
	}

	/**
	 * Returns stream data or false if file no longer exists.
	 *
	 * @return resource|false
	 */
	public function get_data_as_stream() {
		if ( ! $this->file->isReadable() ) {
			return false;
		}

		return fopen( $this->file->getPathname(), 'rb' );
	}

	/**
	 * Get file path.
	 *
	 * @return string
	 */
	public function get_path(): string {
		return $this->file->getPathname();
	}
}
