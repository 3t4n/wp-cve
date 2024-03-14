<?php

namespace GPLSCore\GPLS_PLUGIN_WGR;

use Exception;
use Grafika\Gd\Editor;
use Grafika\Gd\Helper\GifHelper;

/**
 * Gif Editor Manual CLass.
 */
class GIF_Editor_Manual extends \WP_Image_Editor {

	/**
	 * Plugin Info Array.
	 *
	 * @var array
	 */
	protected static $plugin_info;

	/**
	 * Core Object.
	 *
	 * @var object
	 */
	protected static $core;

	/**
	 * GD Resource.
	 *
	 * @var Grafika\Imagick\Image|Grafika\GD\Image
	 */
	protected $image;

	/**
	 * Editor Object.
	 *
	 * @var Grafika\Imagick\Editor|Grafika\GD\Editor
	 */
	protected $editor;

	/**
	 * Gif Helper Object.q
	 *
	 * @var GifHelper
	 */
	protected $gif_helper;

	/**
	 * Decoded GIF Blocks.
	 *
	 * @var array
	 */
	protected $gif_blocks = array();

	/**
	 * Decoded GIF Images Binary Strign Array.
	 *
	 * @var array
	 */
	protected $gif_images = array();

	/**
	 * Latest Transformed Image Binary.
	 *
	 * @var string
	 */
	protected $latest_transformed;

	/**
	 * History Changes.
	 *
	 * @var array
	 */
	protected $history;

	/**
	 * GIF Editor Initialization.
	 *
	 * @param array $plugin_info Plugin Info Array.
	 * @return void
	 */
	public static function init( $plugin_info, $core ) {
		self::$plugin_info = $plugin_info;
		self::$core        = $core;
		self::hooks();
	}

	/**
	 * GIf Editor Hooks.
	 *
	 * @return void
	 */
	public static function hooks() {
	}

	/**
	 * Convert PATH to URL.
	 *
	 * @param string $gif_path
	 * @return string
	 */
	private static function convert_path_to_url( $gif_path ) {
		$uploads = wp_get_upload_dir();
		$gif_url = str_replace( $uploads['basedir'], $uploads['baseurl'], $gif_path );
		return $gif_url;
	}

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
	 * Loads the editor and the image objects with the image path.
	 *
	 * @return true|WP_Error True if loaded successfully; WP_Error on failure.
	 */
	public function load() {
		if ( $this->image ) {
			return true;
		}

		if ( ! is_file( $this->file ) && ! preg_match( '|^https?://|', $this->file ) ) {
			return new \WP_Error( 'error_loading_image', esc_html__( 'File doesn&#8217;t exist?' ), $this->file );
		}

		// Set artificially high because GD uses uncompressed images in memory.
		wp_raise_memory_limit( 'image' );

		$file_contents = @file_get_contents( $this->file );

		if ( ! $file_contents ) {
			return new \WP_Error( 'error_loading_image', esc_html__( 'File doesn&#8217;t exist?' ), $this->file );
		}

		$size = wp_getimagesize( $this->file );

		if ( ! $size ) {
			return new \WP_Error( 'invalid_image', esc_html__( 'Could not read image size.' ), $this->file );
		}

		$this->history    = ! empty( $_REQUEST['history'] ) ? json_decode( wp_unslash( $_REQUEST['history'] ) ) : null;
		$this->gif_helper = new GifHelper();

		$this->update_size( $size[0], $size[1] );
		$this->mime_type = $size['mime'];

		$this->editor = new Editor();
		$this->editor->open( $this->image, $this->file );

		$blocks = $this->image->getBlocks();

		// 2) Resize the Frames and adjust the background for each frame.
		$this->gif_blocks = $this->gif_helper->resize( $blocks, $blocks['canvasWidth'], $blocks['canvasHeight'] );

		// 3) Get GIF Frames.
		$frames = $this->gif_helper->splitFrames( $this->gif_blocks );

		// 4) Convert the image hex string to binary.
		$this->gif_images = array_map(
			function( $frame ) {
				$img_hex = $this->gif_helper->encode( $frame );
				return hex2bin( $img_hex );
			},
			$frames
		);

		return true;
	}

