<?php
namespace GPLSCore\GPLS_PLUGIN_AVFSTW;

use GPLSCore\GPLS_PLUGIN_AVFSTW\AJAXs\SettingsAJAX;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Pages\SettingsPage;
use GPLSCore\GPLS_PLUGIN_AVFSTW\Utils\Img\ImgUtilsTrait;

/**
 * Images Type Suppport Class.
 */
class AvifSupport extends Base {

	use ImgUtilsTrait;

	/**
	 * Singleton Instance.
	 *
	 * @var self
	 */
	private static $instance = null;

	/**
	 * Settings Key.
	 *
	 * @var string
	 */
	private static $settings_key;

	/**
	 * Default Settings.
	 *
	 * @var array
	 */
	private static $default_settings;

	/**
	 * Constructor.
	 */
	private function __construct() {
		$this->setup();
		$this->hooks();
	}

	/**
	 * Setup.
	 *
	 * @return void
	 */
	private function setup() {
		self::$settings_key     = self::$plugin_info['prefix'] . '-avif-support';
		self::$default_settings = array(
			'lib'     => 'imagick',
			'quality' => 82,
			'speed'   => 6,
		);
	}

	/**
	 * Singleton Instance Init.
	 *
	 * @return self
	 */
	public static function init() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	/**
	 * Get Settings.
	 *
	 * @return mixed
	 */
	public static function get_settings( $key = null ) {
		$settings = get_option( self::$settings_key, self::$default_settings );
		$settings = array_merge( self::$default_settings, $settings );
		return ( is_null( $key ) ? $settings : ( isset( $settings[ $key ] ) ? $settings[ $key ] : false ) );
	}

	/**
	 * Update Settings.
	 *
	 * @param array $settings
	 * @return void
	 */
	public static function update_settings( $settings ) {
		$settings = array_merge( self::$default_settings, $settings );
		update_option( self::$settings_key, $settings, false );
	}

