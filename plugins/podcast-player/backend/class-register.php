<?php
/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Backend
 */

namespace Podcast_Player\Backend;

use Podcast_Player\Backend\Inc\Loader;
use Podcast_Player\Backend\Admin\Options;
use Podcast_Player\Backend\Inc\Shortcode;
use Podcast_Player\Backend\Inc\Block;
use Podcast_Player\Backend\Inc\Misc;

/**
 * The admin-specific functionality of the plugin.
 *
 * Register custom widget and custom shortcode functionality. Enqueue admin area
 * scripts and styles.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Backend
 * @author     vedathemes <contact@vedathemes.com>
 */
class Register {

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 3.3.0
	 */
	public function __construct() {}

	/**
	 * Register hooked functions.
	 *
	 * @since 3.3.0
	 */
	public static function init() {

		// Load podcast player resources on admin screens.
		$loader = Loader::get_instance();

		self::load_resources( $loader );

		// Initiate podcast player admin notices.
		self::admin_notices( $loader );

		// Add Elementor edit screen support.
		self::elementor_support( $loader );

		// Register podcast player widget.
		self::register_widget();

		// Register podcast player block.
		self::register_block();

		// Add action links.
		self::action_links();

		// Register miscellaneous actions.
		self::misc_actions();

		// Register podcast player shortcode display method.
		self::register_shortcode();

		// Compatibility with previous versions of PP Pro.
		self::pro_compatibility($loader);

		// Register admin options.
		$options = Options::get_instance();
		$options->init();
	}

	/**
	 * Load podcast player resources on admin screens.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP admin loader instance.
	 */
	public static function load_resources( $instance ) {
		add_action( 'admin_enqueue_scripts', array( $instance, 'enqueue_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $instance, 'enqueue_scripts' ) );
		add_action( 'enqueue_block_editor_assets', array( $instance, 'enqueue_editor_scripts' ) );
		add_action( 'admin_footer', array( $instance, 'svg_icons' ), 9999 );

		/*
		 * This script must be loaded before mediaelement-migrate.js to work.
		 * admin_enqueue_scripts hook is very late for that. As migrate script added
		 * by script handle 'wp-edit-post' at very top of 'edit-form-blocks.php'.
		 */
		add_action( 'admin_init', array( $instance, 'mediaelement_migrate_error_fix' ), 0 );
	}

	/**
	 * Initiate podcast player admin notices.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP admin loader instance.
	 */
	public static function admin_notices( $instance ) {
		add_action( 'admin_head', array( $instance, 'dismiss_notices' ) );
		add_action( 'admin_notices', array( $instance, 'admin_notices' ) );
	}

	/**
	 * Add Elementor edit screen support.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP admin loader instance.
	 */
	public static function elementor_support( $instance ) {
		add_action(
			'elementor/editor/before_enqueue_scripts',
			array( $instance, 'enqueue_styles' )
		);
		add_action(
			'elementor/editor/before_enqueue_scripts',
			array( $instance, 'enqueue_scripts' )
		);
	}

	/**
	 * Register the custom Widget.
	 *
	 * @since 3.3.0
	 */
	public static function register_widget() {
		add_action(
			'widgets_init',
			function() {
				register_widget( 'Podcast_Player\Backend\Inc\Widget' );
			}
		);
	}

	/**
	 * Register podcast player shortcode.
	 *
	 * @since 3.3.0
	 */
	public static function register_shortcode() {
		$shortcode = Shortcode::get_instance();
		add_shortcode( 'podcastplayer', array( $shortcode, 'render' ) );
	}

	/**
	 * Register podcast player editor block.
	 *
	 * @since 3.3.0
	 */
	public static function register_block() {
		$block = Block::get_instance();
		add_action( 'init', array( $block, 'register' ) );
	}

	/**
	 * Register the plugin's miscellaneous actions.
	 *
	 * @since 3.3.0
	 */
	public static function misc_actions() {
		$misc = Misc::get_instance();
		add_action( 'pp_save_images_locally', array( $misc, 'save_images_locally' ) );
		add_action( 'pp_auto_update_podcast', array( $misc, 'auto_update_podcast' ) );
		add_action( 'rest_api_init', array( $misc, 'register_routes' ) );
        add_action( 'init', array( $misc, 'init_storage' ) );
		add_action( 'admin_init', array( $misc, 'transfer_custom_data' ) );
	}

	/**
	 * Register the plugin's miscellaneous actions.
	 *
	 * @since 3.3.0
	 */
	public static function action_links() {
		$misc = Misc::get_instance();
		add_action( 'plugin_action_links_' . PODCAST_PLAYER_BASENAME, array( $misc, 'action_links' ) );
	}

	/**
	 * Compatibility with the pp pro 4.8.1.
	 *
	 * TODO: Only for compatibility. Remove in next update.
	 *
	 * @since 6.6.0
	 *
	 * @param object $instance PP admin loader instance.
	 */
	public static function pro_compatibility( $instance ) {
		if ( defined( 'PP_PRO_VERSION' ) && version_compare( PP_PRO_VERSION, '4.8.1', '==' ) ) {
			add_action( 'admin_enqueue_scripts', array( $instance, 'compat_admin_scripts' ) );
		}
	}
}
