<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW\Utils\Img;

/**
 * Image Related Functions Utils.
 */
trait ImgUtilsTrait {

	/**
	 * Check if the imagick lib is enabled.
	 *
	 * @return boolean
	 */
	public static function is_imagick_enabled() {
		return ( extension_loaded( 'imagick' ) && class_exists( '\Imagick', false ) && class_exists( '\ImagickPixel', false ) );
	}

	/**
	 * Check if the gd lib is enabled.
	 *
	 * @return boolean
	 */
	public static function is_gd_enabled() {
		return ( extension_loaded( 'gd' ) && function_exists( 'gd_info' ) );
	}

	/**
	 * Convert Image URL to PATH.
	 *
	 * @param string $img_url
	 * @return string
	 */
	public static function convert_url_to_path( $img_url ) {
		$uploads  = wp_get_upload_dir();
		$img_path = str_replace( $uploads['baseurl'], $uploads['basedir'], $img_url );
		return $img_path;
	}

	/**
	 * Convert Image PATH to URL.
	 *
	 * @param string $img_path
	 * @return string
	 */
	public static function convert_path_to_url( $img_path ) {
		$uploads = wp_get_upload_dir();
		$img_url = str_replace( $uploads['basedir'], $uploads['baseurl'], $img_path );
		return $img_url;
	}

	/**
	 * Get Image Specs.
	 *
	 * @param string $image_path
	 * @return array|false
	 */
	public static function get_image_specs( $image_path ) {
		$img_details = self::get_imagesize( $image_path );
		if ( ! $img_details ) {
			return false;
		}

		$file_size = filesize( $image_path );
		return array(
			'width'       => $img_details[0],
			'height'      => $img_details[1],
			'mime'        => $img_details['mime'],
			'dimension'   => $img_details[0] . 'x' . $img_details[1],
			'ext'         => str_replace( 'image/', '', $img_details['mime'] ),
			'size'        => $file_size,
			'size_format' => size_format( $file_size ),
		);
	}

	/**
	 * Get Image Ext.
	 *
	 * @param string $img_path
	 * @return string|false
	 */
	public static function get_image_ext( $img_path ) {
		$img_details = self::get_imagesize( $img_path );
		if ( ! $img_details ) {
			return false;
		}
		return str_replace( 'image/', '', $img_details['mime'] );
	}