	/**
	 * Hooks.
	 *
	 * @return void
	 */
	private function hooks() {
		add_filter( 'getimagesize_mimes_to_exts', array( $this, 'filter_mime_to_exts' ), PHP_INT_MAX, 1 );
		add_filter( 'mime_types', array( $this, 'filter_mime_types' ), PHP_INT_MAX, 1 );
		add_filter( 'upload_mimes', array( $this, 'filter_allowed_mimes' ), PHP_INT_MAX, 2 );
		add_filter( 'wp_generate_attachment_metadata', array( $this, 'fix_avif_images' ), 1, 3 );
		add_filter( 'file_is_displayable_image', array( $this, 'fix_avif_displayable' ), PHP_INT_MAX, 2 );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'handle_exif_andfileinfo_fail' ), PHP_INT_MAX, 5 );
		add_filter( 'wp_image_editors', array( $this, 'filter_gd_editor' ), PHP_INT_MAX, 1 );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_assets' ) );
		add_filter( 'wp_editor_set_quality', array( $this, 'filter_default_quality' ), PHP_INT_MAX, 2 );
	}

	/**
	 * Filter Default Quality.
	 *
	 * @param int $default_quality
	 * @param string $mime_type
	 * @return int
	 */
	public function filter_default_quality( $default_quality, $mime_type ) {
		if ( 'image/avif' !== $mime_type ) {
			return $default_quality;
		}
		$default_quality = absint( self::get_settings( 'quality' ) );
		return $default_quality;
	}

	/**
	 * Admin assets.
	 *
	 * @return void
	 */
	public function admin_assets() {
		$settings_page = SettingsPage::init();
		if ( ! $settings_page->is_current_page( true ) ) {
			return;
		}
		if ( ! wp_script_is( 'jquery', 'enqueued' ) ) {
			wp_enqueue_script( 'jquery' );
		}
		wp_enqueue_script( self::$plugin_info['prefix'] . '-settings-js', self::$plugin_info['url'] . 'assets/dist/js/admin/settings.min.js', array( 'jquery' ), self::$plugin_info['version'], true );
		wp_localize_script(
			self::$plugin_info['prefix'] . '-settings-js',
			str_replace( '-', '_', self::$plugin_info['prefix'] . '-localize-data' ),
			array(
				'ajaxUrl' => admin_url( 'admin-ajax.php' ),
				'prefix'  => self::$plugin_info['prefix'],
				'nonce'   => wp_create_nonce( self::$plugin_info['prefix'] . '-nonce' ),
				'action'  => ( SettingsAJAX::init() )->get_ajax_prop( 'general_settings', 'action' ),
			)
		);
	}

	/**
	 * Filter GD Editor.
	 *
	 * @param array $editors
	 * @return array
	 */
	public function filter_gd_editor( $editors ) {
		if ( ! $this->is_gd_and_not_imagick() ) {
			return $editors;
		}

		$supported_gd_editor_class = __NAMESPACE__ . '\AVIFGDEditor';

		// Filter GD editor with our editor.
		if ( in_array( 'WP_Image_Editor_GD', $editors ) ) {
			$editors[ array_search( 'WP_Image_Editor_GD', $editors ) ] = $supported_gd_editor_class;
		} else {
			$editors[] = $supported_gd_editor_class;
		}

		return $editors;
	}

	/**
	 * Filter Mime to Ext.
	 *
	 * @param array $mime_to_exsts
	 *
	 * @return array
	 */
	public function filter_mime_to_exts( $mime_to_exsts ) {
		$mime_to_exsts['image/avif'] = 'avif';
		return $mime_to_exsts;
	}

	/**
	 * Filter Mimes.
	 *
	 * @param array $mimes
	 * @return array
	 */
	public function filter_mime_types( $mimes ) {
		$mimes['avif'] = 'image/avif';
		return $mimes;
	}

	/**
	 * Filter Allowed Mimes.
	 *
	 * @param array    $mimes
	 * @param \WP_User $user
	 * @return array
	 */
	public function filter_allowed_mimes( $mimes, $user ) {
		$mimes['avif'] = 'image/avif';
		return $mimes;
	}

	/**
	 * Fix AVif Displayable Image.
	 *
	 * @param boolean $result
	 * @param string  $path
	 * @return boolean
	 */
	public function fix_avif_displayable( $result, $path ) {
		// Pypass avif.
		if ( str_ends_with( $path, '.avif' ) ) {
			return true;
		}

		return $result;
	}

	/**
	 * Fix Avif Image Support.
	 *
	 * @param array  $metadata
	 * @param int    $attachment_id
	 * @param string $context
	 * @return array
	 */
	public function fix_avif_images( $metadata, $attachment_id, $context ) {
		// If it's empty, It's already failed.
		if ( empty( $metadata ) ) {
			return $metadata;
		}

		$attachemnt_post = get_post( $attachment_id );
		if ( ! $attachemnt_post || is_wp_error( $attachemnt_post ) ) {
			return $metadata;
		}

		if ( 'image/avif' !== $attachemnt_post->post_mime_type ) {
			return $metadata;
		}

		// Fix Width and Height in Metadata.
		$metadata = $this->fix_avif_metadata( $metadata, $attachment_id );

		// Fix scaled image.
		$metadata = $this->fix_avif_scaled_image( $metadata, $attachment_id );

		return $metadata;
	}

	/**
	 * Fix Avif Scaled Image Generation.
	 *
	 * @param array $metadata
	 * @param int   $attachment_id
	 * @return array
	 */
	private function fix_avif_scaled_image( $metadata, $attachment_id ) {
		$file = get_attached_file( $attachment_id );
		if ( ! $file ) {
			return $metadata;
		}

		// IF it's still zero, bail.
		if ( empty( $metadata ) || empty( $metadata['width'] ) || empty( $metadata['height'] ) ) {
			return $metadata;
		}

		$imagesize = self::get_imagesize( $file );
		$threshold = (int) apply_filters( 'big_image_size_threshold', 2560, $imagesize, $file, $attachment_id );

		// No Threshold, bail.
		if ( ! $threshold ) {
			return $metadata;
		}

		$exif_meta = wp_read_image_metadata( $file );
		if ( $exif_meta ) {
			$metadata['image_meta'] = $exif_meta;
		}

		if ( $threshold && ( $metadata['width'] > $threshold || $metadata['height'] > $threshold ) ) {
			$editor = wp_get_image_editor( $file );

			if ( is_wp_error( $editor ) ) {
				// This image cannot be edited.
				return $metadata;
			}

			// Resize the image.
			$resized = $editor->resize( $threshold, $threshold );
			$rotated = null;

			// If there is EXIF data, rotate according to EXIF Orientation.
			if ( ! is_wp_error( $resized ) && is_array( $exif_meta ) ) {
				$resized = $editor->maybe_exif_rotate();
				$rotated = $resized;
			}

			if ( ! is_wp_error( $resized ) ) {
				$saved = $editor->save( $editor->generate_filename( 'scaled' ) );
				if ( ! is_wp_error( $saved ) ) {
					$metadata = _wp_image_meta_replace_original( $saved, $file, $metadata, $attachment_id );
					if ( true === $rotated && ! empty( $metadata['image_meta']['orientation'] ) ) {
						$metadata['image_meta']['orientation'] = 1;
					}
				} else {
					// TODO: Log errors.
				}
			} else {
				// TODO: Log errors.
			}
		}

		return $metadata;
	}

	/**
	 * Fix Avif Dimension Metadata.
	 *
	 * @param array  $metadata
	 * @param int    $attachment_id
	 * @param string $context
	 * @return array
	 */
	private function fix_avif_metadata( $metadata, $attachment_id ) {
		if ( ( ! empty( $metadata['width'] ) && ( 0 !== $metadata['width'] ) ) && ( ! empty( $metadata['height'] ) && 0 !== $metadata['height'] ) ) {
			return $metadata;
		}

		$file = get_attached_file( $attachment_id );
		if ( ! $file ) {
			return $metadata;
		}

		if ( empty( $metadata['width'] ) ) {
			$metadata['width'] = 0;
		}

		if ( empty( $metadata['height'] ) ) {
			$metadata['height'] = 0;
		}

		if ( empty( $metadata['file'] ) ) {
			$metadata['file'] = _wp_relative_upload_path( $file );
		}

		if ( empty( $metadata['sizes'] ) ) {
			$metadata['sizes'] = array();
		}

		$avif_specs = self::get_image_specs( $file );
		if ( is_wp_error( $avif_specs ) || ! $avif_specs ) {
			return $metadata;
		}

		// Manual Avif Width and Height.
		if ( 0 === $avif_specs['width'] && 0 === $avif_specs['height'] ) {
			$avif_dim = self::get_avif_dim_manual( $file );

			if ( is_array( $avif_dim ) ) {
				$avif_specs['width']  = $avif_dim['width'];
				$avif_specs['height'] = $avif_dim['height'];
			}
		}

		$metadata['width']  = $avif_specs['width'];
		$metadata['height'] = $avif_specs['height'];

		return $metadata;
	}


	/**
	 * Handle the fail of exif and fileinfo to detect AVIF [ Rare scenario - Trash Hostings ].
	 *
	 * @param array        $filename_and_type_arr
	 * @param string       $file_path
	 * @param string       $filename
	 * @param array        $mimes
	 * @param string|false $real_mime
	 * @return array
	 */
	public function handle_exif_andfileinfo_fail( $filename_and_type_arr, $file_path, $filename, $mimes, $real_mime ) {
		// ext and type found? proceed.
		if ( $filename_and_type_arr['ext'] && $filename_and_type_arr['type'] ) {
			return $filename_and_type_arr;
		}

		// Not AVIF, return.
		if ( ! str_ends_with( $filename, '.avif' ) ) {
			return $filename_and_type_arr;
		}

		// Valid AVIF.
		if ( self::get_avif_dim_manual( $file_path ) ) {
			$filename_and_type_arr['type'] = 'image/avif';
			$filename_and_type_arr['ext']  = 'avif';
		}

		return $filename_and_type_arr;
	}

	/**
	 * Check if GD is installed ans supports AVIF and Imagick is not.
	 *
	 * @return boolean
	 */
	private function is_gd_and_not_imagick() {
		return ( self::is_type_supported( 'avif', 'gd' ) && ! self::is_type_supported( 'avif', 'imagick' ) );
	}

}
