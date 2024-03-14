<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://www.vedathemes.com
 * @since      1.0.0
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Frontend
 */

namespace Podcast_Player\Frontend;

use Podcast_Player\Helper\Functions\Getters as Get_Fn;
use Podcast_Player\Frontend\Inc\Loader;
use Podcast_Player\Frontend\Inc\Feed;
use Podcast_Player\Frontend\Inc\Instance_Counter;
use Podcast_Player\Frontend\Inc\General;
use Podcast_Player\Frontend\Inc\Icon_Loader;

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Podcast_Player
 * @subpackage Podcast_Player/Frontend
 * @author     vedathemes <contact@vedathemes.com>
 */
class Register {

	/**
	 * Holds the instance of this class.
	 *
	 * @since  1.0.0
	 * @access protected
	 * @var    object
	 */
	protected static $instance = null;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since 1.0.0
	 */
	public function __construct() {}

	/**
	 * Register hooked functions.
	 *
	 * @since 1.0.0
	 */
	public static function init() {

		// Instantiate front-end loader class.
		$loader = Loader::get_instance();

		// Modify Default Hooks.
		self::compatibility_hooks();

		// Load front-end scripts, styles and icons.
		self::load_resources( $loader );

		// Add media element player settings.
		self::register_mejs_settings( $loader );

		// Add Elementor preview screen support.
		self::elementor_support( $loader );

		// Instantiate front-end feed class to register JS hooks.
		$feed = Feed::get_instance();

		// Make pp data available to front-end scripts.
		self::register_script_data( $feed );

		// Support Ajax loading functionality.
		self::support_ajax_functionality( $feed );

		// Instance counter's instance.
		$inst = Instance_Counter::get_instance();
		self::add_dynamic_css( $inst );

		// General functionalities.
		$general = General::get_instance();
		self::remove_frontend_data( $general );
		self::create_subscribe_menu( $general );

		// Load icon definitions.
		$icons = Icon_Loader::get_instance();
		self::add_icons_definitions($icons);
	}

	/**
	 * Load front-end scripts and styles.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP front loader instance.
	 */
	public static function load_resources( $instance ) {

		// The script must be loaded before mediaelement-migrate script.
		add_action( 'wp_enqueue_scripts', array( $instance, 'mm_error_fix' ), 9999 );

		// Load other scripts and styles for podcast player.
		add_action( 'wp_footer', array( $instance, 'enqueue_resources' ) );
	}

	/**
	 * Register Media Element player settings and data..
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP front loader instance.
	 */
	public static function register_mejs_settings( $instance ) {
		add_filter( 'podcast_player_mediaelement_settings', array( $instance, 'mejs_settings' ) );
	}

	/**
	 * Resolve anchor feeds give cURL 60 error.
	 *
	 * @since 5.3.0
	 */
	public static function compatibility_hooks() {
		add_filter(
			'http_request_args',
			function( $args, $url ) {
				if ( strpos( $url, 'anchor.fm' ) ) {
					$args['sslverify'] = false;
				}
				return $args;
			},
			10,
			2
		);
	}

	/**
	 * Add Elementor preview screen support.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP front loader instance.
	 */
	public static function elementor_support( $instance ) {
		if (
			in_array(
				'elementor/elementor.php',
				apply_filters( 'active_plugins', get_option( 'active_plugins' ) ),
				true
			)
		) {
			add_action(
				'elementor/preview/enqueue_scripts',
				array( $instance, 'enqueue_elementor_resources' )
			);
		}
	}

	/**
	 * Make pp data available to front-end scripts.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP feed instance.
	 */
	public static function register_script_data( $instance ) {
		add_filter( 'podcast_player_script_data', array( $instance, 'scripts_data' ) );
	}

	/**
	 * Support Front-end ajax loading functionality.
	 *
	 * @since 3.3.0
	 *
	 * @param object $instance PP feed instance.
	 */
	public static function support_ajax_functionality( $instance ) {

		// Handle Ajax request made to fetch next set of episodes.
		add_action( 'wp_ajax_pp_fetch_episodes', array( $instance, 'fetch_episodes' ) );
		add_action( 'wp_ajax_nopriv_pp_fetch_episodes', array( $instance, 'fetch_episodes' ) );

		// Handle Ajax request to search specific episodes.
		add_action( 'wp_ajax_pp_search_episodes', array( $instance, 'search_episodes' ) );
		add_action( 'wp_ajax_nopriv_pp_search_episodes', array( $instance, 'search_episodes' ) );

		// Support for Ajax powered WordPress themes.
		$is_ajax = Get_Fn::get_plugin_option( 'is_ajax' );
		if ( 'yes' === $is_ajax ) {
			add_filter( 'podcast_player_has_podcast', '__return_true', 12 );
		}
	}

	/**
	 * Add dynamic CSS to the site footer.
	 *
	 * @since 3.5.0
	 *
	 * @param object $instance Instance Counter's instance.
	 */
	public static function add_dynamic_css( $instance ) {
		add_action( 'wp_head', array( $instance, 'print_header_css' ) );
		add_action( 'wp_footer', array( $instance, 'print_footer_css' ) );
	}

	/**
	 * Register Media Element player settings and data..
	 *
	 * @since 4.5.0
	 *
	 * @param object $instance PP front loader instance.
	 */
	public static function remove_frontend_data( $instance ) {
		if ( 'yes' !== Get_Fn::get_plugin_option( 'hide_data' ) ) {
			return;
		}
		add_filter( 'podcast_player_data_protect', array( $instance, 'data_protect' ) );
		add_filter( 'podcast_player_mask_audio_url', array( $instance, 'mask_audio_url' ) );
	}

	/**
	 * Add icons to the Subscribe Menu.
	 *
	 * @since 5.4.0
	 *
	 * @param object $instance PP front loader instance.
	 */
	public static function create_subscribe_menu( $instance ) {
		add_filter( 'walker_nav_menu_start_el', array( $instance, 'subscribe_menu' ), 10, 4 );
	}

	/**
	 * Add icons definitions.
	 *
	 * @since 6.3.0
	 *
	 * @param object $icons Icon loader instance.
	 */
	public static function add_icons_definitions( $icons ) {
		add_filter( 'wp_footer', array( $icons, 'add_icons' ), 9999 );
		add_filter( 'admin_footer', array( $icons, 'add_icons' ), 9999 );
	}
}
