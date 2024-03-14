<?php
/**
 * Class ZipNewData
 *
 * @file class-zipnewdata.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model\Data;

use Exception;
use Pcloud\Classes\ZipFile\Model\ZipData;
use Pcloud\Classes\ZipFile\Model\ZipEntry;

/**
 * The class contains a streaming resource with new content added to the ZIP archive.
 */
class ZipNewData implements ZipData {

	/**
	 * A static variable allows closing the stream in the destructor
	 * only if it is its sole holder.
	 *
	 * @var array<int, int> array of resource ids and the number of class clones
	 */
	private static $guard_cloned_stream = array();

	/**
	 * Stream resource.
	 *
	 * @var resource $stream
	 */
	private $stream;

	/**
	 * Class constructor.
	 *
	 * @param ZipEntry        $zip_entry Zip Entry.
	 * @param string|resource $data Raw string data or resource.
	 * @noinspection PhpMissingParamTypeInspection
	 * @throws Exception Throws Exception.
	 */
	public function __construct( ZipEntry $zip_entry, $data ) {

		if ( is_string( $data ) ) {

			$zip_entry->set_uncompressed_size( strlen( $data ) );

			$handle = fopen( 'php://temp', 'w+b' );

			if ( ! $handle ) {
				// @codeCoverageIgnoreStart
				throw new Exception( 'A temporary resource cannot be opened for writing.' );
				// @codeCoverageIgnoreEnd
			}
			fwrite( $handle, $data );
			rewind( $handle );

			$this->stream = $handle;

		} elseif ( is_resource( $data ) ) {
			$this->stream = $data;
		}

		$resource_id                               = (int) $this->stream;
		self::$guard_cloned_stream[ $resource_id ] = 0;

		if ( isset( self::$guard_cloned_stream[ $resource_id ] ) ) {
			self::$guard_cloned_stream[ $resource_id ] = self::$guard_cloned_stream[ $resource_id ] + 1;
		}
	}

	/**
	 * Returns stream data.
	 *
	 * @return resource|false
	 */
	public function get_data_as_stream() {
		if ( ! is_resource( $this->stream ) ) {
			return false;
		}
		return $this->stream;
	}

	/**
	 * The clone method.
	 *
	 * @return void
	 */
	public function __clone() {

		$resource_id = (int) $this->stream;

		self::$guard_cloned_stream[ $resource_id ] = 1;
		if ( isset( self::$guard_cloned_stream[ $resource_id ] ) ) {
			self::$guard_cloned_stream[ $resource_id ] = self::$guard_cloned_stream[ $resource_id ] + 1;
		}
	}

	/**
	 * The stream will be closed when closing the zip archive.
	 * The method implements protection against closing the stream of the cloned object.
	 */
	public function __destruct() {

		$resource_id = (int) $this->stream;

		if ( isset( self::$guard_cloned_stream[ $resource_id ] ) && self::$guard_cloned_stream[ $resource_id ] > 0 ) {
			self::$guard_cloned_stream[ $resource_id ] --;
			return;
		}

		if ( is_resource( $this->stream ) ) {
			fclose( $this->stream );
		}
	}

	/**
	 * Get path.
	 *
	 * @return string
	 */
	public function get_path(): string {
		return '';
	}
}
