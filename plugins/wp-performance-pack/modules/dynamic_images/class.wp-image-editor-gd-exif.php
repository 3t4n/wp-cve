<?php
/**
 * Image Editor Class for GD using EXIF thumbnail if available
 *
 * @since 2.0
 * @package WPPP
 * @uses WP_Image_Editor_GD Extends class
 */
 
class WP_Image_Editor_GD_EXIF extends WP_Image_Editor_GD {
	private $thumb_w = 0;
	private $thumb_h = 0;
	private	$thumb_loaded = false;

	public static function test( $args = array() ) {
		return	parent::test( $args )
				&& extension_loaded( 'exif' )
				&& function_exists( 'exif_thumbnail' );
	}

	public static function supports_mime_type( $mime_type ) {
		$image_types = imagetypes();
		switch( $mime_type ) {
			case 'image/jpeg':
				return ($image_types & IMG_JPG) != 0;
		}
		return false;
	}

	public function load() {
		if ( $this->image )
			return true;

		$this->thumb_loaded = false;
		if ( !is_file( $this->file ) && !preg_match( '|^https?://|', $this->file ) )
			return new WP_Error( 'error_loading_image', __( 'File doesn&#8217;t exist?' ), $this->file );

		// Set artificially high because GD uses uncompressed images in memory
		wp_raise_memory_limit( 'image' );

		$size = @getimagesize( $this->file );
		if ( !$size )
			return parent::load();

		$thumb = @exif_thumbnail( $this->file, $this->thumb_w, $this->thumb_h, $thumb_type );
		if ( !$thumb  || ( $this->thumb_w == 0 ) || ( $this->thumb_h == 0 ) )
			return parent::load();

		$this->image = @imagecreatefromstring( $thumb );

		if ( !is_resource( $this->image ) )
			return parent::load();

		if ( function_exists( 'imagealphablending' ) && function_exists( 'imagesavealpha' ) ) {
			imagealphablending( $this->image, false );
			imagesavealpha( $this->image, true );
		}

		$this->update_size( $size[ 0 ], $size[ 1 ] );
		$this->mime_type = image_type_to_mime_type( $thumb_type );
		$this->thumb_loaded = true;
		return $this->set_quality();
	}

	protected function _resize( $max_w, $max_h, $crop = false ) {
		global $wp_performance_pack;
		if ( ( $this->thumb_loaded ) 
			&& ( $max_w > $wp_performance_pack->options[ 'exif_width' ] 
				|| $max_h > $wp_performance_pack->options[ 'exif_height' ] ) ) {
			// requested size is bigger than maximum size for exif thumb usage, so load full image
			imagedestroy( $this->image );
			$this->image = null;
			$this->thumb_loaded = false;
			$res = parent::load();
			if ( is_wp_error( $res ) )
				return $res;
		} 

		if ( !$this->thumb_loaded )
			return parent::_resize( $max_w, $max_h, $crop );

		$dims = image_resize_dimensions( $this->size[ 'width' ], $this->size[ 'height' ], $max_w, $max_h, $crop );
		if ( ! $dims )
			return new WP_Error( 'error_getting_dimensions', __( 'Could not calculate resized image dimensions' ), $this->file );

		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		$resized = wp_imagecreatetruecolor( $dst_w, $dst_h );
		$dx = $this->size[ 'width' ] / $this->thumb_w;
		$dy = $this->size[ 'height' ] / $this->thumb_h;

		imagecopyresampled( $resized, $this->image, $dst_x, $dst_y, floor( $src_x / $dx ), floor( $src_y / $dy ), $dst_w, $dst_h, floor( $src_w / $dx ), floor( $src_h / $dy ) );

		if ( is_resource( $resized ) ) {
			$this->update_size( $dst_w, $dst_h );
			$this->thumb_loaded = false;
			return $resized;
		}

		return new WP_Error( 'image_resize_error', __('Image resize failed.'), $this->file );
	}

/*	public function stream( $mime_type = null ) {
		header( 'Content-Type: image/webp' );
		return imagewebp( $this->image, null, 70 );
	} */
}
