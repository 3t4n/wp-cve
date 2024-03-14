<?php
/**
 * Class ZipEntry.
 *
 * @file class-zipentry.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Model;

use Exception;
use Pcloud\Classes\ZipFile\Constants\DosAttrs;
use Pcloud\Classes\ZipFile\Constants\GeneralPurposeBitFlag;
use Pcloud\Classes\ZipFile\Constants\UnixStat;
use Pcloud\Classes\ZipFile\Constants\ZipCompressionLevel;
use Pcloud\Classes\ZipFile\Constants\ZipCompressionMethod;
use Pcloud\Classes\ZipFile\Constants\ZipConstants;
use Pcloud\Classes\ZipFile\Constants\ZipEncryptionMethod;
use Pcloud\Classes\ZipFile\Constants\ZipPlatform;
use Pcloud\Classes\ZipFile\Constants\ZipVersion;
use Pcloud\Classes\ZipFile\Model\Extra\ExtraFieldsCollection;
use Pcloud\Classes\ZipFile\Model\Extra\ZipExtraField;
use Pcloud\Classes\ZipFile\Util\DateTimeConverter;
use Pcloud\Classes\ZipFile\Util\StringUtil;

/**
 * ZIP file entry.
 */
class ZipEntry {

	/**
	 * The unknown value for numeric properties.
	 *
	 * @var int UNKNOWN
	 */
	const UNKNOWN = - 1;

	/**
	 * Entry name (filename in archive).
	 *
	 * @var string $name
	 */
	private $name;

	/**
	 * Is directory.
	 *
	 * @var bool $is_directory
	 */
	private $is_directory;

	/**
	 * Zip entry contents.
	 *
	 * @var ZipData|null $data
	 */
	private $data = null;

	/**
	 * Made by platform.
	 *
	 * @var int $created_os
	 */
	private $created_os = self::UNKNOWN;

	/**
	 * Extracted by platform.
	 *
	 * @var int $extracted_os
	 */
	private $extracted_os = self::UNKNOWN;

	/**
	 * Software version.
	 *
	 * @var int $software_version
	 */
	private $software_version = self::UNKNOWN;

	/**
	 * Version needed to extract.
	 *
	 * @var int $extract_version
	 */
	private $extract_version = self::UNKNOWN;

	/**
	 * Compression method.
	 *
	 * @var int $compression_method
	 */
	private $compression_method = self::UNKNOWN;

	/**
	 * General purpose bit flags.
	 *
	 * @var int $general_purpose_bit_flags
	 */
	private $general_purpose_bit_flags = 0;

	/**
	 * Dos time.
	 *
	 * @var int $dos_time
	 */
	private $dos_time = self::UNKNOWN;

	/**
	 * Crc32.
	 *
	 * @var int $crc
	 */
	private $crc = self::UNKNOWN;

	/**
	 * Compressed size.
	 *
	 * @var int $compressed_size
	 */
	private $compressed_size = self::UNKNOWN;

	/**
	 * Uncompressed size.
	 *
	 * @var int $uncompressed_size
	 */
	private $uncompressed_size = self::UNKNOWN;

	/**
	 * Internal attributes.
	 *
	 * @var int $internal_attributes
	 */
	private $internal_attributes = 0;

	/**
	 * External attributes.
	 *
	 * @var int $external_attributes
	 */
	private $external_attributes = 0;

	/**
	 * Relative Offset Of Local File Header.
	 *
	 * @var int $local_header_offset
	 */
	private $local_header_offset = 0;

	/**
	 * Collections of Extra Fields in Central Directory.
	 * Keys from Header ID [int] and value Extra Field [ExtraField].
	 *
	 * @var ExtraFieldsCollection $cd_extra_fields
	 */
	protected $cd_extra_fields;

	/**
	 * Collections of Extra Fields int local header.
	 * Keys from Header ID [int] and value Extra Field [ExtraField].
	 *
	 * @var ExtraFieldsCollection $local_extra_fields
	 */
	protected $local_extra_fields;

	/**
	 * Comment field.
	 *
	 * @var string|null $comment
	 */
	private $comment = null;

