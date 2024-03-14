<?php
/**
 * Class Zip64ExtraField.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model\Extra\Fields;

use Exception;
use Pcloud\Classes\ZipFile\Model\Extra\ZipExtraField;

/**
 * ZIP64 Extra Field.
 */
final class Zip64ExtraField implements ZipExtraField {
	/**
	 * The Header ID for a ZIP64 Extended Information Extra Field.
	 *
	 * @var int
	 */
	const HEADER_ID = 0x0001;

	/**
	 * Uncompressed size.
	 *
	 * @var int|null $uncompressed_size
	 */
	private $uncompressed_size;

	/**
	 * Compressed size.
	 *
	 * @var int|null $compressed_size
	 */
	private $compressed_size;

	/**
	 * Local header offset.
	 *
	 * @var int|null $local_header_offset
	 */
	private $local_header_offset;

	/**
	 * Disk start.
	 *
	 * @var int|null $disk_start
	 */
	private $disk_start;

	/**
	 * Class constructor.
	 *
	 * @param int|null $uncompressed_size Uncompressed size.
	 * @param int|null $compressed_size Compressed size.
	 * @param int|null $local_header_offset Local header offset.
	 * @param int|null $disk_start Disk start.
	 */
	public function __construct( int $uncompressed_size = null, int $compressed_size = null, int $local_header_offset = null, int $disk_start = null ) {
		$this->uncompressed_size   = $uncompressed_size;
		$this->compressed_size     = $compressed_size;
		$this->local_header_offset = $local_header_offset;
		$this->disk_start          = $disk_start;
	}

	/**
	 * Returns the Header ID (type) of this Extra Field.
	 * The Header ID is an unsigned short integer (two bytes) which must be constant during the life cycle of this object.
	 */
	public function get_header_id(): int {
		return self::HEADER_ID;
	}

	/**
	 * The actual data to put into local file data - without Header-ID or length specifier.
	 *
	 * @return string
	 * @throws Exception Throws exception.
	 */
	public function pack_local_file_data(): string {
		if ( null !== $this->uncompressed_size || null !== $this->compressed_size ) {
			if ( null !== $this->uncompressed_size || null !== $this->compressed_size ) {
				throw new Exception(
					'Zip64 extended information must contain both size values in the local file header.'
				);
			}

			return $this->pack_sizes();
		}

		return '';
	}

	/**
	 * Pack sizes.
	 *
	 * @return string
	 */
	private function pack_sizes(): string {
		$data = '';

		if ( null !== $this->uncompressed_size ) {
			$data .= pack( 'P', $this->uncompressed_size );
		}

		if ( null !== $this->compressed_size ) {
			$data .= pack( 'P', $this->compressed_size );
		}

		return $data;
	}

	/**
	 * The actual data to put into central directory - without Header-ID or length specifier.
	 *
	 * @return string the data
	 */
	public function pack_central_dir_data(): string {
		$data = $this->pack_sizes();

		if ( null !== $this->local_header_offset ) {
			$data .= pack( 'P', $this->local_header_offset );
		}

		if ( null !== $this->disk_start ) {
			$data .= pack( 'V', $this->disk_start );
		}

		return $data;
	}

	/**
	 * Get uncompressed size.
	 *
	 * @return int|null
	 * @noinspection PhpUnused
	 */
	public function get_uncompressed_size(): ?int {
		return $this->uncompressed_size;
	}

	/**
	 * Set uncompressed size.
	 *
	 * @param int|null $uncompressed_size Uncompressed size.
	 * @return void
	 */
	public function set_uncompressed_size( ?int $uncompressed_size ) {
		$this->uncompressed_size = $uncompressed_size;
	}

	/**
	 * Get compressed size.
	 *
	 * @return int|null
	 * @noinspection PhpUnused
	 */
	public function get_compressed_size(): ?int {
		return $this->compressed_size;
	}

	/**
	 * Set compressed size.
	 *
	 * @param int|null $compressed_size Compressed size.
	 * @return void
	 */
	public function set_compressed_size( ?int $compressed_size ) {
		$this->compressed_size = $compressed_size;
	}

	/**
	 * Get local header offset.
	 *
	 * @return int|null
	 * @noinspection PhpUnused
	 */
	public function get_local_header_offset(): ?int {
		return $this->local_header_offset;
	}

	/**
	 * Set local header offset.
	 *
	 * @param int|null $local_header_offset Local header offset.
	 * @return void
	 */
	public function set_local_header_offset( ?int $local_header_offset ) {
		$this->local_header_offset = $local_header_offset;
	}

	/**
	 * To string method.
	 *
	 * @return string
	 */
	public function __toString(): string {

		$args    = array( self::HEADER_ID );
		$format  = '0x%04x ZIP64: ';
		$formats = array();

		if ( null !== $this->uncompressed_size ) {
			$formats[] = 'SIZE=%d';
			$args[]    = $this->uncompressed_size;
		}

		if ( null !== $this->compressed_size ) {
			$formats[] = 'COMP_SIZE=%d';
			$args[]    = $this->compressed_size;
		}

		if ( null !== $this->local_header_offset ) {
			$formats[] = 'OFFSET=%d';
			$args[]    = $this->local_header_offset;
		}

		if ( null !== $this->disk_start ) {
			$formats[] = 'DISK_START=%d';
			$args[]    = $this->disk_start;
		}

		$format .= implode( ' ', $formats );

		return vsprintf( $format, $args );
	}
}
