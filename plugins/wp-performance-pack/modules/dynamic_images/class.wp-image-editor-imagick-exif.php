<?php
/**
 * Image Editor Class for Image Magick using EXIF thumbnail if available
 *
 * @since 2.0
 * @package WPPP
 * @uses WP_Image_Editor_Imagick Extends class
 */

class WP_Image_Editor_Imagick_EXIF extends WP_Image_Editor_Imagick {
	private $thumb_loaded = false;
	private $thumb_w = 0;
	private $thumb_h = 0;
	private static $required_methods = array(
			'clear',
			'destroy',
			'valid',
			'getimage',
			'writeimage',
			'getimageblob',
			'getimagegeometry',
			'getimageformat',
			'setimageformat',
			'setimagecompression',
			'setimagecompressionquality',
			'setimagepage',
			'setoption',
			'scaleimage',
			'cropimage',
			'rotateimage',
			'flipimage',
			'flopimage',
			'readimage',
		);

	public static function test( $args = array() ) {
		// First, test Imagick's extension and classes.
		if ( ! extension_loaded( 'imagick' ) || ! class_exists( 'Imagick', false ) || ! class_exists( 'ImagickPixel', false ) )
			return false;

		if ( version_compare( phpversion( 'imagick' ), '2.2.0', '<' ) )
			return false;

		// Now, test for deep requirements within Imagick.
		if ( ! defined( 'imagick::COMPRESSION_JPEG' ) )
			return false;

		foreach ( self::$required_methods as $rm ) {
			if ( !method_exists( 'Imagick', $rm ) )
				return false;
		}
		/*$class_methods = array_map( 'strtolower', get_class_methods( 'Imagick' ) );
		if ( array_diff( $required_methods, $class_methods ) ) {
			return false;
		}*/

		// HHVM Imagick does not support loading from URL, so fail to allow fallback to GD.
		if ( defined( 'HHVM_VERSION' ) && isset( $args['path'] ) && preg_match( '|^https?://|', $args['path'] ) ) {
			return false;
		}

		return extension_loaded( 'exif' )
				&& function_exists( 'exif_thumbnail' );
	}

	public static function supports_mime_type( $mime_type ) {
		return parent::supports_mime_type( $mime_type )
				&& ( $mime_type == 'image/jpeg' );
	}

	public function load() {
		if ( $this->image instanceof Imagick )
			return true;

		$this->thumb_loaded = false;

		if ( ! is_file( $this->file ) && ! preg_match( '|^https?://|', $this->file ) )
			return new WP_Error( 'error_loading_image', __('File doesn&#8217;t exist?'), $this->file );

		/*
		 * Even though Imagick uses less PHP memory than GD, set higher limit
		 * for users that have low PHP.ini limits.
		 */
		wp_raise_memory_limit( 'image' );

		try {
			$size = @getimagesize( $this->file );
			if ( !$size )
				return parent::load();

			$thumb = @exif_thumbnail( $this->file, $this->thumb_w, $this->thumb_h );
			if ( !$thumb )
				return parent::load();

			$this->image = new Imagick();

			// Reading image after Imagick instantiation because `setResolution`
			// only applies correctly before the image is read.
			$this->image->readImageBlob( $thumb );

			if ( ! $this->image->valid() )
				return parent::load();

			// Select the first frame to handle animated images properly
			if ( is_callable( array( $this->image, 'setIteratorIndex' ) ) )
				$this->image->setIteratorIndex(0);

			$this->mime_type = $this->get_mime_type( $this->image->getImageFormat() );
		}
		catch ( Exception $e ) {
			return parent::load();
		}

		$updated_size = $this->update_size( $size[ 0 ], $size[ 1 ] );
		if ( is_wp_error( $updated_size ) ) {
			return parent::load();
		}

		$this->thumb_loaded = true;
		return $this->set_quality();
	}

	/**
	 * Resizes current image.
	 *
	 * At minimum, either a height or width must be provided.
	 * If one of the two is set to null, the resize will
	 * maintain aspect ratio according to the provided dimension.
	 *
	 * @since 3.5.0
	 * @access public
	 *
	 * @param  int|null $max_w Image width.
	 * @param  int|null $max_h Image height.
	 * @param  bool     $crop
	 * @return bool|WP_Error
	 */
	public function resize( $max_w, $max_h, $crop = false ) {
		global $wp_performance_pack;
		if ( ( $this->thumb_loaded ) 
			&& ( $max_w > $wp_performance_pack->options[ 'exif_width' ] 
				|| $max_h > $wp_performance_pack->options[ 'exif_height' ] ) ) {
			// requested size is bigger than maximum size for exif thumb usage, so load full image
			if ( $this->image instanceof Imagick ) {
				// we don't need the original in memory anymore
				$this->image->clear();
				$this->image->destroy();
			}
			$this->image = null;
			$this->thumb_loaded = false;
			$res = parent::load();
			if ( is_wp_error( $res ) )
				return $res;
		}

		if ( !$this->thumb_loaded )
			return parent::resize( $max_w, $max_h, $crop );

		if ( ( $this->thumb_w == $max_w ) && ( $this->thumb_h == $max_h ) )
			return true;

		$dims = image_resize_dimensions( $this->size['width'], $this->size['height'], $max_w, $max_h, $crop );
		if ( ! $dims )
			return new WP_Error( 'error_getting_dimensions', __('Could not calculate resized image dimensions') );
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		if ( $crop ) {
			$dx = $this->size[ 'width' ] / $this->thumb_w;
			$dy = $this->size[ 'height' ] / $this->thumb_h;
			$this->thumb_loaded = false;
			return $this->crop( floor( $src_x / $dx ), floor( $src_y / $dy ), floor( $src_w / $dx ), floor( $src_h / $dy ), $dst_w, $dst_h );
		}

		// Execute the resize
		$this->update_size( $this->thumb_w, $this->thumb_h );
		$thumb_result = $this->thumbnail_image( $dst_w, $dst_h );
		if ( is_wp_error( $thumb_result ) ) {
			return $thumb_result;
		}

		$this->thumb_loaded = false;
		return $this->update_size( $dst_w, $dst_h );
	}
}