	/**
	 * Get Image Path - Filename - size - ext details.
	 *
	 * @param int    $attachment_id Attachment ID.
	 * @param string $img_size_name Get the details to specific size name.
	 * @return array|\WP_Error
	 */
	public static function get_image_file_details( $attachment_id, $img_size_name = 'original' ) {
		$img_details       = array();
		$uploads           = wp_get_upload_dir();
		$img_meta          = wp_get_attachment_metadata( $attachment_id );
		$img_relative_path = get_post_meta( $attachment_id, '_wp_attached_file', true );
		$img_file_name     = wp_basename( $img_relative_path );

		// Check if the image is scaled, get the original image.
		if ( ! empty( $img_meta['original_image'] ) && ( $img_file_name !== $img_meta['original_image'] ) ) {
			$original_img_filename      = $img_meta['original_image'];
			$original_img_relative_path = str_replace( $img_file_name, $img_meta['original_image'], $img_relative_path );
			$original_full_path         = trailingslashit( $uploads['basedir'] ) . $original_img_relative_path;
			// check if the original exists.
			if ( @file_exists( $original_full_path ) ) {
				$img_details['scaled_path'] = get_attached_file( $attachment_id );
				$img_relative_path          = $original_img_relative_path;
				$img_file_name              = $original_img_filename;
			}
		}

		$img_relative_subdirectory_path = str_replace( $img_file_name, '', $img_relative_path );
		if ( 'original' === $img_size_name ) {
			$img_full_path = trailingslashit( $uploads['basedir'] ) . $img_relative_path;
			$img_full_url  = trailingslashit( $uploads['baseurl'] ) . $img_relative_path;
			$filetype      = wp_check_filetype_and_ext( $img_full_path, $img_file_name, self::get_mimes() );

		} elseif ( 'original' !== $img_size_name && ! empty( $img_meta['sizes'] ) && ! empty( $img_meta['sizes'][ $img_size_name ] ) ) {
			$size_file_name    = $img_meta['sizes'][ $img_size_name ]['file'];
			$img_relative_path = str_replace( $img_file_name, $size_file_name, $img_relative_path );
			$img_file_name     = wp_basename( $img_relative_path );
			$img_full_path     = trailingslashit( $uploads['basedir'] ) . $img_relative_path;
			$img_full_url      = trailingslashit( $uploads['baseurl'] ) . $img_relative_path;
			$filetype          = wp_check_filetype_and_ext( $img_full_path, $img_file_name, self::get_mimes() );
		} else {
			return new \WP_Error(
				self::$plugin_info['name'] . '-attachment-subsize-not-found',
				sprintf( esc_html__( 'Image file sub-size: %s not found!' ), $img_size_name )
			);
		}

		if ( ! file_exists( $img_full_path ) ) {
			return new \WP_Error(
				self::$plugin_info['name'] . '-attachment-file-not-found',
				sprintf( esc_html__( 'Image file %s not found!' ), $img_file_name )
			);
		}
		if ( 'original' === $img_size_name ) {
			$img_details['width']  = $img_meta['width'];
			$img_details['height'] = $img_meta['height'];
		} else {
			$img_details['width']  = ( 'original' === $img_size_name ) ? $img_meta['width'] : $img_meta['sizes'][ $img_size_name ]['width'];
			$img_details['height'] = ( 'original' === $img_size_name ) ? $img_meta['height'] : $img_meta['sizes'][ $img_size_name ]['height'];
		}

		if ( 'original' !== $img_size_name ) {
			$img_details['width_ratio']  = number_format( floatval( $img_details['width'] / $img_meta['width'] ), 2 );
			$img_details['height_ratio'] = number_format( floatval( $img_details['height'] / $img_meta['height'] ), 2 );
			$img_details['width_ratio']  = ( $img_details['width_ratio'] < 0.10 ) ? 0.10 : $img_details['width_ratio'];
			$img_details['height_ratio'] = ( $img_details['height_ratio'] < 0.10 ) ? 0.10 : $img_details['height_ratio'];
		}

		$img_file_filetype = wp_check_filetype( $img_file_name );

		$img_details['id']                     = $attachment_id;
		$img_details['size_name']              = $img_size_name;
		$img_details['path']                   = $img_full_path;
		$img_details['url']                    = $img_full_url;
		$img_details['filename']               = $img_file_name;
		$img_details['relative_path']          = $img_relative_subdirectory_path;
		$img_details['full_path_without_name'] = trailingslashit( dirname( $img_full_path ) );
		$img_details['ext']                    = $filetype['ext'];
		$img_details['file_ext']               = $img_file_filetype['ext'];
		$img_details['mime_type']              = $filetype['type'];
		$img_details['file_mime_type']         = $img_file_filetype['type'];
		$img_details['specs']                  = self::get_image_specs( $img_full_path );
		return $img_details;
	}

	/**
	 * Get Image as string.
	 *
	 * @param string $img_path
	 * @return string
	 */
	private static function get_img_string( $img_path ) {
		return file_get_contents( $img_path );
	}

	/**
	 * Get GD Version.
	 *
	 * @return string
	 */
	public function get_gd_version() {
		$gd_info = gd_info();
		return $gd_info['GD Version'];
	}

	/**
	 * Get Imagick Version.
	 *
	 * @return string|false
	 */
	public function get_imagick_version() {
		try {
			$version = \Imagick::getVersion();
			if ( preg_match( '/((?:[0-9]+\.?)+)/', $version['versionString'], $matches ) ) {
				return $matches[0];
			}
			return $version['versionString'];
		} catch ( \Exception $e ) {

		}

		return false;
	}