	/**
	 * Encryption method.
	 *
	 * @var int $encryption_method
	 */
	private $encryption_method = ZipEncryptionMethod::NONE;

	/**
	 * Compression level.
	 *
	 * @var int $compression_level
	 */
	private $compression_level = ZipCompressionLevel::NORMAL;

	/**
	 * Entry name charset.
	 *
	 * @var string|null $charset
	 */
	private $charset = null;

	/**
	 * Class constructor.
	 *
	 * @param string            $name Entry name.
	 * @param string|null|mixed $charset Entry name charset.
	 * @throws Exception Throws Exception.
	 */
	public function __construct( string $name, $charset = null ) {
		$this->set_name( $name, $charset );
		$this->cd_extra_fields    = new ExtraFieldsCollection();
		$this->local_extra_fields = new ExtraFieldsCollection();
	}

	/**
	 * Set entry name.
	 *
	 * @param string            $name New entry name.
	 * @param string|null|mixed $charset Entry name charset.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	private function set_name( string $name, $charset = null ) {

		$name = ltrim( $name, '\\/' );
		if ( '' === $name ) {
			throw new Exception( 'Empty zip entry name' );
		}

		$length = strlen( $name );

		if ( 0xFFFF < $length ) {
			throw new Exception( 'Illegal zip entry name parameter' );
		}

		$this->set_charset( $charset );

		if ( null === $this->charset && ! StringUtil::is_ascii( $name ) ) {
			$this->enable_utf8_name( true );
		}

		$this->name = $name;
		$length     = strlen( $name );

		$this->is_directory = false;
		if ( $length >= 1 && ( '/' === $name[ $length - 1 ] ) ) {
			$this->is_directory = true;
		}

		$this->external_attributes = $this->is_directory ? DosAttrs::DOS_DIRECTORY : DosAttrs::DOS_ARCHIVE;

		if ( self::UNKNOWN !== $this->extract_version ) {
			$this->extract_version = max(
				$this->extract_version,
				$this->is_directory
					? ZipVersion::V20_DEFLATED_FOLDER_ZIP_CRYPTO
					: ZipVersion::V10_DEFAULT_MIN
			);
		}
	}

	/**
	 * Set charset.
	 *
	 * @param string|null|false $charset Charset.
	 * @return ZipEntry
	 * @throws Exception Throws exception.
	 */
	public function set_charset( $charset = null ): ZipEntry {
		if ( ! is_null( $charset ) && '' === strval( $charset ) ) {
			throw new Exception( 'Empty charset' );
		}
		$this->charset = $charset;

		return $this;
	}

	/**
	 * Get charset.
	 *
	 * @return string|null
	 */
	public function get_charset(): ?string {
		return $this->charset;
	}

	/**
	 * Returns the ZIP entry name.
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Get the data.
	 *
	 * @return ZipData|null
	 */
	public function get_data(): ?ZipData {
		return $this->data;
	}

	/**
	 * Get file path.
	 *
	 * @return ZipData|null
	 */
	public function get_path(): string {
		return $this->data->get_path();
	}

	/**
	 * Set data.
	 *
	 * @param ZipData|null $data The Zip data.
	 * @return void
	 */
	public function set_data( ZipData $data ) {
		$this->data = $data;
	}

	/**
	 * Get created os.
	 *
	 * @return int Platform.
	 */
	public function get_created_os(): int {
		return $this->created_os;
	}

	/**
	 * Set platform.
	 *
	 * @param int $platform Platform.
	 * @return ZipEntry
	 * @throws Exception Throws exception.
	 */
	public function set_created_os( int $platform ): self {
		if ( $platform < 0x00 || $platform > 0xFF ) {
			throw new Exception( 'Platform out of range' );
		}
		$this->created_os = $platform;

		return $this;
	}

	/**
	 * Get extracted os.
	 *
	 * @return int
	 */
	public function get_extracted_os(): int {
		return $this->extracted_os;
	}