	/**
	 * Sets or updates current image size.
	 *
	 * @since 3.5.0
	 *
	 * @param int $width
	 * @param int $height
	 * @return true
	 */
	protected function update_size( $width = false, $height = false ) {
		if ( ! $width ) {
			$width = $this->get_width();
		}

		if ( ! $height ) {
			$height = $this->get_height();
		}

		return parent::update_size( $width, $height );
	}

	/**
	 * Update Image Blocks
	 *
	 * @return void
	 */
	private function update_image_blocks( $gif_blocks = array() ) {
		$this->image->setBlocks( empty( $gif_blocks ) ? $this->gif_blocks : $gif_blocks );
	}

	/**
	 * Update Image Block after transfomration process.
	 *
	 * @param string $frame Frame Binary String.
	 * @param int    $index Image Frame Index.
	 * @return void
	 */
	private function update_image_block( $frame, $index, &$gif_blocks = array() ) {
		$frame_as_hex    = $this->gif_helper->load( $frame );
		$frame_as_blocks = $this->gif_helper->decode( $frame_as_hex );
		if ( empty( $gif_blocks ) ) {
			$this->gif_blocks['frames'][ $index ]['imageWidth']            = $frame_as_blocks['frames'][0]['imageWidth'];
			$this->gif_blocks['frames'][ $index ]['imageHeight']           = $frame_as_blocks['frames'][0]['imageHeight'];
			$this->gif_blocks['frames'][ $index ]['imageLeft']             = $frame_as_blocks['frames'][0]['imageLeft'];
			$this->gif_blocks['frames'][ $index ]['imageTop']              = $frame_as_blocks['frames'][0]['imageTop'];
			$this->gif_blocks['frames'][ $index ]['imageData']             = $frame_as_blocks['frames'][0]['imageData'];
			$this->gif_blocks['frames'][ $index ]['localColorTableFlag']   = $frame_as_blocks['globalColorTableFlag'];
			$this->gif_blocks['frames'][ $index ]['localColorTable']       = $frame_as_blocks['globalColorTable'];
			$this->gif_blocks['frames'][ $index ]['sizeOfLocalColorTable'] = $frame_as_blocks['sizeOfGlobalColorTable'];
			$this->gif_blocks['frames'][ $index ]['transparentColorFlag']  = 0;
		} else {
			$gif_blocks['frames'][ $index ]['imageWidth']            = $frame_as_blocks['frames'][0]['imageWidth'];
			$gif_blocks['frames'][ $index ]['imageHeight']           = $frame_as_blocks['frames'][0]['imageHeight'];
			$gif_blocks['frames'][ $index ]['imageLeft']             = $frame_as_blocks['frames'][0]['imageLeft'];
			$gif_blocks['frames'][ $index ]['imageTop']              = $frame_as_blocks['frames'][0]['imageTop'];
			$gif_blocks['frames'][ $index ]['imageData']             = $frame_as_blocks['frames'][0]['imageData'];
			$gif_blocks['frames'][ $index ]['localColorTableFlag']   = $frame_as_blocks['globalColorTableFlag'];
			$gif_blocks['frames'][ $index ]['localColorTable']       = $frame_as_blocks['globalColorTable'];
			$gif_blocks['frames'][ $index ]['sizeOfLocalColorTable'] = $frame_as_blocks['sizeOfGlobalColorTable'];
			$gif_blocks['frames'][ $index ]['transparentColorFlag']  = 0;
		}

	}
	/**
	 * Resize GIF ( overwrite ).
	 *
	 * @param int|null $max_w Image width.
	 * @param int|null $max_h Image height.
	 * @param bool     $crop
	 * @return void
	 */
	public function resize( $max_w, $max_h, $crop = false ) {
		if ( ( $this->size['width'] == $max_w ) && ( $this->size['height'] == $max_h ) ) {
			return true;
		}

		$resized_blocks = $this->_resize( $max_w, $max_h, $crop );
		if ( is_wp_error( $resized_blocks ) ) {
			return $resized_blocks;
		}

		$this->gif_blocks = $resized_blocks;

		$this->update_image_blocks();

		@imagedestroy( $this->get_core() );
		return true;
	}

