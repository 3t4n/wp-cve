<?php


/**
 * GD を使った Image Editor を継承した class
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




class StillBE_WP_Image_Editor_GD extends WP_Image_Editor_GD {

	use StillBE_Image_Editor_Common_Variables,
	    StillBE_Image_Editor_Common_Overwrite,
	    StillBE_Image_Editor_Common_Append;

	const IMAGE_EDITOR_LIBRARY = 'gd';


	// Get the Number of Colors for PNG when Loading Image
	public function load() {

		$loaded = parent::load();

		if( ! defined( 'STILLBE_IQ_ENABLE_INDEX_COLOR' ) ) {
			define( 'STILLBE_IQ_ENABLE_INDEX_COLOR', false );
		}

		if( is_wp_error( $loaded ) ) {
			return $loaded;
		}

		if( 'image/png' === $this->mime_type ) {
			$this->color_num = $this->get_colors();
		}

		return $loaded;

	}


	// _save メソッドで PNG の圧縮品質を指定できないので上書きする
	// ついでにインターレースを有効にする
	protected function _save( $image, $filename = null, $mime_type = null ) {

		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );
		if( ! $filename ) {
			$filename = $this->generate_filename( null, null, $extension );
		}

		// Enable interlace
		if( function_exists( 'imageinterlace' ) ) {
			if( 'image/jpeg' === $mime_type && apply_filters( 'stillbe_image_quality_control_enable_interlace_jpeg', STILLBE_IQ_ENABLE_INTERLACE_JPEG ) ) {
				imageinterlace( $image, true );
			}
			if( 'image/png' === $mime_type && apply_filters( 'stillbe_image_quality_control_enable_interlace_png', STILLBE_IQ_ENABLE_INTERLACE_PNG ) ) {
				imageinterlace( $image, true );
			}
		}

		if( 'image/webp' === $mime_type ) {

			// WebP
			$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $this->get_quality(), 'webp' );

			if( ! $quality ) {
				$quality = $this->get_quality_from_size( 'default', 'image/webp' );
			}

			if( ! $this->make_image( $filename, 'imagewebp', array( $image, $filename, $quality ) ) ) {
				return new WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}

		} elseif( 'image/png' === $mime_type ) {

			// PNG
			$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $this->get_quality(), 'png' );

			if( ! $quality ) {
				$quality = $this->get_quality_from_size( 'default', 'image/png' );
			}

			// Convert from full colors to index colors, like original PNG.
			if( function_exists( 'imageistruecolor' ) && ! imageistruecolor( $image ) ) {
				imagetruecolortopalette( $image, false, imagecolorstotal( $image ) );
			}

			// When the Original Image is Index Color, the Resized Image is Converted to 256 Index Color.
			$is_conv_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color', STILLBE_IQ_ENABLE_INDEX_COLOR );

			// Force 256 Indexed Colors when Generating Resized Images.
			$is_force_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color_force', STILLBE_IQ_ENABLE_INDEX_COLOR_FORCE );

			// Convert Index Color
			if( $is_force_index || ( ! empty( $this->color_num ) && 256 >= $this->color_num && $is_conv_index ) ) {

				// Number of Used Colors
				$colors = min( 256, $this->get_colors( $image ) );
				$colors = 1 > $colors ? 256 : $colors;

				// Copy Image before Converting
				$_image = imagecreatetruecolor( $this->size['width'], $this->size['height'] );
				imagecopy($_image, $image, 0, 0, 0, 0, $this->size['width'], $this->size['height'] );

				// Convert to Index Color
				imagetruecolortopalette( $image, false, $colors );

				// Color Match
				imagecolormatch( $_image, $image );

				// Destory Image
				imagedestroy( $_image );

			}

			if( ! $this->make_image( $filename, 'imagepng', array( $image, $filename, $quality ) ) ) {
				return new WP_Error( 'image_save_error', __( 'Image Editor Save Failed' ) );
			}

		} else{

			// Others
			return parent::_save( $image, $filename, $mime_type );

		}

		// Set correct file permissions.
		$stat  = stat( dirname( $filename ) );
		$perms = $stat['mode'] & 0000666; // Same permissions as parent folder, strip off the executable bits.
		chmod( $filename, $perms );

		return array(
			'path'      => $filename,
			'file'      => wp_basename( apply_filters( 'image_make_intermediate_size', $filename ) ),
			'width'     => $this->size['width'],
			'height'    => $this->size['height'],
			'mime-type' => $mime_type,
			'sb-iqc'    => array(
				'quality' => empty( $quality ) ? $this->get_quality() : $quality,
			),
		);

	}


	// PNG の圧縮レベルを設定する
	public function stream( $mime_type = null ) {

		// Enable interlace
		if( function_exists( 'imageinterlace' ) && apply_filters( 'stillbe_image_quality_control_enable_interlace', STILLBE_IQ_ENABLE_INTERLACE ) ) {
			imageinterlace( $this->image, true );
		}

		if( 'image/png' === $mime_type ) {

			$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $this->get_quality(), 'png' );

			if( ! $quality ) {
				$quality = $this->get_quality_from_size( 'default', 'image/png' );
			}

			list( $filename, $extension, $mime_type ) = $this->get_output_format( null, $mime_type );

			// When the Original Image is Index Color, the Resized Image is Converted to 256 Index Color.
			$is_conv_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color', STILLBE_IQ_ENABLE_INDEX_COLOR );

			// Force 256 Indexed Colors when Generating Resized Images.
			$is_force_index = apply_filters( 'stillbe_image_quality_control_enable_png_index_color_force', STILLBE_IQ_ENABLE_INDEX_COLOR_FORCE );

			// Convert Index Color
			if( $is_force_index || ( ! empty( $this->color_num ) && 256 >= $this->color_num && $is_conv_index ) ) {

				// Number of Used Colors
				$colors = min( 256, $this->get_colors( $this->image ) );
				$colors = 1 > $colors ? 256 : $colors;

				// Copy Image before Converting
				$_image = imagecreatetruecolor( $this->size['width'], $this->size['height'] );
				imagecopy($_image, $this->image, 0, 0, 0, 0, $this->size['width'], $this->size['height'] );

				// Convert to Index Color
				imagetruecolortopalette( $this->image, false, $colors );

				// Color Match
				imagecolormatch( $_image, $this->image );

				// Destory Image
				imagedestroy( $_image );

			}

			header( 'Content-Type: image/png' );

			return imagepng( $this->image, null, $quality );

		}

		if( version_compare( $GLOBALS['wp_version'], '5.8', '<' ) && 'image/webp' === $mime_type ) {

			if( function_exists( 'imagewebp' ) ) {

				$quality = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $this->get_quality(), 'webp' );

				if( ! $quality ) {
					$quality = $this->get_quality_from_size( 'default', 'image/webp' );
				}

				header( 'Content-Type: image/webp' );

				return imagewebp( $this->image, null, $quality );

			}

		}

		return parent::stream( $mime_type );

	}


	// Overwrite 'supports_mime_type' for up to WP 5.7
	public static function supports_mime_type( $mime_type ) {

		if( 'image/webp' === $mime_type ) {
			if( apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY )
			      && stillbe_iqc_is_enabled_cwebp() ) {
				return true;
			}
			$image_types = imagetypes();
			return ( $image_types & IMG_WEBP ) != 0;
		}

		return parent::supports_mime_type( $mime_type );

	}


	// Make WebP Function with GD Library
	protected function _make_webp_embed_library( $filename = null, $size_data = array() ) {

		// Check Filename
		if( empty( $filename ) ) {
			return new WP_Error( 'error_webp_filename', __( 'No WebP filename has be passed' ) );
		}

		// Store Original Size
		$orig_size = $this->size;

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
		$resized = $this->_resize( $size_data['width'], $size_data['height'], $size_data['crop'] );

		// Save
		if( is_wp_error( $resized ) ) {
			$this->size = $orig_size;
			$this->set_quality( $current_quality );
			// WP 5.8 対応で追加した hook を削除する (@since 0.8.0)
			remove_filter( 'wp_editor_set_quality', array( $this, '_set_quality_hook' ), 1 );
			return $resized;
		} else {
			$saved = $this->_save( $resized, $filename, 'image/webp' );
			$this->output_mime_type = null;   // @since 0.10.9
			imagedestroy( $resized );
		}

		if( is_wp_error( $saved ) ) {
			return $saved;
		}

		$file_size = @filesize( $saved['path'] );
		$_quality  = $this->get_quality();

		$this->size = $orig_size;
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

		imagepalettetotruecolor( $this->image );

	}


	// Get Number of Used Colors
	public function get_colors( $image = null ) {

		if( empty( $image ) ) {
			$image = $this->image;
		}

		return (int) imagecolorstotal( $image );

	}


}





// END of the File



