<?php

namespace GPLSCore\GPLS_PLUGIN_WGR;

use Exception;
use Grafika\Gd\Editor;
use Grafika\Gd\Helper\GifHelper;

/**
 * Gif Editor CLass.
 */
class GIF_Editor {

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
		add_filter( 'wp_image_editors', array( get_called_class(), 'add_gif_editor_class_for_gif_subsizes' ), PHP_INT_MAX, 1 );
		add_action( 'edit_form_before_permalink', array( get_called_class(), 'activate_notice' ), PHP_INT_MAX, 1 );
		add_filter( 'plugin_action_links_' . self::$plugin_info['basename'], array( get_called_class(), 'plugin_pro_link' ), 10, 1 );
	}

	/**
	 * Activate Plugin Notice in Image edit page.
	 *
	 * @param object $post
	 * @return void
	 */
	public static function activate_notice( $post ) {
		if ( is_object( $post ) && ( 'image/gif' === $post->post_mime_type ) ) {
			?>
			<div class="notice notice-warning" ><p><?php echo sprintf( __( 'You can apply all edits options on GIF without losing animation in %s version ', 'wp-gif-editor' ), '<a target="_blank" href="' . esc_url( self::$plugin_info['pro_link'] ) . '" ><strong>' . esc_html__( 'Pro', 'wp-gif-uploader' ) . '</strong></a>' ); ?></p></div>
			<?php
		}
	}

	/**
	 * Pro Link.
	 *
	 * @param array $links
	 * @return array
	 */
	public static function plugin_pro_link( $links ) {
		$links[] = '<a href="' . esc_url_raw( self::$plugin_info['pro_link'] ) . '" target="_blank" >' . __( 'Pro Version', 'wp-gif-uploader' ) . '</a>';
		return $links;
	}

	/**
	 * Add The GIF Editor Class to editors filter.
	 *
	 * @param array $editors_classes_array  Image Editors Classes Array.
	 * @return array
	 */
	public static function add_gif_editor_class_for_gif_subsizes( $editors_classes_array ) {
		// Upload media from REST.
		if ( did_action( 'rest_after_insert_attachment' ) && ! empty( $_FILES['file'] ) && ( 'image/gif' === sanitize_text_field( wp_unslash( $_FILES['file']['type'] ) ) ) ) {
			$editors_classes_array = self::get_gif_editor( $editors_classes_array );
		}

		// Check if its an upload attachment | media process and it's a GIF type.
		if ( ! empty( $_POST[ self::$plugin_info['name'] . '-gif-creator' ] ) || ( ! empty( $_FILES ) && ! empty( $_FILES['async-upload'] ) && ! empty( $_FILES['async-upload']['type'] ) && 'image/gif' === sanitize_text_field( wp_unslash( $_FILES['async-upload']['type'] ) ) ) ) {
			$editors_classes_array = self::get_gif_editor( $editors_classes_array );
		}

		// Regenerate Subsizes Request.
		if ( ! empty( $GLOBALS[ self::$plugin_info['name'] . '-regenerate-subsizes-request' ] ) ) {
			$editors_classes_array = self::get_gif_editor( $editors_classes_array );
		}

		return $editors_classes_array;
	}

	/**
	 * Get GIF Editor.
	 *
	 * @param array $editors_classes
	 * @return array
	 */
	private static function get_gif_editor( $editors_classes ) {
		if ( extension_loaded( 'imagick' ) && class_exists( '\Imagick', false ) ) {
			require_once self::$plugin_info['path'] . 'includes/class-gif-editor-imagick.php';
			array_unshift( $editors_classes, __NAMESPACE__ . '\GIF_Editor_Imagick' );
		} else {
			require_once self::$plugin_info['path'] . 'includes/class-gif-editor-manual.php';
			array_unshift( $editors_classes, __NAMESPACE__ . '\GIF_Editor_Manual' );
		}

		return $editors_classes;
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
	 * @return true|\WP_Error
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

}