	/**
	 * Resize GIF ( new ).
	 *
	 * @param int        $max_w
	 * @param int        $max_h
	 * @param bool|array $crop
	 * @return array
	 */
	private function _resize( $max_w, $max_h, $crop = false ) {
		// 1) Adjust the new sizes.
		$dims = image_resize_dimensions( $this->size['width'], $this->size['height'], $max_w, $max_h, $crop );
		if ( ! $dims ) {
			return new \WP_Error( 'error_getting_dimensions', esc_html__( 'Could not calculate resized image dimensions' ) );
		}
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		// 2) Resize the Frames and adjust the background for each frame.
		$blocks = $this->gif_helper->resize( $this->gif_blocks, $dst_w, $dst_h );

		if ( isset( $dst_w, $dst_h ) ) {
			$blocks['canvasWidth']  = $dst_w;
			$blocks['canvasHeight'] = $dst_h;
		}
		$blocks['globalColorTableFlag'] = 0;
		$blocks['globalColorTable']     = '';

		$this->update_size( $dst_w, $dst_h );

		return $blocks;
	}

	/**
	 * Multi resize func.
	 *
	 * @param array $sizes Images Sizes Array.
	 * @return array()
	 */
	public function multi_resize( $sizes ) {
		$metadata = array();

		foreach ( $sizes as $size => $size_data ) {
			$meta = $this->make_subsize( $size_data );

			if ( ! is_wp_error( $meta ) ) {
				$metadata[ $size ] = $meta;
			}
		}

		@imagedestroy( $this->get_core() );
		return $metadata;
	}

	/**
	 * Make animated GIF subsizes.
	 *
	 * @param array $size_data
	 * @return array|\WP_Error
	 */
	public function make_subsize( $size_data ) {
		// Overwrite the maximum execution time, as big GIFs will take some time.
		set_time_limit( 0 );
		if ( ! isset( $size_data['width'] ) && ! isset( $size_data['height'] ) ) {
			return new \WP_Error( 'image_subsize_create_error', esc_html__( 'Cannot resize the image. Both width and height are not set.' ) );
		}

		$orig_size = $this->size;

		if ( ! isset( $size_data['width'] ) ) {
			$size_data['width'] = null;
		}

		if ( ! isset( $size_data['height'] ) ) {
			$size_data['height'] = null;
		}

		if ( ! isset( $size_data['crop'] ) ) {
			$size_data['crop'] = false;
		}

		// 1) Adjust the new sizes.
		$dims = image_resize_dimensions( $this->size['width'], $this->size['height'], $size_data['width'], $size_data['height'], $size_data['crop'] );
		if ( ! $dims ) {
			return new \WP_Error( 'error_getting_dimensions', esc_html__( 'Could not calculate resized image dimensions' ) );
		}
		list( $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h ) = $dims;

		$blocks = $this->image->getBlocks();

		// 2) Resize the Frames and adjust the background for each frame.
		$blocks = $this->gif_helper->resize( $blocks, $dst_w, $dst_h );

		// 3) Encode the Blocks and conver it to binary.
		$img_hex = $this->gif_helper->encode( $blocks );
		$img_bin = hex2bin( $img_hex );

		$this->update_size( $dst_w, $dst_h );

		// 4) Save subsize.
		$saved      = $this->_save( null, $this->mime_type, $img_bin, true );
		$this->size = $orig_size;

		// Delete the subsize if its bigger than the original.
		if ( ! is_wp_error( $saved ) ) {
			$subsize_path  = $saved['path'];
			$subsize_size  = filesize( $subsize_path );
			$original_size = filesize( $this->file );

			if ( $subsize_size > $original_size ) {
				unlink( $subsize_path );
				$saved = new \WP_Error(
					'gif-subsize-oversize',
					esc_html__( 'Subsize is bigger than original size. abort!' )
				);
			}
		}

		if ( ! is_wp_error( $saved ) ) {
			unset( $saved['path'] );
		}
		// Return Saved subsize details.
		return $saved;
	}

