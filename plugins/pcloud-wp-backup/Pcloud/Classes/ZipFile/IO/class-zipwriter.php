<?php
/**
 * Class ZipWriter.
 *
 * @file class-zipwriter.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\IO;

use Exception;
use Pcloud\Classes\ZipFile\Constants\DosCodePage;
use Pcloud\Classes\ZipFile\Constants\ZipCompressionMethod;
use Pcloud\Classes\ZipFile\Constants\ZipConstants;
use Pcloud\Classes\ZipFile\Constants\ZipEncryptionMethod;
use Pcloud\Classes\ZipFile\Constants\ZipPlatform;
use Pcloud\Classes\ZipFile\Constants\ZipVersion;
use Pcloud\Classes\ZipFile\Model\Extra\Fields\WinZipAesExtraField;
use Pcloud\Classes\ZipFile\Model\Extra\Fields\Zip64ExtraField;
use Pcloud\Classes\ZipFile\Model\ZipContainer;
use Pcloud\Classes\ZipFile\Model\ZipEntry;

/**
 * Class ZipWriter
 */
class ZipWriter {

	/**
	 * Chunk read size.
	 *
	 * @var int CHUNK_SIZE
	 */
	const CHUNK_SIZE = 8192;

	/**
	 * Zip container.
	 *
	 * @var ZipContainer $zip_container
	 */
	protected $zip_container;

	/**
	 * Class contructor.
	 *
	 * @param ZipContainer $container Container.
	 */
	public function __construct( ZipContainer $container ) {
		$this->zip_container = clone $container;
	}

	/**
	 * Write file.
	 *
	 * @param resource $out_stream Output stream.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	public function write( $out_stream ) {
		if ( ! is_resource( $out_stream ) ) {
			throw new Exception( '$outStream must be resource' );
		}
		$this->write_local_block( $out_stream );
		$cd_offset = ftell( $out_stream );
		$this->write_central_directory_block( $out_stream );
		$cd_size = ftell( $out_stream ) - $cd_offset;
		$this->write_end_of_central_directory_block( $out_stream, $cd_offset, $cd_size );
	}

	/**
	 * Write local block.
	 *
	 * @param resource $out_stream Out stream.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	protected function write_local_block( $out_stream ) {

		$zip_entries = $this->zip_container->get_entries();

		foreach ( $zip_entries as $zip_entry ) {

			$file_path = $zip_entry->get_path();

			if ( ! file_exists( $file_path ) || ! is_readable( $file_path ) ) {
				$this->zip_container->delete_entry( $zip_entry );
				continue;
			}

			$this->write_local_header( $out_stream, $zip_entry );
			$this->write_data( $out_stream, $zip_entry );

			if ( $zip_entry->is_data_descriptor_enabled() ) {
				$this->write_data_descriptor( $out_stream, $zip_entry );
			}
		}
	}

	/**
	 * Write local header.
	 *
	 * @param resource $out_stream Out stream.
	 * @param ZipEntry $entry Zip entry.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	protected function write_local_header( $out_stream, ZipEntry $entry ) {

		$relative_offset = ftell( $out_stream );

		$entry->set_local_header_offset( $relative_offset );

		if ( $entry->is_encrypted() && ZipEncryptionMethod::PKWARE === $entry->get_encryption_method() ) {
			$entry->enable_data_descriptor();
		}

		$dd                = $entry->is_data_descriptor_required() || $entry->is_data_descriptor_enabled();
		$compressed_size   = $entry->get_compressed_size();
		$uncompressed_size = $entry->get_uncompressed_size();

		$entry->get_local_extra_fields()->remove( Zip64ExtraField::HEADER_ID );

		if ( $compressed_size > ZipConstants::ZIP64_MAGIC || $uncompressed_size > ZipConstants::ZIP64_MAGIC ) {
			$entry->get_local_extra_fields()->add(
				new Zip64ExtraField( $uncompressed_size, $compressed_size )
			);

			$compressed_size   = ZipConstants::ZIP64_MAGIC;
			$uncompressed_size = ZipConstants::ZIP64_MAGIC;
		}

		$compression_method = $entry->get_compression_method();
		$crc                = $entry->get_crc();

		if ( $entry->is_encrypted() && ZipEncryptionMethod::is_win_zip_aes_method( $entry->get_encryption_method() ) ) {

			/**
			 * Win Zip Aes Extra.
			 *
			 * @var WinZipAesExtraField|null $win_zip_aes_extra
			 */
			$win_zip_aes_extra = $entry->get_local_extra_field( WinZipAesExtraField::HEADER_ID );
			if ( null === $win_zip_aes_extra ) {
				$win_zip_aes_extra = WinZipAesExtraField::create( $entry );
			}

