<?php
/**
 * Class FilesUtil
 *
 * @file class-filesutil.php
 * @package pcloud_wp_backup
 */

namespace Pcloud\Classes\ZipFile\Util;

use finfo;

/**
 * Files util.
 *
 * @internal
 */
final class FilesUtil {

	/**
	 * Is empty directory.
	 *
	 * @param string $dir Directory.
	 */
	public static function is_empty_dir( string $dir ): bool {
		if ( ! is_readable( $dir ) ) {
			return false;
		}

		return count( scandir( $dir ) ) === 2;
	}

	/**
	 * Is bad compression file.
	 *
	 * @param string $file The file.
	 * @return bool
	 */
	public static function is_bad_compression_file( string $file ): bool {

		$bad_compress_file_ext = array(
			'dic',
			'dng',
			'f4v',
			'flipchart',
			'h264',
			'lrf',
			'mobi',
			'mts',
			'nef',
			'pspimage',
		);

		$ext = strtolower( pathinfo( $file, PATHINFO_EXTENSION ) );

		if ( in_array( $ext, $bad_compress_file_ext, true ) ) {
			return true;
		}

		$mime_type = self::get_mime_type_from_file( $file );

		return self::is_bad_compression_mime_type( $mime_type );
	}

	/**
	 * Is bad compression mime type.
	 *
	 * @param string $mime_type Mime Type.
	 * @return bool
	 */
	public static function is_bad_compression_mime_type( string $mime_type ): bool {

		static $bad_deflate_comp_mime_types = array(
			'application/epub+zip',
			'application/gzip',
			'application/vnd.debian.binary-package',
			'application/vnd.oasis.opendocument.graphics',
			'application/vnd.oasis.opendocument.presentation',
			'application/vnd.oasis.opendocument.text',
			'application/vnd.oasis.opendocument.text-master',
			'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
			'application/vnd.rn-realmedia',
			'application/x-7z-compressed',
			'application/x-arj',
			'application/x-bzip2',
			'application/x-hwp',
			'application/x-lzip',
			'application/x-lzma',
			'application/x-ms-reader',
			'application/x-rar',
			'application/x-rpm',
			'application/x-stuffit',
			'application/x-tar',
			'application/x-xz',
			'application/zip',
			'application/zlib',
			'audio/flac',
			'audio/mpeg',
			'audio/ogg',
			'audio/vnd.dolby.dd-raw',
			'audio/webm',
			'audio/x-ape',
			'audio/x-hx-aac-adts',
			'audio/x-m4a',
			'audio/x-m4a',
			'audio/x-wav',
			'image/gif',
			'image/heic',
			'image/jp2',
			'image/jpeg',
			'image/png',
			'image/vnd.djvu',
			'image/webp',
			'image/x-canon-cr2',
			'video/ogg',
			'video/webm',
			'video/x-matroska',
			'video/x-ms-asf',
			'x-epoc/x-sisx-app',
		);

		return in_array( $mime_type, $bad_deflate_comp_mime_types, true );
	}

	/**
	 * Get mime type from file.
	 *
	 * @param string $file File to check.
	 * @return string
	 */
	public static function get_mime_type_from_file( string $file ): string {

		if ( function_exists( 'mime_content_type' ) ) {
			return mime_content_type( $file );
		}

		return 'application/octet-stream';
	}

	/**
	 * Get mime type from string.
	 *
	 * @param string $contents Contents to check.
	 * @return string
	 */
	public static function get_mime_type_from_string( string $contents ): string {
		$finfo     = new finfo( FILEINFO_MIME );
		$mime_type = $finfo->buffer( $contents );

		if ( false === $mime_type ) {
			$mime_type = 'application/octet-stream';
		}

		return explode( ';', $mime_type )[0];
	}
}
