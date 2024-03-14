<?php

namespace GPLSCore\GPLS_PLUGIN_WGR;

use Exception;
use Grafika\Gd\Editor;
use Grafika\Gd\Helper\GifHelper;

/**
 * Gif Editor CLass.
 */
class GIF_Editor_Imagick extends \WP_Image_Editor_Imagick {

	/**
	 * Allowed resize filters.
	 *
	 * @var array
	 */
	protected $allowed_filters = array(
		'FILTER_POINT',
		'FILTER_BOX',
		'FILTER_TRIANGLE',
		'FILTER_HERMITE',
		'FILTER_HANNING',
		'FILTER_HAMMING',
		'FILTER_BLACKMAN',
		'FILTER_GAUSSIAN',
		'FILTER_QUADRATIC',
		'FILTER_CUBIC',
		'FILTER_CATROM',
		'FILTER_MITCHELL',
		'FILTER_LANCZOS',
		'FILTER_BESSEL',
		'FILTER_SINC',
	);

	/**
	 * Supports GIFs.
	 *
	 * @param string $mime_type Media Mime Type.
	 * @return bool
	 */
	public static function supports_mime_type( $mime_type ) {
		return ( 'image/gif' === $mime_type );
	}

	/**
	 * Pypass Test only for GIFs.
	 *
	 * @param array $args Arguments array.
	 * @return bool
	 */
	public static function test( $args = array() ) {
		if ( empty( $args ) ) {
			return false;
		}

		if ( ! empty( $args['mime_type'] ) && ( 'image/gif' === $args['mime_type'] ) ) {
			return true;
		}

		if ( empty( $args['path'] ) ) {
			return false;
		}

		$media_file_path = $args['path'];
		$media_mime_type = wp_get_image_mime( $media_file_path );

		if ( 'image/gif' !== $media_mime_type ) {
			return false;
		}

		return true;
	}

    /**
	 * Resizes current image.
	 *
	 * At minimum, either a height or width must be provided.
	 * If one of the two is set to null, the resize will
	 * maintain aspect ratio according to the provided dimension.
	 *
	 * @since 3.5.0
	 *
	 * @param int|null $max_w Image width.
	 * @param int|null $max_h Image height.
	 * @param bool     $crop
	 * @return true|\WP_Error
	 */
	public function resize( $max_w, $max_h, $crop = false ) {
		if ( ( $this->size['width'] == $max_w ) && ( $this->size['height'] == $max_h ) ) {
			return true;
		}

		$dims = image_resize_dimensions( $this->size['width'], $this->size['height'], $max_w, $max_h, $crop );
		if ( ! $dims ) {
			return new \WP_Error( 'error_getting_dimensions', __( 'Could not calculate resized image dimensions' ) );
		}

		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		self::set_imagick_time_limit();

		// Execute the resize.
		$thumb_result = $this->thumbnail_image( $dst_w, $dst_h );
		if ( is_wp_error( $thumb_result ) ) {
			return $thumb_result;
		}

		return $this->update_size( $dst_w, $dst_h );
	}

	/**
	 * Crops Image.
	 *
	 * @since 3.5.0
	 *
	 * @param int  $src_x   The start x position to crop from.
	 * @param int  $src_y   The start y position to crop from.
	 * @param int  $src_w   The width to crop.
	 * @param int  $src_h   The height to crop.
	 * @param int  $dst_w   Optional. The destination width.
	 * @param int  $dst_h   Optional. The destination height.
	 * @param bool $src_abs Optional. If the source crop points are absolute.
	 * @return true|\WP_Error
	 */
	public function crop( $src_x, $src_y, $src_w, $src_h, $dst_w = null, $dst_h = null, $src_abs = false ) {
        return false;
	}

	/**
	 * Rotates current image counter-clockwise by $angle.
	 *
	 * @since 3.5.0
	 *
	 * @param float $angle
	 * @return true|\WP_Error
	 */
	public function rotate( $angle ) {
		return false;
	}

