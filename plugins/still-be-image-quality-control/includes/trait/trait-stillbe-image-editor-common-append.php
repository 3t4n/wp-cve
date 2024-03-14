<?php


/**
 * Trait Used in a Class that extends WP_Image_Editor_GD/Imagick Class
 * 
 *  * Define the Methods to Append
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 追加する共通メソッドを定義
trait StillBE_Image_Editor_Common_Append {


	// サイズに対応した圧縮品質を取得する
	public function get_quality_from_size( $size, $mime_type = 'image/jpeg' ) {

		if( empty( $this->qualities ) ) {
			// Quality Setting Data
			$qualities = $this->_get_quality_array();
			$this->qualities = apply_filters( 'stillbe_image_quality_list', $qualities );
		}

		if( empty( $this->original_webp ) ) {
			// Original WebP Quality Setting
			$_defaults      = _stillbe_get_quality_level_array();
			$_original_webp = array(
				array(
					'lossy'    => $_defaults['original_webp'],
					'lossless' => 9,
				),
			);
			$this->original_webp = apply_filters( 'stillbe_image_quality_original_webp_settings', $_original_webp );
		}

		if( empty( $this->is_lossless_options ) ) {
			// Is Enabled Lossless Compression?
			$this->is_lossless_options = stillbe_iqc_is_extended() && apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY )
			                               && apply_filters( 'stillbe_image_quality_control_enable_webp_lossless_for_png_gif', STILLBE_IQ_ENABLE_WEBP_LOSSLESS );
		}

		$mime = explode( '/', $mime_type );
		$mime = end( $mime );

		if( 'original' !== strtolower( $size ) ) {
			$name    = $size. '_'. $mime;
			$defname = 'default_'. $mime;

			$quality = empty( $this->qualities[ $name ] ) ?
			             apply_filters( "stillbe_image_quality_default_{$mime}", $this->qualities[ $defname ] ) :
			             $this->qualities[ $name ];
			$quality = absint( apply_filters( "stillbe_image_quality_{$size}_{$mime}", $quality ) );

		} else {

			$original_webp = $this->original_webp;

			$width  = $this->size['width'];
			$height = $this->size['height'];

			$webp = array_shift( $original_webp );
			$_defaults = _stillbe_get_quality_level_array();

			while( $_webp = array_pop( $original_webp ) ) {

				if( ! isset( $_webp['width'] ) || ! isset( $_webp['height'] ) ) {
					continue;
				}

				if( ( $_webp['width'] && $width > $_webp['width'] ) ||
				      ( $_webp['height'] && $height > $_webp['height'] ) ) {
					break;
				}

				$webp = $_webp;

				if( ! is_array( $webp ) ) {
					$webp = array();
				}
				if( empty( $webp['lossy'] ) ) {
					$webp['lossy'] = $_defaults['original_webp'];
				}
				if( empty( $webp['lossless'] ) ) {
					$webp['lossless'] = 9;
				}

			}

			$is_lossless = $this->is_lossless_options && 'png' === $mime;

			$quality = $webp[( $is_lossless ? 'lossless' : 'lossy' )];
			$quality = absint( apply_filters( "stillbe_image_quality_original_{$mime}", $quality ) );

		}

		if( 'image/png' !== $mime_type && $quality > 100 ) {
			$quality = 100;
		}

		if( 'image/png' === $mime_type && $quality > 9 ){
			$quality = 9;
		}

		if( $quality < 1 ) {
			$quality = 1;
		}

		return $quality;

	}


	// Get Quality Settings
	protected function _get_quality_array() {

		return apply_filters(
			'stillbe_image_quality_default_list',
			_stillbe_get_quality_level_array()
		);

	}


	// 半角英数字と-_以外が含まれる場合はファイル名を置換する
	public function generate_safe_filename( $filename = '', $suffix = null, $original = false ) {

		if( apply_filters( 'stillbe_image_quality_control_convert_safename', true ) &&
		      preg_match( '%(.*?/)([^/]*?[^/](?=[^\/\.\-_a-zA-Z0-9])[^/]*?)(\.(?:jpe?g|png|gif)(?:\.webp)?)$%', $filename, $m ) ) {
			// $m[1]: dir path, $m[2]: unsafe file name, $m[3]: file extension
			$now  = date_i18n( 'YmdHis' );
			$hash = substr( md5( $m[2] ), 7, 8 );
			if( $original ) {
				return $m[1]. "{$now}-{$hash}". $m[3];
			}
			// $suffix will be appended to the destination filename, just before the extension.
			if( ! $suffix ) {
				$suffix = $this->get_suffix();
			}
			return $m[1]. "{$now}-{$hash}-{$suffix}". $m[3];
		}

		return $filename;

	}


	// Get the Mime-Type of Original Image
	public function get_original_mime() {
		return $this->mime_type;
	}


	// Set the Making Size to Private Var for 'wp_editor_set_quality' hook
	public function _set_mk_size( $size = '' ) {
		$this->mk_size = $size;
		return $this->mk_size;
	}


	// Set the Making Mime-Type to Private Var for 'wp_editor_set_quality' hook
	public function _set_mk_mime( $mime_type = '' ) {
		if( preg_match( '$image/(jpeg|png|webp)$', $mime_type ) ) {
			$this->mk_mime = $mime_type;
		} else {
			$this->mk_mime = '';
		}
		return $this->mk_mime;
	}


	// Set the Making Quality for 'wp_editor_set_quality' hook
	// This Option is Used by only generating Test Image
	public function _set_mk_quality( $quality = 0, $mime = 'jpeg' ) {
		$q = StillBE_Image_Quality_Ctrl_Setting::chk_num_type( $quality, $mime );
		$this->mk_q = empty( $q ) ? 0 : $q;
		return $this->mk_q;
	}


	// Function for 'wp_editor_set_quality' hook
	public function _set_quality_hook( $default_quality, $mime_type ) {

		if( ! method_exists( $this, 'get_default_quality' ) ) {
			return $default_quality;
		}

		// Only Test Image
		if( $this->mk_q ) {
			return $this->mk_q;
		}

		if( empty( $this->mk_mime ) ) {
			$this->mk_mime = $mime_type;
		}

		// If it has already been changed, return $default_quality that is not change
		$_default = $this->get_default_quality( $this->mk_mime );
		if( $_default !== $default_quality &&
		      apply_filters( 'stillbe_image_quality_control_force_priority', false ) ) {
			return $default_quality;
		}

		return $this->get_quality_from_size( $this->mk_size, $this->mk_mime );

	}


	// Convert to WebP with GD or Imagick
	abstract protected function _make_webp_embed_library( $filename, $size_data );


	// Generate WebP
	public function make_webp( $filename = null, $size_data = null ) {

		if( ! apply_filters( 'stillbe_image_quality_control_enable_webp', STILLBE_IQ_ENABLE_WEBP, 'generate' ) && 'image/webp' !== $this->mime_type ) {
			return false;
		}

	//	@since 0.5.1 Deleted
	//	$this->var_cwebp['is_exists'] = isset( $this->var_cwebp['is_exists'] ) ? $this->var_cwebp['is_exists'] : $this->_server_cmd_exists( 'cwebp' );
		if( apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY )
		      && stillbe_iqc_is_enabled_cwebp() ) {
			// @since 0.9.0 Added
			//   Enable Conversion in "cwebp" if the Extension Plugin is Installed
			$size         = empty( $size_data['size_name'] ) ? 'default' : $size_data['size_name'];
			$quality      = $this->get_quality_from_size( $size, $this->mime_type );
			$webp_quality = $this->get_quality_from_size( $size, 'image/webp' );
			// Oprions
			$options = array(
				'quality' => array( $webp_quality, $quality ),
				'mime'    => $this->mime_type,
				'size'    => $this->size,
			);
			// Make WebP usign "cwebp"
			return stillbe_iqc_extends_conv_cwebp( $this->file, $filename, $size_data, $options );
		} elseif( $this->supports_mime_type( 'image/webp' ) ) {
			return $this->_make_webp_embed_library( $filename, $size_data );
		}

		return false;

	}


	// Get a Number of Used Colors in Original Image
	public function get_original_color_num() {

		return $this->color_num;

	}


}




// END of the File