	/**
	 * Reformed Get Image Size.
	 *
	 * @param string $img_path
	 * @return array|false
	 */
	public static function get_imagesize( $img_path ) {
		$img_size = wp_getimagesize( $img_path );
		// Legacy PHP Version, return false, fake it till manual.
		if ( empty( $img_size ) ) {
			$img_size = array(
				0      => 0,
				1      => 0,
				2      => 19,
				3      => 'width="0" height="0"',
				'mime' => 'image/avif',
			);
		}

		if ( is_array( $img_size ) && ( 0 !== $img_size[0] ) && ( 0 !== $img_size[1] ) ) {
			return $img_size;
		}

		// Failed, fallback to Imagick.
		if ( self::is_imagick_enabled() ) {
			try {
				$imgick      = new \Imagick( $img_path );
				$img_dim     = $imgick->getImageGeometry();
				$img_size[0] = $img_dim['width'];
				$img_size[1] = $img_dim['height'];

				$imgick->clear();
				return $img_size;
			} catch ( \Exception $e ) {
				// Do nothing for now.
			}
		}

		return $img_size;
	}

	/**
	 * Manual Handle AVIF Width and Height.
	 *
	 * @param string $img_path
	 * @return array|false
	 */
	private static function get_avif_dim_manual( $img_path ) {
		$binary_string = file_get_contents( $img_path );
		$ispe_pos      = strpos( $binary_string, 'ispe' );

		if ( false === $ispe_pos ) {
			// Corrupted Image.
			return false;
		}

		$dim_start_pos = $ispe_pos + 8;
		$dim_bin       = substr( $binary_string, $dim_start_pos, 8 );
		$width         = hexdec( bin2hex( substr( $dim_bin, 0, 4 ) ) );
		$height        = hexdec( bin2hex( substr( $dim_bin, 4, 8 ) ) );

		if ( $width && $height && is_numeric( $width ) && is_numeric( $height ) ) {
			return array(
				'width'  => absint( $width ),
				'height' => absint( $height ),
			);
		}

		return false;
	}

	/**
	 * Check if Image Type is supported.
	 *
	 * @param string $type
	 * @param string $lib_type [ gd - imagick ]
	 * @return bool
	 */
	public static function is_type_supported( $type, $lib_type = '' ) {
		$gd_check      = false;
		$imagick_check = false;

		// GD Check.
		if ( self::is_gd_enabled() && ( empty( $lib_type ) || 'gd' === $lib_type ) ) {
			$gd_info = gd_info();
			foreach ( $gd_info as $gd_key => $gd_value ) {
				if ( str_starts_with( strtolower( $gd_key ), strtolower( $type ) ) && $gd_value ) {
					return true;
				}
			}
		}

		if ( 'gd' === $lib_type ) {
			return $gd_check;
		}

		// Imagick Check.
		if ( self::is_imagick_enabled() && ( empty( $lib_type ) || 'imagick' === $lib_type ) ) {
			try {
				$imagick_check = (bool) @\Imagick::queryFormats( strtoupper( $type ) );
			} catch ( \Exception $e ) {
				$imagick_check = false;
			}
		}

		if ( 'imagick' === $lib_type ) {
			return $imagick_check;
		}

		return $gd_check || $imagick_check;
	}

	/**
	 * Get Mimies.
	 *
	 * @return array
	 */
	private static function get_mimes() {
		return array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'gif'          => 'image/gif',
			'png'          => 'image/png',
			'bmp'          => 'image/bmp',
			'tiff|tif'     => 'image/tiff',
			'webp'         => 'image/webp',
			'ico'          => 'image/x-icon',
			'heic'         => 'image/heic',
			'avif'         => 'image/avif',
		);
	}
}