	/**
	 * Flips current image.
	 *
	 * @since 3.5.0
	 *
	 * @param bool $horz Flip along Horizontal Axis
	 * @param bool $vert Flip along Vertical Axis
	 * @return true|\WP_Error
	 */
	public function flip( $horz, $vert ) {
        return false;
	}

	/**
	 * Create an image sub-size and return the image meta data value for it.
	 *
	 * @param array $size_data {
	 * @return array|\WP_Error
	 */
	public function make_subsize( $size_data ) {
        set_time_limit(0);
		if ( ! isset( $size_data['width'] ) && ! isset( $size_data['height'] ) ) {
			return new \WP_Error( 'image_subsize_create_error', __( 'Cannot resize the image. Both width and height are not set.' ) );
		}

		$orig_size  = $this->size;
		$orig_image = $this->image;

		if ( ! isset( $size_data['width'] ) ) {
			$size_data['width'] = null;
		}

		if ( ! isset( $size_data['height'] ) ) {
			$size_data['height'] = null;
		}

		if ( ! isset( $size_data['crop'] ) ) {
			$size_data['crop'] = false;
		}

		$resized = $this->resize( $size_data['width'], $size_data['height'], $size_data['crop'] );

		if ( is_wp_error( $resized ) ) {
			$saved = $resized;
		} else {
			$saved = $this->_save( $this->image );

			$this->image->clear();
			$this->image->destroy();
			$this->image = null;

			// Delete the subsize if its bigger than the original.
			if ( ! is_wp_error( $saved ) ) {
				$subsize_path  = $saved['path'];
				$subsize_size  = filesize( $subsize_path );
				$original_size = $orig_image->getImageLength();

				if ( $subsize_size > $original_size ) {
					unlink( $subsize_path );
					$saved = new \WP_Error(
						'gif-subsize-oversize',
						esc_html__( 'Subsize is bigger than original size. abort!' )
					);
				}
			}
		}

		$this->size  = $orig_size;
		$this->image = $orig_image;

		if ( ! is_wp_error( $saved ) ) {
			unset( $saved['path'] );
		}

		return $saved;
	}

	/**
	 * Efficiently resize the current image
	 *
	 * @param int    $dst_w       The destination width.
	 * @param int    $dst_h       The destination height.
	 * @param string $filter_name Optional. The Imagick filter to use when resizing. Default 'FILTER_TRIANGLE'.
	 * @param bool   $strip_meta  Optional. Strip all profiles, excluding color profiles, from the image. Default true.
	 * @return void|\WP_Error
	 */
	protected function thumbnail_image( $dst_w, $dst_h, $filter_name = 'FILTER_TRIANGLE', $strip_meta = true ) {

		if ( in_array( $filter_name, $this->allowed_filters, true ) && defined( 'Imagick::' . $filter_name ) ) {
			$filter = constant( 'Imagick::' . $filter_name );
		} else {
			$filter = defined( 'Imagick::FILTER_TRIANGLE' ) ? \Imagick::FILTER_TRIANGLE : false;
		}

		if ( apply_filters( 'image_strip_meta', $strip_meta ) ) {
			$this->strip_meta(); // Fail silently if not supported.
		}

		try {
			$image_coalesce = $this->image->coalesceImages();

			foreach ( $image_coalesce as $frame ) {

				if ( is_callable( array( $frame, 'sampleImage' ) ) ) {
					$resize_ratio  = ( $dst_w / $this->size['width'] ) * ( $dst_h / $this->size['height'] );
					$sample_factor = 5;

					if ( $resize_ratio < .111 && ( $dst_w * $sample_factor > 128 && $dst_h * $sample_factor > 128 ) ) {
						$frame->sampleImage( $dst_w * $sample_factor, $dst_h * $sample_factor );
					}
				}

				if ( is_callable( array( $frame, 'resizeImage' ) ) && $filter ) {
					$frame->setOption( 'filter:support', '2.0' );
					$frame->resizeImage( $dst_w, $dst_h, $filter, 1 );
				} else {
					$frame->scaleImage( $dst_w, $dst_h );
				}
			}

			$this->image = $image_coalesce->deconstructImages();


		} catch ( Exception $e ) {
			return new \WP_Error( 'image_resize_error', $e->getMessage() );
		}
	}

