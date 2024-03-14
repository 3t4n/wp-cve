<?php
/**
 * WinZipAesExtraField class.
 *
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model\Extra\Fields;

use Exception;
use Pcloud\Classes\ZipFile\Constants\ZipCompressionMethod;
use Pcloud\Classes\ZipFile\Constants\ZipEncryptionMethod;
use Pcloud\Classes\ZipFile\Model\Extra\ZipExtraField;
use Pcloud\Classes\ZipFile\Model\ZipEntry;

/**
 * WinZip AES Extra Field.
 */
final class WinZipAesExtraField implements ZipExtraField {

	const HEADER_ID = 0x9901;

	/**
	 * The vendor ID field should always be set to the two ASCII characters "AE".
	 * 'A' | ('E' << 8)
	 */
	const VENDOR_ID = 0x4541;

	/**
	 * Entries of this type do include the standard ZIP CRC-32 value.
	 */
	const VERSION_AE1 = 1;

	/**
	 * Entries of this type do not include the standard ZIP CRC-32 value.
	 */
	const VERSION_AE2 = 2;

	/**
	 * Integer mode value indicating AES encryption 128-bit strength.
	 */
	const KEY_STRENGTH_128BIT = 0x01;

	/**
	 * Integer mode value indicating AES encryption 192-bit strength.
	 */
	const KEY_STRENGTH_192BIT = 0x02;

	/**
	 * Integer mode value indicating AES encryption 256-bit strength.
	 *
	 * @var int KEY_STRENGTH_256BIT
	 */
	const KEY_STRENGTH_256BIT = 0x03;

	/**
	 * ALLOW_VENDOR_VERSIONS
	 */
	const ALLOW_VENDOR_VERSIONS = array(
		self::VERSION_AE1,
		self::VERSION_AE2,
	);

	/**
	 * ENCRYPTION_STRENGTHS
	 *
	 * @var array<int, int> ENCRYPTION_STRENGTHS
	 */
	const ENCRYPTION_STRENGTHS = array(
		self::KEY_STRENGTH_128BIT => 128,
		self::KEY_STRENGTH_192BIT => 192,
		self::KEY_STRENGTH_256BIT => 256,
	);

	/**
	 * MAP_KEY_STRENGTH_METHODS.
	 *
	 * @var array<int, int> MAP_KEY_STRENGTH_METHODS
	 */
	const MAP_KEY_STRENGTH_METHODS = array(
		self::KEY_STRENGTH_128BIT => ZipEncryptionMethod::WINZIP_AES_128,
		self::KEY_STRENGTH_192BIT => ZipEncryptionMethod::WINZIP_AES_192,
		self::KEY_STRENGTH_256BIT => ZipEncryptionMethod::WINZIP_AES_256,
	);

	/**
	 * Integer version number specific to the zip vendor.
	 *
	 * @var int $vendor_version
	 */
	private $vendor_version = self::VERSION_AE1;

	/**
	 * Integer mode value indicating AES encryption strength.
	 *
	 * @var int $key_strength
	 */
	private $key_strength = self::KEY_STRENGTH_256BIT;

	/**
	 * The actual compression method used to compress the file.
	 *
	 * @var int $compression_method
	 */
	private $compression_method;

	/**
	 * Class constructor.
	 *
	 * @param int $vendor_version Integer version number specific to the zip vendor.
	 * @param int $key_strength Integer mode value indicating AES encryption strength.
	 * @param int $compression_method The actual compression method used to compress the file.
	 *
	 * @throws Exception Throws exception.
	 */
	public function __construct( int $vendor_version, int $key_strength, int $compression_method ) {
		$this->set_vendor_version( $vendor_version );
		$this->set_key_strength( $key_strength );
		$this->set_compression_method( $compression_method );
	}