	/**
	 * Set extracted OS.
	 *
	 * @param int $platform Platform.
	 * @return ZipEntry
	 * @throws Exception Throws exception.
	 */
	public function set_extracted_os( int $platform ): self {
		if ( 0x00 > $platform || 0xFF < $platform ) {
			throw new Exception( 'Platform out of range' );
		}
		$this->extracted_os = $platform;

		return $this;
	}

	/**
	 * Get software version.
	 *
	 * @return int
	 */
	public function get_software_version(): int {
		if ( self::UNKNOWN === $this->software_version ) {
			return $this->get_extract_version();
		}

		return $this->software_version;
	}

	/**
	 * Version needed to extract.
	 */
	public function get_extract_version(): int {
		if ( self::UNKNOWN === $this->extract_version ) {
			if ( ZipEncryptionMethod::is_win_zip_aes_method( $this->encryption_method ) ) {
				return ZipVersion::V51_ENCR_AES_RC2_CORRECT;
			}

			if ( ZipCompressionMethod::BZIP2 === $this->compression_method ) {
				return ZipVersion::V46_BZIP2;
			}

			if ( $this->is_zip64_extensions_required() ) {
				return ZipVersion::V45_ZIP64_EXT;
			}

			if (
				ZipCompressionMethod::DEFLATED === $this->compression_method
				|| $this->is_directory
				|| ZipEncryptionMethod::PKWARE === $this->encryption_method
			) {
				return ZipVersion::V20_DEFLATED_FOLDER_ZIP_CRYPTO;
			}

			return ZipVersion::V10_DEFAULT_MIN;
		}
		return $this->extract_version;
	}

	/**
	 * Returns the compressed size of this entry.
	 */
	public function get_compressed_size(): int {
		return $this->compressed_size;
	}

	/**
	 * Sets the compressed size of this entry.
	 *
	 * @param int $compressed_size The Compressed Size.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 * @internal
	 */
	public function set_compressed_size( int $compressed_size ): self {
		if ( self::UNKNOWN > $compressed_size ) {
			throw new Exception( 'Compressed size < ' . self::UNKNOWN );
		}
		$this->compressed_size = $compressed_size;
		return $this;
	}

	/**
	 * Returns the uncompressed size of this entry.
	 */
	public function get_uncompressed_size(): int {
		return $this->uncompressed_size;
	}

	/**
	 * Sets the uncompressed size of this entry.
	 *
	 * @param int $uncompressed_size The (Uncompressed) Size.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 * @internal
	 */
	public function set_uncompressed_size( int $uncompressed_size ): self {
		if ( self::UNKNOWN > $uncompressed_size ) {
			throw new Exception( 'Uncompressed size < ' . self::UNKNOWN );
		}
		$this->uncompressed_size = $uncompressed_size;

		return $this;
	}

	/**
	 * Return relative Offset Of Local File Header.
	 */
	public function get_local_header_offset(): int {
		return $this->local_header_offset;
	}

	/**
	 * Set local header offset.
	 *
	 * @param int $local_header_offset Local header offset.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 * @internal
	 */
	public function set_local_header_offset( int $local_header_offset ): self {
		if ( 0 > $local_header_offset ) {
			throw new Exception( 'Negative $local_header_offset' );
		}
		$this->local_header_offset = $local_header_offset;

		return $this;
	}

	/**
	 * Returns the General Purpose Bit Flags.
	 */
	public function get_general_purpose_bit_flags(): int {
		return $this->general_purpose_bit_flags;
	}

	/**
	 * Update compression level.
	 *
	 * @return void
	 */
	private function update_compression_level() {

		if ( ZipCompressionMethod::DEFLATED === $this->compression_method ) {

			$bit1 = $this->is_set_general_bit_flag( GeneralPurposeBitFlag::COMPRESSION_FLAG1 );
			$bit2 = $this->is_set_general_bit_flag( GeneralPurposeBitFlag::COMPRESSION_FLAG2 );

			if ( $bit1 && ! $bit2 ) {
				$this->compression_level = ZipCompressionLevel::MAXIMUM;
			} elseif ( ! $bit1 && $bit2 ) {
				$this->compression_level = ZipCompressionLevel::FAST;
			} elseif ( $bit1 && $bit2 ) {
				$this->compression_level = ZipCompressionLevel::SUPER_FAST;
			} else {
				$this->compression_level = ZipCompressionLevel::NORMAL;
			}
		}
	}

