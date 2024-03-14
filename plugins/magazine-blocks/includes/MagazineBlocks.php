<?php
/**
 * Magazine Blocks plugin main class.
 *
 * @since 1.0.0
 * @package Magazine Blocks
 */

namespace MagazineBlocks;

// Exit if accessed directly
defined( 'ABSPATH' ) || exit;

use MagazineBlocks\RestApi\RestApi;
use MagazineBlocks\Traits\Singleton;

/**
 * Magazine Blocks setup.
 *
 * Include and initialize necessary files and classes for the plugin.
 *
 * @since   1.0.0
 */
final class MagazineBlocks {

	use Singleton;

	/**
	 * @var Utils
	 */
	public $utils;

	/**
	 * Plugin Constructor.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private function __construct() {
		$this->init_props();
		Activation::init();
		Deactivation::init();
		Update::init();
		RestApi::init();
		Admin::init();
		Blocks::init();
		Ajax::init();
		ScriptStyle::init();
		Review::init();
		MaintenanceMode::init();
		$this->init_hooks();
	}

	/**
	 * Init properties.
	 *
	 * @return void
	 */
	private function init_props() {
		$this->utils = Utils::init();
	}

	/**
	 * Initialize hooks.
	 *
	 * @since 1.0.0
	 */
	private function init_hooks() {
		add_action( 'init', array( $this, 'after_wp_init' ), 0 );
		add_filter( 'wp_check_filetype_and_ext', array( $this, 'check_filetype_and_ext' ), 10, 5 );
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_global_styles' ) );
	}

	public function enqueue_global_styles() {
		$global_styles = magazine_blocks_generate_global_styles();
		$global_styles->enqueue_fonts();
		$global_styles->enqueue();
	}


	/**
	 * Initialize Magazine Blocks when WordPress initializes.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function after_wp_init() {
		/**
		 * Magazine Blocks before init.
		 *
		 * @since 1.0.0
		 */
		do_action( 'magazine_blocks_before_init' );
		$this->update_plugin_version();
		$this->load_text_domain();
		/**
		 * Magazine Blocks init.
		 *
		 * Fires after Magazine Blocks has loaded.
		 *
		 * @since 1.0.0
		 */
		do_action( 'magazine_blocks_init' );
	}

	/**
	 * Update the plugin version.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	private static function update_plugin_version() {
		update_option( '_magazine_blocks_version', MAGAZINE_BLOCKS_VERSION );
	}

	/**
	 * Load plugin text domain.
	 */
	private static function load_text_domain() {
		load_plugin_textdomain( 'magazine-blocks', false, plugin_basename( dirname( MAGAZINE_BLOCKS_PLUGIN_FILE ) ) . '/languages' );
	}

	/**
	 * Return valid filetype array for lottie json uploads.
	 *
	 * @param array  $value Filetype array.
	 * @param string $file Original file.
	 * @param string $filename Filename.
	 * @param array  $mimes Mimes array.
	 * @param string $real_mime Real mime type.
	 * @return array
	 */
	public function check_filetype_and_ext( $value, $file, $filename, $mimes, $real_mime ) {

		$wp_filetype = wp_check_filetype( $filename, $mimes );
		$ext         = $wp_filetype['ext'];
		$type        = $wp_filetype['type'];

		if ( 'json' !== $ext || 'application/json' !== $type || 'text/plain' !== $real_mime ) {
			return $value;
		}

		$value['ext']             = $wp_filetype['ext'];
		$value['type']            = $wp_filetype['type'];
		$value['proper_filename'] = $filename;

		return $value;
	}
}
