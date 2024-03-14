<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW;

require_once \ABSPATH . \WPINC . '/class-wp-image-editor-gd.php';

/**
 * WordPress Image Editor Class for Image Manipulation through GD ( Supports AVIF ).
 *
 * @since 3.5.0
 *
 * @see WP_Image_Editor
 */
class AVIFGDEditor extends \WP_Image_Editor_GD {

	/**
	 * Checks to see if editor supports the mime-type specified.
	 *
	 * @since 3.5.0
	 *
	 * @param string $mime_type
	 * @return bool
	 */
	public static function supports_mime_type( $mime_type ) {
		$image_types = imagetypes();
		switch ( $mime_type ) {
			case 'image/jpeg':
				return ( $image_types & IMG_JPG ) != 0;
			case 'image/png':
				return ( $image_types & IMG_PNG ) != 0;
			case 'image/gif':
				return ( $image_types & IMG_GIF ) != 0;
			case 'image/webp':
				return ( $image_types & IMG_WEBP ) != 0;
			case 'image/avif':
				return ( defined( 'IMG_AVIF' ) && ( ( $image_types & IMG_AVIF ) != 0 ) );
		}

		return false;
	}

	/**
	 * Returns stream of current image.
	 *
	 * @since 3.5.0
	 *
	 * @param string $mime_type The mime type of the image.
	 * @return bool True on success, false on failure.
	 */
	public function stream( $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( null, $mime_type );

		switch ( $mime_type ) {
			case 'image/png':
				header( 'Content-Type: image/png' );
				return imagepng( $this->image );
			case 'image/gif':
				header( 'Content-Type: image/gif' );
				return imagegif( $this->image );
			case 'image/webp':
				if ( function_exists( 'imagewebp' ) ) {
					header( 'Content-Type: image/webp' );
					return imagewebp( $this->image, null, $this->get_quality() );
				}
				// Fall back to the default if webp isn't supported.
			case 'image/avif':
				if ( function_exists( 'imageavif' ) ) {
					header( 'Content-Type: image/avif' );
					return imageavif( $this->image, null, $this->get_quality() );
				}
			default:
				header( 'Content-Type: image/jpeg' );
				return imagejpeg( $this->image, null, $this->get_quality() );
		}
	}

	/**
	 * @since 3.5.0
	 * @since 6.0.0 The `$filesize` value was added to the returned array.
	 *
	 * @param resource|\GdImage $image
	 * @param string|null       $filename
	 * @param string|null       $mime_type
	 * @return array|\WP_Error {
	 *     Array on success or \WP_Error if the file failed to save.
	 *
	 *     @type string $path      Path to the image file.
	 *     @type string $file      Name of the image file.
	 *     @type int    $width     Image width.
	 *     @type int    $height    Image height.
	 *     @type string $mime-type The mime type of the image.
	 *     @type int    $filesize  File size of the image.
	 * }
	 */
	protected function _save( $image, $filename = null, $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );

		if ( ! $filename ) {
			$filename = $this->generate_filename( null, null, $extension );
		}

		if ( 'image/gif' === $mime_type ) {
			if ( ! $this->make_image( $filename, 'imagegif', array( $image, $filename ) ) ) {
				return new \WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}
		} elseif ( 'image/png' === $mime_type ) {
			// Convert from full colors to index colors, like original PNG.
			if ( function_exists( 'imageistruecolor' ) && ! imageistruecolor( $image ) ) {
				imagetruecolortopalette( $image, false, imagecolorstotal( $image ) );
			}

			if ( ! $this->make_image( $filename, 'imagepng', array( $image, $filename ) ) ) {
				return new \WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}
		} elseif ( 'image/jpeg' === $mime_type ) {
			if ( ! $this->make_image( $filename, 'imagejpeg', array( $image, $filename, $this->get_quality() ) ) ) {
				return new \WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}
		} elseif ( 'image/webp' == $mime_type ) {
			if ( ! function_exists( 'imagewebp' ) || ! $this->make_image( $filename, 'imagewebp', array( $image, $filename, $this->get_quality() ) ) ) {
				return new \WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}
		} elseif ( 'image/avif' === $mime_type ) {
			$settings = AvifSupport::get_settings();
			if ( ! $this->make_image( $filename, 'imageavif', array( $image, $filename, $settings['quality'], $settings['speed'] ) ) ) {
				return new \WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}
		} else {
			return new \WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
		}

		// Set correct file permissions.
		$stat  = stat( dirname( $filename ) );
		$perms = $stat['mode'] & 0000666; // Same permissions as parent folder, strip off the executable bits.
		chmod( $filename, $perms );

		return array(
			'path'      => $filename,
			/**
			 * Filters the name of the saved image file.
			 *
			 * @since 2.6.0
			 *
			 * @param string $filename Name of the file.
			 */
			'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', $filename ) ),
			'width'     => $this->size['width'],
			'height'    => $this->size['height'],
			'mime-type' => $mime_type,
			'filesize'  => wp_filesize( $filename ),
		);
	}

}