	/**
	 * Set general bit flag.
	 *
	 * @param int  $mask Mask.
	 * @param bool $enable Enabled.
	 * @return void
	 */
	private function set_general_bit_flag( int $mask, bool $enable ) {
		if ( $enable ) {
			$this->general_purpose_bit_flags |= $mask;
		} else {
			$this->general_purpose_bit_flags &= ~$mask;
		}
	}

	/**
	 * Is set general bit flag.
	 *
	 * @param int $mask Mask.
	 * @return bool
	 */
	private function is_set_general_bit_flag( int $mask ): bool {
		return ( $this->general_purpose_bit_flags & $mask ) === $mask;
	}

	/**
	 * Is data descriptor enabled.
	 *
	 * @return bool
	 */
	public function is_data_descriptor_enabled(): bool {
		return $this->is_set_general_bit_flag( GeneralPurposeBitFlag::DATA_DESCRIPTOR );
	}

	/**
	 * Enabling or disabling the use of the Data Descriptor block.
	 *
	 * @param bool|null $enabled Is enabled or not.
	 * @return void
	 */
	public function enable_data_descriptor( bool $enabled = true ) {
		$this->set_general_bit_flag( GeneralPurposeBitFlag::DATA_DESCRIPTOR, $enabled );
	}

	/**
	 * Enable UTF-8 name.
	 *
	 * @param bool $enabled Is Enabled.
	 * @return void
	 */
	public function enable_utf8_name( bool $enabled ) {
		$this->set_general_bit_flag( GeneralPurposeBitFlag::UTF8, $enabled );
	}

	/**
	 * Is UTF-8 flag.
	 *
	 * @return bool
	 */
	public function is_utf8_flag(): bool {
		return $this->is_set_general_bit_flag( GeneralPurposeBitFlag::UTF8 );
	}

	/**
	 * Returns true if and only if this ZIP entry is encrypted.
	 */
	public function is_encrypted(): bool {
		return $this->is_set_general_bit_flag( GeneralPurposeBitFlag::ENCRYPTION );
	}

	/**
	 * Returns the compression method for this entry.
	 */
	public function get_compression_method(): int {
		return $this->compression_method;
	}

	/**
	 * Sets the compression method for this entry.
	 *
	 * @param int $compression_method Compression method.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 */
	public function set_compression_method( int $compression_method ): self {
		if ( 0x0000 > $compression_method || 0xFFFF < $compression_method ) {
			throw new Exception( 'method out of range: ' . $compression_method );
		}
		$this->compression_method = $compression_method;
		$this->update_compression_level();
		$this->extract_version = self::UNKNOWN;
		return $this;
	}

	/**
	 * Get Dos Time.
	 */
	public function get_dos_time(): int {
		return $this->dos_time;
	}

	/**
	 * Set Dos Time.
	 *
	 * @param int $dos_time DOS time.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 */
	public function set_dos_time( int $dos_time ): self {
		if ( PHP_INT_SIZE === 8 ) {
			if ( 0x00000000 > $dos_time || 0xFFFFFFFF < $dos_time ) {
				throw new Exception( 'DosTime out of range' );
			}
		}
		$this->dos_time = $dos_time;
		return $this;
	}

	/**
	 * Set time from unix timestamp.
	 *
	 * @param int $unix_timestamp Unix Time stamp.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 */
	public function set_time( int $unix_timestamp ): self {
		if ( self::UNKNOWN !== $unix_timestamp ) {
			$this->set_dos_time( DateTimeConverter::unix_to_ms_dos( $unix_timestamp ) );
		} else {
			$this->dos_time = 0;
		}

		return $this;
	}

	/**
	 * Returns the external file attributes.
	 *
	 * @return int The external file attributes.
	 */
	public function get_external_attributes(): int {
		return $this->external_attributes;
	}