	/**
	 * The create method.
	 *
	 * @param ZipEntry $entry Entry.
	 *
	 * @return WinZipAesExtraField
	 * @throws Exception Throws exception.
	 */
	public static function create( ZipEntry $entry ): self {

		$key_strength = array_search( $entry->get_encryption_method(), self::MAP_KEY_STRENGTH_METHODS, true );

		if ( false === $key_strength ) {
			throw new Exception( 'Not support encryption method ' . $entry->get_encryption_method() );
		}

		// WinZip 11 will continue to use AE-2, with no CRC, for very small files
		// of less than 20 bytes. It will also use AE-2 for files compressed in
		// BZIP2 format, because this format has internal integrity checks
		// equivalent to a CRC check built in.
		//
		// @see https://www.winzip.com/win/en/aes_info.html.
		$vendor_version = (
			$entry->get_uncompressed_size() < 20
			|| $entry->get_compression_method() === ZipCompressionMethod::BZIP2
		)
			? self::VERSION_AE2
			: self::VERSION_AE1;

		$field = new self( $vendor_version, $key_strength, $entry->get_compression_method() );

		$entry->get_local_extra_fields()->add( $field );
		$entry->get_cd_extra_fields()->add( $field );

		return $field;
	}

	/**
	 * Returns the Header ID (type) of this Extra Field.
	 * The Header ID is an unsigned short integer (two bytes)
	 * which must be constant during the life cycle of this object.
	 */
	public function get_header_id(): int {
		return self::HEADER_ID;
	}

	/**
	 * The actual data to put into local file data - without Header-ID or length specifier.
	 *
	 * @return string
	 */
	public function pack_local_file_data(): string {
		return pack(
			'vvcv',
			$this->vendor_version,
			self::VENDOR_ID,
			$this->key_strength,
			$this->compression_method
		);
	}

	/**
	 * The actual data to put into central directory - without Header-ID or length specifier.
	 *
	 * @return string
	 */
	public function pack_central_dir_data(): string {
		return $this->pack_local_file_data();
	}

	/**
	 * Sets the vendor version.
	 *
	 * @param int $vendor_version The vendor version.
	 *
	 * @return void
	 * @throws Exception Throws exception.
	 */
	public function set_vendor_version( int $vendor_version ) {
		if ( ! in_array( $vendor_version, self::ALLOW_VENDOR_VERSIONS, true ) ) {
			throw new Exception(
				sprintf(
					'Unsupport WinZip AES vendor version: %d',
					$vendor_version
				)
			);
		}
		$this->vendor_version = $vendor_version;
	}

	/**
	 * Get key strength.
	 *
	 * @return int
	 */
	public function get_key_strength(): int {
		return $this->key_strength;
	}

	/**
	 * Set key strength.
	 *
	 * @param int $key_strength Key strength.
	 * @return void
	 * @throws Exception Throws exception.
	 */
	public function set_key_strength( int $key_strength ) {
		if ( ! isset( self::ENCRYPTION_STRENGTHS[ $key_strength ] ) ) {
			throw new Exception(
				sprintf(
					'Key strength %d not support value. Allow values: %s',
					$key_strength,
					implode( ', ', array_keys( self::ENCRYPTION_STRENGTHS ) )
				)
			);
		}
		$this->key_strength = $key_strength;
	}

	/**
	 * Get compression method.
	 *
	 * @return int
	 * @noinspection PhpUnused
	 */
	public function get_compression_method(): int {
		return $this->compression_method;
	}

	/**
	 * Set compression method.
	 *
	 * @param int $compression_method Compression method.
	 * @return void
	 * @throws Exception Throws exception.
	 */
	public function set_compression_method( int $compression_method ) {
		$this->compression_method = $compression_method;
	}

	/**
	 * Get encryption method.
	 *
	 * @return int
	 * @noinspection PhpUnused
	 * @throws Exception Throws exception.
	 */
	public function get_encryption_method(): int {
		$key_strength = $this->get_key_strength();

		if ( ! isset( self::MAP_KEY_STRENGTH_METHODS[ $key_strength ] ) ) {
			throw new Exception( 'Invalid encryption method' );
		}

		return self::MAP_KEY_STRENGTH_METHODS[ $key_strength ];
	}

	/**
	 * Is Version 2.
	 *
	 * @return bool
	 */
	public function is_v2(): bool {
		return self::VERSION_AE2 === $this->vendor_version;
	}

	/**
	 * To string method.
	 *
	 * @return string
	 */
	public function __toString(): string {
		return sprintf(
			'0x%04x WINZIP AES: VendorVersion=%d KeyStrength=0x%02x CompressionMethod=%s',
			__CLASS__,
			$this->vendor_version,
			$this->key_strength,
			$this->compression_method
		);
	}
}
