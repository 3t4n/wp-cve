<?php


/**
 * Trait Used in a Class that extends WP_Image_Editor_GD/Imagick Class
 * 
 *  * Define Override Method to the Base Class
 * 
 */


// Do not allow direct access to the file.
if( ! defined( 'ABSPATH' ) ) {
	exit;
}




// 親 class から上書きする共通メソッドを定義
trait StillBE_Image_Editor_Common_Overwrite {


	// Overwrite 'make_subsize' method
	public function make_subsize( $size_data ) {

		// 現在の設定を退避しておく
		$current_quality = $this->get_quality();

		// 圧縮品質を変更する
		// for up to WP 5.7
		$size    = empty( $size_data['size_name'] ) ? 'default' : $size_data['size_name'];
		$quality = $this->get_quality_from_size( $size, $this->mime_type );
		$this->set_quality( $quality );

		// WP 5.8 対応で hook を追加する (@since 0.7.5)
		$this->_set_mk_size( $size );
		$this->_set_mk_mime( $this->mime_type );
		add_filter( 'wp_editor_set_quality', array( $this, '_set_quality_hook' ), 1, 2 );

		// リサイズして保存する
		if( apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY )
		      && 'image/webp' === $this->mime_type && stillbe_iqc_is_enabled_cwebp() ) {
			$orig_size = $this->size;
			$_resize = $this->resize( $size_data['width'], $size_data['height'], $size_data['crop'] );
			if( is_wp_error( $_resize ) ) {
				$this->size = $orig_size;
				return $_resize;
			}
			$ouptu_filename = $this->generate_filename( null, null, 'webp' );
			$this->size = $orig_size;
			$result = $this->make_webp( $ouptu_filename, $size_data );
			if( is_wp_error( $result ) ) {
				$meta = $result;
			} else {
				$meta = $result['meta'];
				$meta['updated'] = time();
				$meta['sb-iqc']  = array( 'quality' => $this->get_quality() );
				if( isset( $result['cwebp'] ) ) {
					$meta['sb-iqc']['cwebp'] = $result['cwebp'];
				}
			}
		} else {
			$meta = parent::make_subsize( $size_data );
			if( ! is_wp_error( $meta ) ) {
				$meta['updated'] = time();
				$meta['sb-iqc']  = array( 'quality' => $this->get_quality() );
			}
		}

		if( ! is_wp_error( $meta ) && 'image/webp' !== $this->mime_type ) {
			// Save WebP
			$basedir   = dirname( $this->file );
			$webp_name = apply_filters( "stillbe_uploaded_image_webp_name", "{$basedir}/{$meta['file']}.webp" );
			$webp_data = $this->make_webp( $webp_name, $size_data );
			if( ! is_wp_error( $webp_data ) && ! empty( $webp_data['size'] ) ) {
				$meta['sb-iqc']['webp-file']    = $webp_data['file'];
				$meta['sb-iqc']['webp-quality'] = $webp_data['q'];
				if( isset( $webp_data['cwebp'] ) ) {
					$meta['sb-iqc']['cwebp']    = $webp_data['cwebp'];
				}
			}
		}

		$this->set_quality( $current_quality );

		// WP 5.8 対応で追加した hook を削除する (@since 0.7.5)
		remove_filter( 'wp_editor_set_quality', array( $this, '_set_quality_hook' ), 9999 );

		return $meta;

	}


	// WP_Image_Editor の generate_filename を改造
	// 半角英数字と-と_以外が含まれている場合は安全な名前に変更する
	// 'stillbe_image_quality_control_convert_safename' フックに false を返すとファイル名を変更しない
	public function generate_filename( $suffix = null, $dest_path = null, $extension = null ) {

		// WP組込Classによる画像ファイル名
		$wp_generated_name = parent::generate_filename( $suffix, $dest_path, $extension );

		// 半角英数字と-_以外が含まれる場合はファイル名を置換する
		$wp_generated_name = $this->generate_safe_filename( $wp_generated_name, $suffix );

		// Return after Filtering
		return apply_filters( "stillbe_uploaded_image_{$this->mime_type}_name", $wp_generated_name );

	}


	// Suffix に Quality の値を追加する
	// ユーザが追加できる suffix 用のフィルターも追加する
	public function get_suffix() {

		$suffix = parent::get_suffix();
		if( false === $suffix ) {
			return false;
		}

		$user_additional_suffix = apply_filters( 'stillbe_image_quality_control_suffix_options', '' );
		$user_additional_suffix = apply_filters( 'stillbe_image_quality_control_suffix_options_'. self::IMAGE_EDITOR_LIBRARY, $user_additional_suffix );

		if( apply_filters( 'stillbe_image_quality_control_suffix_q_value', STILLBE_IQ_ENABLE_QUALITY_VALUE_SUFFIX ) ) {
			$quality = $this->get_quality();
			return $suffix. '-q'. $quality. $user_additional_suffix;
		}

		return $suffix. $user_additional_suffix;

	}


	// 
	public static function supports_mime_type( $mime_type ) {

		if( 'image/webp' === $mime_type && stillbe_iqc_is_enabled_cwebp()
		      && apply_filters( 'stillbe_image_quality_control_enable_cwebp_lib', STILLBE_IQ_ENABLE_CWEBP_LIBRARY ) ) {
			return true;
		}

		return parent::supports_mime_type( $mime_type );

	}


}




// END of the File



