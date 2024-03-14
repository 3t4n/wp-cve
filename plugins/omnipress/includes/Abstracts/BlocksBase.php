<?php

/**
 * Abstract class for blocks registration class handler.
 *
 * @package Omnipress
 */

namespace Omnipress\Abstracts;

use Omnipress\Helpers;

/**
 * Exit if accessed directly.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Abstract class for blocks registration class handler.
 *
 * @since 1.1.0
 */
abstract class BlocksBase {

	/**
	 * Blocks directory path.
	 *
	 * @var string
	 */
	protected static $dirpath = '';
	/**
	 * Blocks args.
	 *
	 * @var array
	 */
	protected static $blocks = array();
	/**
	 * The "Late Static Binding" class name.
	 *
	 * @var string
	 */
	private static $called_by = '';

	/**
	 * Init blocks
	 *
	 * @return void
	 */
	public static function init() {

		$blocks = static::get_blocks();
		ksort( $blocks );

		self::$called_by = get_called_class();
		self::$dirpath   = trailingslashit( wp_normalize_path( static::get_dirpath() ) );
	self::$blocks    = $blocks;

		add_action( 'init', array( __CLASS__, 'init_blocks' ) );
		add_action( 'enqueue_block_assets', array( __CLASS__, 'register_blocks_assets' ) );

		/**
		 * Localize script for nonce.
		 *
		 * @since 1.2.0
		 */
		add_action( 'wp_enqueue_scripts', array( 'Omnipress\Helpers', 'op_wc_nonce_localize' ) );

		add_action( 'enqueue_block_editor_assets', array( __CLASS__, 'register_block_editor_assets' ) );

		if ( method_exists( self::$called_by, 'register_category' ) ) {
			add_filter( 'block_categories_all', array( self::$called_by, 'register_category' ), PHP_INT_MAX );
		}
	}

	/**
	 * Returns blocks arguments as key:value paired array.
	 * key: being the foldername
	 * value: being the arguments for the block or `register_block_type` function args.
	 *
	 * @see {https://developer.wordpress.org/reference/functions/register_block_type} For `register_block_type` parameters.
	 * @return array
	 */
	abstract public static function get_blocks();

	/**
	 * Returns full path to blocks directory.
	 *
	 * @return string
	 */
	abstract public static function get_dirpath();

	/**
	 * Initialize everything related to our blocks.
	 *
	 * @return void
	 */
	public static function init_blocks() {
		self::validate_blocks();
		self::register_blocks();
	}

	/**
	 * Validate blocks args and generated blocks folders.
	 *
	 * @return void
	 */
	public static function validate_blocks() {
		$blocks_folders = @scandir(self::$dirpath); // @phpcs:ignore

		if ( is_array( $blocks_folders ) && ! empty( $blocks_folders ) ) {
			foreach ( $blocks_folders as $blocks_folder ) {

				if ( '.' === $blocks_folder || '..' === $blocks_folder ) {
					continue;
				}

				$folderpath = self::$dirpath . $blocks_folder;

				if ( ! is_dir( $folderpath ) ) {
					continue;
				}

				if ( ! file_exists( $folderpath . '/block.json' ) ) {
					/**
					 * Bail if current folder is not a block folder.
					 */
					continue;
				}

				if ( ! isset( self::$blocks[ $blocks_folder ] ) ) {
					/* translators: %s is the folder name of the generated block. */
					throw new \Exception( sprintf( __( '"%s" block is missing in blocks args. Please check the block args and generated blocks.', 'omnipress' ), $blocks_folder ) );
				}
			}
		}
	}

	/**
	 * Register blocks.
	 *
	 * @return void
	 */
	public static function register_blocks() {

		/**
		 * Fires before registering blocks.
		 *
		 * @since 1.2.0
		 */
		do_action( 'omnipress_before_blocks_register' );

		if ( is_array( self::$blocks ) && ! empty( self::$blocks ) ) {
			foreach ( self::$blocks as $foldername => $args ) {

				if ( str_contains( $foldername, 'woo' ) ) {

					/**
					 * Check function exists or not.
					 */
					if ( ! function_exists( 'is_plugin_active' ) ) {
						require_once ABSPATH . '/wp-admin/includes/plugin.php';
					}

					/**
					 * Register block type only if woocommerce plugin is active.
					 *
					 * @since 1.2.0
					 */
					if ( function_exists( 'is_plugin_active' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
						register_block_type( self::$dirpath . $foldername, $args );
					}
				} else {
					register_block_type( self::$dirpath . $foldername, $args );
				}
			}
		}

		/**
		 * Fires before registering blocks.
		 *
		 * @since 1.2.0
		 */
		do_action( 'omnipress_after_blocks_register' );
	}

	/**
	 * Enqueue all assets related to blocks for editor and frontend
	 *
	 * @return void
	 */
	public static function register_blocks_assets() {
		self::register_blocks_scripts();
		self::register_blocks_styles();
	}

	/**
	 * Enqueue Blocks scripts
	 *
	 * @return void
	 */
	public static function register_blocks_scripts() {
		$js_files = scandir( OMNIPRESS_PATH . 'assets/js', 0 );
		if ( is_array( $js_files ) && ! empty( $js_files ) ) {
			foreach ( $js_files as $js_file ) {
				if ( strlen( $js_file ) > 2 && file_exists( OMNIPRESS_PATH . 'assets/js/' . $js_file ) ) {
					wp_register_script( 'op-swiper-script-' . str_replace( '.js', '', $js_file ), OMNIPRESS_URL . 'assets/js/' . $js_file, array(), OMNIPRESS_VERSION );
					wp_enqueue_script( 'op-swiper-script-' . str_replace( '.js', '', $js_file ), OMNIPRESS_URL . 'assets/js/' . $js_file, array(), OMNIPRESS_VERSION );
				}
			}
		}
	}

	/**
	 * @return void
	 * Register blocks styles
	 */
	public static function register_blocks_styles() {
		$css_files = scandir( OMNIPRESS_PATH . 'assets/css/public', 0 );

		if ( is_array( $css_files ) && ! empty( $css_files ) ) {

			foreach ( $css_files as $css_file ) {

				if ( strlen( $css_file ) > 2 && file_exists( OMNIPRESS_PATH . 'assets/css/public/' . $css_file ) ) {
					wp_register_style( 'op-style-' . $css_file, OMNIPRESS_URL . 'assets/css/public/' . $css_file, array(), OMNIPRESS_VERSION );
					wp_enqueue_style( 'op-style-' . $css_file );
				}
			}
		}
	}

	/**
	 * Enqueue all assets related to blocks for editor only
	 *
	 * @return void
	 */
	public static function register_block_editor_assets() {
		self::register_block_editor_only_styles();
	}

	/**
	 * @return void
	 * Register blocks editor assets
	 */
	public static function register_block_editor_only_styles() {

		$editor_css_files = scandir( OMNIPRESS_PATH . 'assets/css/editor', 0 );

		if ( is_array( $editor_css_files ) && ! empty( $editor_css_files ) ) {

			foreach ( $editor_css_files as $editor_css_file ) {

				if ( strlen( $editor_css_file ) > 2 && file_exists( OMNIPRESS_PATH . 'assets/css/editor/' . $editor_css_file ) ) {
					wp_register_style( 'op-editor-settings-style-' . str_replace( '.css', '', $editor_css_file ), OMNIPRESS_URL . 'assets/css/editor/' . $editor_css_file, array(), OMNIPRESS_VERSION );
					wp_enqueue_style( 'op-editor-settings-style-' . str_replace( '.css', '', $editor_css_file ) );
				}
			}
		}
	}
}