	/**
	 * @param \Imagick $image
	 * @param string   $filename
	 * @param string   $mime_type
	 * @return array|\WP_Error {
	 */
	protected function _save( $image, $filename = null, $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );

		if ( ! $filename ) {
			$filename = $this->generate_filename( null, null, $extension );
		}

		try {
			// Store initial format.
			$orig_format = $this->image->getImageFormat();

			$this->image->setImageFormat( strtoupper( $this->get_extension( $mime_type ) ) );
		} catch ( Exception $e ) {
			return new \WP_Error( 'image_save_error', $e->getMessage(), $filename );
		}

		$write_image_result = $this->write_image( $this->image, $filename );
		if ( is_wp_error( $write_image_result ) ) {
			return $write_image_result;
		}

		try {
			// Reset original format.
			$this->image->setImageFormat( $orig_format );
		} catch ( Exception $e ) {
			return new \WP_Error( 'image_save_error', $e->getMessage(), $filename );
		}

		// Set correct file permissions.
		$stat  = stat( dirname( $filename ) );
		$perms = $stat['mode'] & 0000666; // Same permissions as parent folder, strip off the executable bits.
		chmod( $filename, $perms );

		return array(
			'path'      => $filename,
			/** This filter is documented in wp-includes/class-wp-image-editor-gd.php */
			'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', $filename ) ),
			'width'     => $this->size['width'],
			'height'    => $this->size['height'],
			'mime-type' => $mime_type,
			'filesize'  => wp_filesize( $filename ),
		);
	}

	/**
	 * Writes an image to a file or stream.
	 *
	 * @since 5.6.0
	 *
	 * @param \Imagick $image
	 * @param string   $filename The destination filename or stream URL.
	 * @return true|\WP_Error
	 */
	private function write_image( $image, $filename ) {
		if ( wp_is_stream( $filename ) ) {
			if ( file_put_contents( $filename, $image->getImagesBlob() ) === false ) {
				return new \WP_Error(
					'image_save_error',
					sprintf(
						/* translators: %s: PHP function name. */
						__( '%s failed while writing image to stream.' ),
						'<code>file_put_contents()</code>'
					),
					$filename
				);
			} else {
				return true;
			}
		} else {
			$dirname = dirname( $filename );

			if ( ! wp_mkdir_p( $dirname ) ) {
				return new \WP_Error(
					'image_save_error',
					sprintf(
						/* translators: %s: Directory path. */
						__( 'Unable to create directory %s. Is its parent directory writable by the server?' ),
						esc_html( $dirname )
					)
				);
			}

			try {
				return $image->writeImages( $filename, true );
			} catch ( Exception $e ) {
				return new \WP_Error( 'image_save_error', $e->getMessage(), $filename );
			}
		}
	}

	/**
	 * Streams current image to browser.
	 *
	 * @since 3.5.0
	 *
	 * @param string $mime_type The mime type of the image.
	 * @return true|\WP_Error True on success, WP_Error object on failure.
	 */
	public function stream( $mime_type = null ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( null, $mime_type );

		try {
			// Temporarily change format for stream.
			$this->image->setImageFormat( strtoupper( $extension ) );

			// Output stream of image content.
			header( "Content-Type: $mime_type" );
			print $this->image->getImagesBlob();

			// Reset image to original format.
			$this->image->setImageFormat( $this->get_extension( $this->mime_type ) );
		} catch ( Exception $e ) {
			return new \WP_Error( 'image_stream_error', $e->getMessage() );
		}

		return true;
	}
}