			if ( $win_zip_aes_extra->is_v2() ) {
				$crc = 0;
			}
			$compression_method = ZipCompressionMethod::WINZIP_AES;
		}

		$extra       = $this->get_extra_fields_contents( $entry, true );
		$name        = $entry->get_name();
		$dos_charset = $entry->get_charset();

		if ( null !== $dos_charset && ! $entry->is_utf8_flag() ) {
			$name = DosCodePage::from_utf8( $name, $dos_charset );
		}

		$name_length  = strlen( $name );
		$extra_length = strlen( $extra );

		$size = $name_length + $extra_length;

		if ( $size > 0xFFFF ) {
			throw new Exception(
				sprintf(
					'%s (the total size of %s bytes for the name, extra fields and comment exceeds the maximum size of %d bytes)',
					$entry->get_name(),
					$size,
					0xFFFF
				)
			);
		}

		$extracted_by = ( $entry->get_extracted_os() << 8 ) | $entry->get_extract_version();

		fwrite(
			$out_stream,
			pack(
				'VvvvVVVVvv',
				ZipConstants::LOCAL_FILE_HEADER,
				$extracted_by,
				$entry->get_general_purpose_bit_flags(),
				$compression_method,
				$entry->get_dos_time(),
				$dd ? 0 : $crc,
				$dd ? 0 : $compressed_size,
				$dd ? 0 : $uncompressed_size,
				$name_length,
				$extra_length
			)
		);

		if ( $name_length > 0 ) {
			fwrite( $out_stream, $name );
		}

		if ( $extra_length > 0 ) {
			fwrite( $out_stream, $extra );
		}
	}

	/**
	 * Merges the local file data fields of the given ZipExtraFields.
	 *
	 * @param ZipEntry $entry Entry.
	 * @param bool     $local Is Local? .
	 * @throws Exception Throws Exception.
	 */
	protected function get_extra_fields_contents( ZipEntry $entry, bool $local ): string {

		$collection = $local ? $entry->get_local_extra_fields() : $entry->get_cd_extra_fields();
		$extra_data = '';

		foreach ( $collection as $extra_field ) {
			if ( $local ) {
				$data = $extra_field->pack_local_file_data();
			} else {
				$data = $extra_field->pack_central_dir_data();
			}
			$extra_data .= pack(
				'vv',
				$extra_field->get_header_id(),
				strlen( $data )
			);
			$extra_data .= $data;
		}

		$size = strlen( $extra_data );
		if ( 0xFFFF < $size ) {
			throw new Exception( sprintf( 'Size extra out of range: %d. Extra data: %s', $size, $extra_data ) );
		}

		return $extra_data;
	}

	/**
	 * Write data.
	 *
	 * @param resource $out_stream Out stream.
	 * @param ZipEntry $entry Zip entry.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	protected function write_data( $out_stream, ZipEntry $entry ) {

		$zip_data = $entry->get_data();

		if ( null === $zip_data ) {
			if ( $entry->is_directory() ) {
				return;
			}

			throw new Exception( sprintf( 'No zip data for entry "%s"', $entry->get_name() ) );
		}

		$entry_stream = $zip_data->get_data_as_stream();
		if ( is_bool( $entry_stream ) && false === $entry_stream ) {
			return;
		}

		if ( stream_get_meta_data( $entry_stream )['seekable'] ) {
			rewind( $entry_stream );
		}

		$uncompressed_size = $entry->get_uncompressed_size();

		$pos_before_write = ftell( $out_stream );
		$context_filter   = $this->append_compression_filter( $out_stream, $entry );
		$checksum         = $this->write_and_count_checksum( $entry_stream, $out_stream, $uncompressed_size );

		if ( null !== $context_filter ) {
			stream_filter_remove( $context_filter );
			$context_filter = null;
		}

		fseek( $out_stream, 0, SEEK_END );
		$compressed_size = ftell( $out_stream ) - $pos_before_write;

		$entry->set_compressed_size( $compressed_size );
		$entry->set_crc( $checksum );

		if ( ! $entry->is_data_descriptor_enabled() ) {
			if ( $uncompressed_size > ZipConstants::ZIP64_MAGIC || $compressed_size > ZipConstants::ZIP64_MAGIC ) {

				/**
				 * Zip64 Extra Local.
				 *
				 * @var Zip64ExtraField|null $zip64_extra_local
				 */
				$zip64_extra_local = $entry->get_local_extra_field( Zip64ExtraField::HEADER_ID );

				if ( null !== $zip64_extra_local ) {

					$zip64_extra_local->set_compressed_size( $compressed_size );
					$zip64_extra_local->set_uncompressed_size( $uncompressed_size );

					$pos_extra = $entry->get_local_header_offset() + ZipConstants::LFH_FILENAME_POS + strlen( $entry->get_name() );
					fseek( $out_stream, $pos_extra );
					fwrite( $out_stream, $this->get_extra_fields_contents( $entry, true ) );

				} else {

					$pos_gpbf = $entry->get_local_header_offset() + 6;
					$entry->enable_data_descriptor();
					fseek( $out_stream, $pos_gpbf );
					fwrite(
						$out_stream,
						pack(
							'v',
							$entry->get_general_purpose_bit_flags()
						)
					);
				}

				$compressed_size   = ZipConstants::ZIP64_MAGIC;
				$uncompressed_size = ZipConstants::ZIP64_MAGIC;
			}

			$pos_checksum = $entry->get_local_header_offset() + 14;

			/**
			 * WinZip aes extra field.
			 *
			 * @var WinZipAesExtraField|null $win_zip_aes_extra
			 */
			$win_zip_aes_extra = $entry->get_local_extra_field( WinZipAesExtraField::HEADER_ID );

			if ( null !== $win_zip_aes_extra && $win_zip_aes_extra->is_v2() ) {
				$checksum = 0;
			}

			fseek( $out_stream, $pos_checksum );
			fwrite(
				$out_stream,
				pack(
					'VVV',
					$checksum,
					$compressed_size,
					$uncompressed_size
				)
			);
			fseek( $out_stream, 0, SEEK_END );
		}
	}

	/**
	 * Write and count checksum.
	 *
	 * @param resource $in_stream Input stream.
	 * @param resource $out_stream Output stream.
	 * @param int      $size Output stream.
	 * @return int
	 */
	private function write_and_count_checksum( $in_stream, $out_stream, int $size ): int {

		$context_hash = hash_init( 'crc32b' );
		$offset       = 0;

		while ( $offset < $size ) {
			$read   = min( self::CHUNK_SIZE, $size - $offset );
			$buffer = fread( $in_stream, $read );
			fwrite( $out_stream, $buffer );
			hash_update( $context_hash, $buffer );
			$offset += $read;
		}

		return (int) hexdec( hash_final( $context_hash ) );
	}

	/**
	 * Append compression filter.
	 *
	 * @param resource $out_stream Output steam.
	 * @param ZipEntry $entry Entry.
	 *
	 * @return resource|null
	 * @throws Exception Throws Exception.
	 */
	protected function append_compression_filter( $out_stream, ZipEntry $entry ) {

		$context_compress = null;

		$compress_method = $entry->get_compression_method();

		if ( ZipCompressionMethod::DEFLATED === $compress_method ) {
			$context_compress = stream_filter_append(
				$out_stream,
				'zlib.deflate',
				STREAM_FILTER_WRITE,
				array( 'level' => $entry->get_compression_level() )
			);
			if ( ! $context_compress ) {
				throw new Exception( 'Could not append filter "zlib.deflate" to out stream' );
			}
		} elseif ( ZipCompressionMethod::BZIP2 === $compress_method ) {

			$context_compress = stream_filter_append(
				$out_stream,
				'bzip2.compress',
				STREAM_FILTER_WRITE,
				array(
					'blocks' => $entry->get_compression_level(),
					'work'   => 0,
				)
			);
			if ( ! $context_compress ) {
				throw new Exception( 'Could not append filter "bzip2.compress" to out stream' );
			}
		} else {
			if ( ZipCompressionMethod::STORED !== $compress_method ) {
				throw new Exception(
					sprintf(
						'%s (compression method %d (%s) is not supported)',
						$entry->get_name(),
						$entry->get_compression_method(),
						ZipCompressionMethod::get_compression_method_name( $entry->get_compression_method() )
					)
				);
			}
		}

		return $context_compress;
	}

	/**
	 * Write data descriptor.
	 *
	 * @param resource $out_stream Output stream.
	 * @param ZipEntry $entry Zip entry.
	 * @return void
	 */
	protected function write_data_descriptor( $out_stream, ZipEntry $entry ) {

		$crc = $entry->get_crc();

		/**
		 * WinZip aes extra field.
		 *
		 * @var WinZipAesExtraField|null $win_zip_aes_extra
		 */
		$win_zip_aes_extra = $entry->get_local_extra_field( WinZipAesExtraField::HEADER_ID );

		if ( null !== $win_zip_aes_extra && $win_zip_aes_extra->is_v2() ) {
			$crc = 0;
		}

		fwrite(
			$out_stream,
			pack(
				'VV',
				ZipConstants::DATA_DESCRIPTOR,
				$crc
			)
		);

		if ( $entry->is_zip64_extensions_required() || $entry->get_local_extra_fields()->has( Zip64ExtraField::HEADER_ID ) ) {
			$dd = pack(
				'PP',
				$entry->get_compressed_size(),
				$entry->get_uncompressed_size()
			);
		} else {
			$dd = pack(
				'VV',
				$entry->get_compressed_size(),
				$entry->get_uncompressed_size()
			);
		}

		fwrite( $out_stream, $dd );
	}

	/**
	 * Write central directory block.
	 *
	 * @param resource $out_stream Output stream.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	protected function write_central_directory_block( $out_stream ) {
		foreach ( $this->zip_container->get_entries() as $output_entry ) {
			$this->write_central_directory_header( $out_stream, $output_entry );
		}
	}

	/**
	 * Writes a Central File Header record.
	 *
	 * @param resource $out_stream Output stream.
	 * @param ZipEntry $entry Zip entry.
	 * @return void
	 * @throws Exception Throws Exception.
	 */
	protected function write_central_directory_header( $out_stream, ZipEntry $entry ) {

		$compressed_size     = $entry->get_compressed_size();
		$uncompressed_size   = $entry->get_uncompressed_size();
		$local_header_offset = $entry->get_local_header_offset();

		$entry->get_cd_extra_fields()->remove( Zip64ExtraField::HEADER_ID );

		if ( $local_header_offset > ZipConstants::ZIP64_MAGIC || $compressed_size > ZipConstants::ZIP64_MAGIC || $uncompressed_size > ZipConstants::ZIP64_MAGIC ) {

			$zip64_extra_field = new Zip64ExtraField();

			if ( $uncompressed_size >= ZipConstants::ZIP64_MAGIC ) {
				$zip64_extra_field->set_uncompressed_size( $uncompressed_size );
				$uncompressed_size = ZipConstants::ZIP64_MAGIC;
			}

			if ( $compressed_size >= ZipConstants::ZIP64_MAGIC ) {
				$zip64_extra_field->set_compressed_size( $compressed_size );
				$uncompressed_size = ZipConstants::ZIP64_MAGIC;
			}

			if ( $local_header_offset >= ZipConstants::ZIP64_MAGIC ) {
				$zip64_extra_field->set_local_header_offset( $local_header_offset );
				$local_header_offset = ZipConstants::ZIP64_MAGIC;
			}

			$entry->get_cd_extra_fields()->add( $zip64_extra_field );
		}

		$extra        = $this->get_extra_fields_contents( $entry, false );
		$extra_length = strlen( $extra );
		$name         = $entry->get_name();
		$comment      = $entry->get_comment();
		$dos_charset  = $entry->get_charset();

		if ( null !== $dos_charset && ! $entry->is_utf8_flag() ) {
			$name = DosCodePage::from_utf8( $name, $dos_charset );
			if ( $comment ) {
				$comment = DosCodePage::from_utf8( $comment, $dos_charset );
			}
		}

		$comment_length = strlen( $comment );

		$compression_method = $entry->get_compression_method();
		$crc                = $entry->get_crc();

		/**
		 * WinZip aes extra field.
		 *
		 * @var WinZipAesExtraField|null $win_zip_aes_extra
		 */
		$win_zip_aes_extra = $entry->get_local_extra_field( WinZipAesExtraField::HEADER_ID );

		if ( null !== $win_zip_aes_extra ) {
			if ( $win_zip_aes_extra->is_v2() ) {
				$crc = 0;
			}
			$compression_method = ZipCompressionMethod::WINZIP_AES;
		}

		fwrite(
			$out_stream,
			pack(
				'VvvvvVVVVvvvvvVV',
				ZipConstants::CENTRAL_FILE_HEADER,
				( $entry->get_created_os() << 8 ) | $entry->get_software_version(),
				( $entry->get_extracted_os() << 8 ) | $entry->get_extract_version(),
				$entry->get_general_purpose_bit_flags(),
				$compression_method,
				$entry->get_dos_time(),
				$crc,
				$compressed_size,
				$uncompressed_size,
				strlen( $name ),
				$extra_length,
				$comment_length,
				0,
				$entry->get_internal_attributes(),
				$entry->get_external_attributes(),
				$local_header_offset
			)
		);

		fwrite( $out_stream, $name );
		if ( $extra_length > 0 ) {
			fwrite( $out_stream, $extra );
		}

		if ( $comment_length > 0 ) {
			fwrite( $out_stream, $comment );
		}
	}

	/**
	 * Write end of central directory block.
	 *
	 * @param resource $out_stream Output stream.
	 * @param int      $central_directory_offset Central directory offset.
	 * @param int      $central_directory_size Central directory size.
	 */
	protected function write_end_of_central_directory_block( $out_stream, int $central_directory_offset, int $central_directory_size ) {

		$cd_entries_count = $this->zip_container->count();

		$cd_entries_zip64 = $cd_entries_count > 0xFFFF;
		$cd_size_zip64    = $central_directory_size > ZipConstants::ZIP64_MAGIC;
		$cd_offset_zip64  = $central_directory_offset > ZipConstants::ZIP64_MAGIC;

		$zip64_required = $cd_entries_zip64 || $cd_size_zip64 || $cd_offset_zip64;

		if ( $zip64_required ) {

			$zip64_end_of_central_directory_offset = ftell( $out_stream );

			list( $software_version, $version_needed_to_extract ) = array_reduce(
				$this->zip_container->get_entries(),
				static function ( array $carry, ZipEntry $entry ) {
					$carry[0] = max( $carry[0], $entry->get_software_version() & 0xFF );
					$carry[1] = max( $carry[1], $entry->get_extract_version() & 0xFF );

					return $carry;
				},
				array( ZipVersion::V10_DEFAULT_MIN, ZipVersion::V45_ZIP64_EXT )
			);

			$created_os           = ZipPlatform::OS_DOS;
			$extracted_os         = ZipPlatform::OS_DOS;
			$version_made_by      = ( $created_os << 8 ) | max( $software_version, ZipVersion::V45_ZIP64_EXT );
			$version_extracted_by = ( $extracted_os << 8 ) | max( $version_needed_to_extract, ZipVersion::V45_ZIP64_EXT );

			fwrite(
				$out_stream,
				pack(
					'VPvvVVPPPPVVPV',
					ZipConstants::ZIP64_END_CD,
					ZipConstants::ZIP64_END_OF_CD_LEN - 12,
					$version_made_by & 0xFFFF,
					$version_extracted_by & 0xFFFF,
					0,
					0,
					$cd_entries_count,
					$cd_entries_count,
					$central_directory_size,
					$central_directory_offset,
					ZipConstants::ZIP64_END_CD_LOC,
					0,
					$zip64_end_of_central_directory_offset,
					1
				)
			);
		}

		$comment        = $this->zip_container->get_archive_comment();
		$comment_length = null !== $comment ? strlen( $comment ) : 0;

		fwrite(
			$out_stream,
			pack(
				'VvvvvVVv',
				ZipConstants::END_CD,
				0,
				0,
				$cd_entries_zip64 ? 0xFFFF : $cd_entries_count,
				$cd_entries_zip64 ? 0xFFFF : $cd_entries_count,
				$cd_size_zip64 ? ZipConstants::ZIP64_MAGIC : $central_directory_size,
				$cd_offset_zip64 ? ZipConstants::ZIP64_MAGIC : $central_directory_offset,
				$comment_length
			)
		);

		if ( null !== $comment && $comment_length > 0 ) {
			fwrite( $out_stream, $comment );
		}
	}
}