	/**
	 * Sets the external file attributes.
	 *
	 * @param int $external_attributes The external file attributes.
	 *
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 */
	public function set_external_attributes( int $external_attributes ): self {
		$this->external_attributes = $external_attributes;
		if ( 8 === PHP_INT_SIZE ) {
			if ( 0x00000000 > $external_attributes || 0xFFFFFFFF < $external_attributes ) {
				throw new Exception( 'external attributes out of range: ' . $external_attributes );
			}
		}
		$this->external_attributes = $external_attributes;
		return $this;
	}

	/**
	 * Returns the internal file attributes.
	 *
	 * @return int The internal file attributes.
	 */
	public function get_internal_attributes(): int {
		return $this->internal_attributes;
	}

	/**
	 * Returns true if and only if this ZIP entry represents a directory entry (i.e. end with '/').
	 */
	final public function is_directory(): bool {
		return $this->is_directory;
	}

	/**
	 * Get CD Extra fields.
	 *
	 * @return ExtraFieldsCollection
	 */
	public function get_cd_extra_fields(): ExtraFieldsCollection {
		return $this->cd_extra_fields;
	}

	/**
	 * Get Local extra fields.
	 *
	 * @return ExtraFieldsCollection
	 */
	public function get_local_extra_fields(): ExtraFieldsCollection {
		return $this->local_extra_fields;
	}

	/**
	 * Get local extra field.
	 *
	 * @param int $header_id Header ID.
	 * @return ZipExtraField|null
	 */
	public function get_local_extra_field( int $header_id ): ?ZipExtraField {
		return $this->local_extra_fields[ $header_id ];
	}

	/**
	 * Returns comment entry.
	 */
	public function get_comment(): string {
		return $this->comment ?? '';
	}

	/**
	 * Is data descriptor required.
	 *
	 * @return bool
	 */
	public function is_data_descriptor_required(): bool {
		return ( $this->get_crc() | $this->get_compressed_size() | $this->get_uncompressed_size() ) === self::UNKNOWN;
	}

	/**
	 * Return crc32 content or 0 for WinZip AES v2.
	 */
	public function get_crc(): int {
		return $this->crc;
	}

	/**
	 * Set crc32 content.
	 *
	 * @param int $crc CRC content.
	 * @return ZipEntry
	 * @internal
	 */
	public function set_crc( int $crc ): self {
		$this->crc = $crc;
		return $this;
	}

	/**
	 * Get encryption method.
	 *
	 * @return int
	 */
	public function get_encryption_method(): int {
		return $this->encryption_method;
	}

	/**
	 * Get compression level.
	 *
	 * @return int
	 */
	public function get_compression_level(): int {
		return $this->compression_level;
	}

	/**
	 * Sets Unix permissions in a way that is understood by Info-Zip's unzip command.
	 *
	 * @param int $mode Mode an int value.
	 * @return ZipEntry
	 * @throws Exception Throws Exception.
	 */
	public function set_unix_mode( int $mode ): self {
		$this->set_external_attributes(
			( $mode << 16 )
			| ( ( $mode & UnixStat::UNX_IWUSR ) === 0 ? DosAttrs::DOS_HIDDEN : 0 )
			| ( $this->is_directory() ? DosAttrs::DOS_DIRECTORY : DosAttrs::DOS_ARCHIVE )
		);
		$this->created_os = ZipPlatform::OS_UNIX;

		return $this;
	}

	/**
	 * Offset MUST be considered in decision about ZIP64 format - see
	 * description of Data Descriptor in ZIP File Format Specification.
	 */
	public function is_zip64_extensions_required(): bool {
		return $this->compressed_size > ZipConstants::ZIP64_MAGIC || $this->uncompressed_size > ZipConstants::ZIP64_MAGIC;
	}

	/**
	 * Class clone.
	 *
	 * @return void
	 */
	public function __clone() {
		$this->cd_extra_fields    = clone $this->cd_extra_fields;
		$this->local_extra_fields = clone $this->local_extra_fields;
		if ( null !== $this->data ) {
			$this->data = clone $this->data;
		}
	}
}