	/**
	 * Crops GIF.
	 *
	 * @param int  $src_x   The start x position to crop from.
	 * @param int  $src_y   The start y position to crop from.
	 * @param int  $src_w   The width to crop.
	 * @param int  $src_h   The height to crop.
	 * @param int  $dst_w   Optional. The destination width.
	 * @param int  $dst_h   Optional. The destination height.
	 * @param bool $src_abs Optional. If the source crop points are absolute.
	 * @return true|WP_Error
	 */
	public function crop( $src_x, $src_y, $src_w, $src_h, $dst_w = null, $dst_h = null, $src_abs = false ) {
		return false;
	}

	/**
	 * Rotates GIF
	 *
	 * @param int $angle
	 * @return true|\WP_Error
	 */
	public function rotate( $angle ) {
		return false;
	}

	/**
	 * Flips GIF
	 *
	 * @param bool $horz Flip along Horizontal Axis.
	 * @param bool $vert Flip along Vertical Axis.
	 * @return true|\WP_Error
	 */
	public function flip( $horz, $vert ) {
		return false;
	}

	/**
	 * Saves current in-memory image to file.
	 *
	 * @since 3.5.0
	 *
	 * @param string $filename  Full PATH to save the GIF.
	 * @param string $mime_type Mime Type ( image/gif ).
	 * @return array
	 */
	public function save( $filename = null, $mime_type = 'image/gif' ) {
		$saved = $this->_save( $filename, $mime_type );
		if ( ! is_wp_error( $saved ) ) {
			$this->file      = $saved['path'];
			$this->mime_type = $saved['mime-type'];
		}
		@imagedestroy( $this->get_core() );
		return $saved;
	}

	/**
	 * Handle saving GIF.
	 *
	 * @param string $filename
	 * @param string $mime_type
	 * @return array
	 */
	private function _save( $filename = null, $mime_type = 'image/gif', $img_string = '', $is_direct = false ) {
		list( $filename, $extension, $mime_type ) = $this->get_output_format( $filename, $mime_type );
		if ( ! $filename ) {
			$filename = $this->generate_filename( null, null, $extension );
		}

		if ( $is_direct ) {
			file_put_contents( $filename, $img_string );
		} else {
			$hex = $this->gif_helper->encode( $this->gif_blocks );
			$bin = hex2bin( $hex );
			file_put_contents( $filename, $bin );
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
		);
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
		$blocks = $this->image->getBlocks();
		$gift   = new GifHelper();
		$hex    = $gift->encode( $blocks );
		$blob   = hex2bin( $hex );
		echo $blob;
		return true;
	}

	/**
	 * Convert Image Resource to string.
	 *
	 * @param \GdImage $img_resource  Image Resource.
	 * @return string
	 */
	private function resource_to_string( $img_resource ) {
		ob_start();
		imagegif( $img_resource );
		return ob_get_clean();
	}

	/**
	 * Check which image library is used.
	 *
	 * @return \GdImage
	 */
	public function get_core() {
		return $this->image->getCore();
	}

	/**
	 * Get GIF Width.
	 *
	 * @return int
	 */
	public function get_width() {
		return $this->image->getWidth();
	}

	/**
	 * Get GIF Height.
	 *
	 * @return int
	 */
	public function get_height() {
		return $this->image->getHeight();
	}

}
