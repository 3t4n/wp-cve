<?php


/**
 * Imagick を使った Image Editor を継承した class
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




class StillBE_WP_Image_Editor_Imagick extends WP_Image_Editor_Imagick {

	use StillBE_Image_Editor_Common_Variables,
	    StillBE_Image_Editor_Common_Overwrite,
	    StillBE_Image_Editor_Common_Append;

	const IMAGE_EDITOR_LIBRARY = 'imagick';


	// Get the Number of Colors for PNG when Loading Image
	public function load() {

		$loaded = parent::load();

		if( ! defined( 'STILLBE_IQ_ENABLE_INDEX_COLOR' ) ) {
			define( 'STILLBE_IQ_ENABLE_INDEX_COLOR', true );
		}

		if( is_wp_error( $loaded ) ) {
			return $loaded;
		}

		if( 0 !== strpos( $this->mime_type, 'image/' ) ) {
			$_mime_type = wp_get_image_mime( $this->file );
			if( 0 === strpos( $_mime_type, 'image/' ) ) {
				$this->mime_type = $_mime_type;
			}
		}

		if( 'image/png' === $this->mime_type ) {
			$this->color_num = $this->get_colors();
		}

		return $loaded;

	}


	protected function _save( $image, $filename = null, $mime_type = null ) {

		// Set PNG Compression Level
		if( 'image/png' === $this->mime_type ) {

			$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $this->get_quality(), 'png' );

			if( ! $quality ) {
				$quality = $this->get_quality_from_size( 'default', 'image/png' );
			}

			$this->image->setOption( 'png:compression-level', (string) $quality );

			// When the Original Image is Index Color, the Resized Image is Converted to 256 Index Color.
			$is_conv_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color', STILLBE_IQ_ENABLE_INDEX_COLOR );

			// Force 256 Indexed Colors when Generating Resized Images.
			$is_force_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color_force', STILLBE_IQ_ENABLE_INDEX_COLOR_FORCE );

			// Convert Index Color
			if( $is_force_index || ( ! empty( $this->color_num ) && 256 >= $this->color_num && $is_conv_index ) ) {

				// Number of Used Colors
				$colors = min( 256, $this->get_colors() );
				$colors = 1 > $colors ? 256 : $colors;

				// Convert to Index Color
				$this->image->quantizeImage( $colors, Imagick::COLORSPACE_SRGB, 0, false, false );
				$this->image->setImageDepth( 8 );

			}

		}

		// Set Interlace
		if( method_exists( $this->image, 'setInterlaceScheme' ) ) {

			if( 'image/jpeg' === $this->mime_type && apply_filters( 'stillbe_image_quality_control_enable_interlace_jpeg', STILLBE_IQ_ENABLE_INTERLACE_JPEG ) ) {
				$this->image->setInterlaceScheme( Imagick::INTERLACE_PLANE );
			}

			if( 'image/png' === $this->mime_type && apply_filters( 'stillbe_image_quality_control_enable_interlace_png', STILLBE_IQ_ENABLE_INTERLACE_PNG ) ) {
				$this->image->setInterlaceScheme( Imagick::INTERLACE_PLANE );
			}

		}

		// Strip EXIF Data
		if( apply_filters( 'stillbe_image_quality_control_enable_strip_exif', STILLBE_IQ_ENABLE_STRIP_EXIF ) ) {

			// Get the ICC Profile
			$profiles = $this->image->getImageProfiles( 'icc', true );

			// Strip EXIF & Comments
			$this->image->stripImage();

			// Restore the ICC Profile
			if( isset( $profiles['icc'] ) ) {
				$this->image->profileImage( 'icc', $profiles['icc'] );
			}

		}

		// Call the Parent Class Method
		$result = parent::_save( $image, $filename, $mime_type );
		if( is_wp_error( $result ) ) {
			return $result;
		}

		$result['sb-iqc'] = array(
			'quality' => empty( $quality ) ? $this->get_quality() : $quality,
		);

		return $result;

	}


	// PNG の圧縮レベルを設定する
	public function stream( $mime_type = null ) {

		if( 'image/png' === $mime_type ) {

			$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $this->get_quality(), 'png' );

			if( ! $quality ) {
				$quality = $this->get_quality_from_size( 'default', 'image/png' );
			}

			$this->image->setOption( 'png:compression-level', (string) $quality );

			// When the Original Image is Index Color, the Resized Image is Converted to 256 Index Color.
			$is_conv_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color', STILLBE_IQ_ENABLE_INDEX_COLOR );

			// Force 256 Indexed Colors when Generating Resized Images.
			$is_force_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color_force', STILLBE_IQ_ENABLE_INDEX_COLOR_FORCE );

			// Convert Index Color
			if( $is_force_index || ( ! empty( $this->color_num ) && 256 >= $this->color_num && $is_conv_index ) ) {

				// Number of Used Colors
				$colors = min( 256, $this->get_colors() );
				$colors = 1 > $colors ? 256 : $colors;

				// Convert to Index Color
				$this->image->quantizeImage( $colors, Imagick::COLORSPACE_SRGB, 0, false, false );
				$this->image->setImageDepth( 8 );

			}

		}

		// Set Interlace
		if( method_exists( $this->image, 'setInterlaceScheme' ) && apply_filters( 'stillbe_image_quality_control_enable_interlace', STILLBE_IQ_ENABLE_INTERLACE ) ) {
			$this->image->setInterlaceScheme( Imagick::INTERLACE_PLANE );
		}

		return parent::stream( $mime_type );

	}


	// Overwrite 'supports_mime_type' for using 'cwebp'
	public static function supports_mime_type( $mime_type ) {

		if( 'image/webp' === $mime_type ) {
			if( apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY )
			      && stillbe_iqc_is_enabled_cwebp() ) {
				return true;
			}
		}

		return parent::supports_mime_type( $mime_type );

	}


	// Make WebP Function with Imagick Library
	protected function _make_webp_embed_library( $filename = null, $size_data = array() ) {

		// Check Filename
		if( empty( $filename ) ) {
			return new WP_Error( 'error_webp_filename', __( 'No WebP filename has be passed' ) );
		}

		// Store Original Size & Image
		$orig_size  = $this->size;
		$orig_image = $this->image->getImage();

		// Max Size
		if( ! isset( $size_data['width'] ) ) {
			$size_data['width'] = $this->size['width'];
		}
		if ( ! isset( $size_data['height'] ) ) {
			$size_data['height'] = $this->size['height'];
		}
		if ( ! isset( $size_data['crop'] ) ) {
			$size_data['crop'] = false;
		}

		// Get the Quality
		$current_quality = $this->get_quality();
		$size            = empty( $size_data['size_name'] ) ? 'default' : $size_data['size_name'];
		$webp_quality    = $this->get_quality_from_size( $size, 'image/webp' );
		$this->set_quality( $webp_quality );

		// WP 5.8 対応で hook を追加する (@since 0.8.0)
		$this->_set_mk_size( $size );
		$this->_set_mk_mime( 'image/webp' );
		add_filter( 'wp_editor_set_quality', array( $this, '_set_quality_hook' ), 1, 2 );

		// Resize
		$resized = $this->resize( $size_data['width'], $size_data['height'], $size_data['crop'] );

		// Save
		if( is_wp_error( $resized ) ) {
			$this->size  = $orig_size;
			$this->image->clear();
			$this->image->destroy();
			$this->image = null;
			$this->image = $orig_image;
			$this->set_quality( $current_quality );
			// WP 5.8 対応で追加した hook を削除する (@since 0.8.0)
			remove_filter( 'wp_editor_set_quality', array( $this, '_set_quality_hook' ), 1 );
			return $resized;
		} else {
			$saved = $this->_save( $resized, $filename, 'image/webp' );
			$this->output_mime_type = null;   // @since 0.10.9
			$this->image->clear();
			$this->image->destroy();
			$this->image = null;
		}

		if( is_wp_error( $saved ) ) {
			return $saved;
		}

		$file_size   = @filesize( $saved['path'] );
		$_quality  = $this->get_quality();

		$this->size  = $orig_size;
		$this->image = $orig_image;
		$this->set_quality( $current_quality );

		// WP 5.8 対応で追加した hook を削除する (@since 0.8.0)
		remove_filter( 'wp_editor_set_quality', array( $this, '_set_quality_hook' ), 1 );

		return array(
			'path' => $saved['path'],
			'file' => $saved['file'],
			'size' => $file_size,
			'q'    => $_quality,
		);

	}


	// Convert to True Color
	public function conv2truecolor() {

		$this->image->setImageType( Imagick::IMGTYPE_TRUECOLOR );

	}


	// Get Number of Used Colors
	public function get_colors( $image = null ) {

		if( empty( $image ) ) {
			$image = $this->image;
		}

		return (int) $image->getImageColors();

	}


}





// END of the File



