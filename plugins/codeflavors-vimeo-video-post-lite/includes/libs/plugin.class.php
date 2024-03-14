<?php

namespace Vimeotheque;

use Vimeotheque\Admin\Admin;
use Vimeotheque\Admin\Customizer\Customizer;
use Vimeotheque\Amp\Amp;
use Vimeotheque\Blocks\Block_Abstract;
use Vimeotheque\Blocks\Blocks_Factory;
use Vimeotheque\Options\Options;
use Vimeotheque\Options\Options_Factory;
use Vimeotheque\Playlist\Theme\Theme;
use Vimeotheque\Playlist\Theme\Themes;
use Vimeotheque\Post\Post_Registration;
use Vimeotheque\Post\Post_Type;
use Vimeotheque\Rest_Api\Rest_Api;
use Vimeotheque\Shortcode\Shortcode_Factory;
use Vimeotheque\Templates\Templates_Init;
use Vimeotheque\Widgets\Widgets_Factory;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Plugin
 * @package Vimeotheque
 */
class Plugin{

	/**
	* Holds the plugin instance.
	*
	* @var Plugin
	*/
	private static $instance = null;
	/**
	 * Stores plugin options
	 *
	 * @var Options
	 */
	private $options;
	/**
	 * Stores player options
	 *
	 * @var Options
	 */
	private $player_options;
	/**
	 * @var Post_Type
	 */
	private $cpt;
	/**
	 * Store admin instance
	 * @var Admin
	 */
	private $admin;
	/**
	 * @var Posts_Import
	 */
	private $posts_import;
	/**
	 * @var Front_End
	 */
	private $front_end;
	/**
	 * @var Blocks_Factory
	 */
	private $blocks_factory;
	/**
	 * @var Themes
	 */
	private $playlist_themes;
	/**
	 * @var Post_Registration
	 */
	private $registered_post_types;
	/**
	 * @var Customizer
	 */
	private $customizer;

	/**
	 * @var Rest_Api
	 */
	private $rest_api;

	/**
	 * Clone.
	 *
	 * Disable class cloning and throw an error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object. Therefore, we don't want the object to be cloned.
	 *
	 * @access public
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
	}

	/**
	 * Wakeup.
	 *
	 * Disable unserializing of the class.
	 *
	 * @access public
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, esc_html__( 'Something went wrong.', 'codeflavors-vimeo-video-post-lite' ), '2.0' );
	}

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the plugin class is loaded or can be loaded.
	 *
	 * @access public
	 * @static
	 *
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			/**
			 * Triggered when Vimeotheque has loaded.
			 * Fires when Vimeotheque was fully loaded and instantiated.
			 *
			 * @since 2.0
			 */
			do_action( 'vimeotheque_loaded' );
		}

		return self::$instance;
	}

	/**
	 * Class constructors - sets all filters and hooks
	 */
	private function __construct(){
		// start the autoloader
		$this->register_autoloader();
		// load dependency files
		$this->load();

		// activation hook to add the rewrite rules for the custom post type
		register_activation_hook( VIMEOTHEQUE_FILE, [
			$this,
			'activation_hook'
		] );

		add_action( 'plugins_loaded', [
			$this,
			'init'
		], 1 );

		// run this admin init on init to have access to earlier hooks
		// priority must be set to run very early so that init hooks set
		// in admin page can also run
		add_action( 'init', [
			$this,
			'admin_init'
		], -9999999 );

		new Amp();
		new Templates_Init();

		add_action(
			'after_setup_theme',
			function(){
				$options = $this->get_options();
				if( $options['enable_templates'] && !current_theme_supports( 'vimeotheque' ) ){
					/**
					 * Support for Video templates.
					 */
					add_theme_support( 'vimeotheque' );

					/**
					 * Support for next video card after the video finishes.
					 */
					add_theme_support( 'vimeotheque-next-video-card' );
				}
			}, 1
		);

		add_filter(
			'vimeotheque\options\get',
			/**
			 * Filter options.
			 *
			 * Filter the plugin options and check if templates are enabled.
			 *
			 * @param array $result             The requested options set.
			 * @param array $all_options        All the plugin options.
			 * @param string $wp_option_name    The WP option name.
			 */
			function( $result, $all_options, $wp_option_name ){

				if( $this->get_options_obj()->get_option_name() == $wp_option_name ){

					if( get_theme_support( 'vimeotheque' ) ){
						$all_options['enable_templates'] = true;
						if( isset( $result['enable_templates'] ) ) {
							$result['enable_templates'] = true;
						}
					}

					if( $all_options['enable_templates'] ) {
						// When templates are enabled, these options will always have the same predefined value.
						$options = [
							'archives'           => false,
							'public'             => true,
							'import_title'       => true,
							'import_description' => 'content',
							'featured_image'      => true
						];

						// Set the options to the predefined value.
						foreach ( $options as $index => $value ) {
							if ( isset( $result[ $index ] ) ) {
								$result[ $index ] = $value;
							}
						}
					}
				}

				return $result;

			}, -999, 3
		);
	}

	public function init(){
		// register the post type
		$this->set_post_type();
		// set the importer
		$this->load_importer();
		// start the front-end
		$this->load_front_end();

		new Shortcode_Factory();
		$this->blocks_factory = new Blocks_Factory( $this );

		new Widgets_Factory( $this );

		$this->playlist_themes = new Themes(
			new Theme(
				VIMEOTHEQUE_PATH . 'themes/default/player.php',
				__( 'Default', 'codeflavors-vimeo-video-post-lite' )
			)
		);

		$this->playlist_themes->register_theme(
			new Theme(
				VIMEOTHEQUE_PATH . 'themes/simple/theme.php',
				__( 'Simple', 'codeflavors-vimeo-video-post-lite' )
			)
		);

		$this->playlist_themes->register_theme(
			new Theme(
				VIMEOTHEQUE_PATH . 'themes/listy/theme.php',
				__( 'Listy', 'codeflavors-vimeo-video-post-lite' )
			)
		);

		// internalization
		load_plugin_textdomain(
			'codeflavors-vimeo-video-post-lite',
			false,
			basename( dirname( VIMEOTHEQUE_FILE ) ) . '/languages/'
		);
	}

	/**
	 * Register the autoloader
	 */
	private function register_autoloader(){
		require VIMEOTHEQUE_PATH . 'includes/libs/autoload.class.php';
		Autoload::run();
	}

	/**
	 * Register the post type
	 */
	private function set_post_type(){
		$this->cpt = new Post_Type( $this );
		add_action( 'init', function(){
			$this->registered_post_types = new Post_Registration(
				$this->cpt->get_wp_post_type_object(),
				$this->cpt->get_category_taxonomy_object(),
				get_taxonomy( $this->cpt->get_tag_tax() )
			);
		}, 2 );
	}

	/**
	 * Loads the automatic importer
	 */
	private function load_importer(){
		/**
		 * Posts importer filter.
		 *
		 * @param Posts_Import $importer The Posts_Import object reference.
		 */
		$this->posts_import = apply_filters(
			'vimeotheque\set_importer',
			new Posts_Import( $this->get_cpt() )
		);
	}

	/**
	 * Loads the front-end
	 */
	private function load_front_end(){
		// start the REST API compatibility
		$this->rest_api = new Rest_Api( $this->get_cpt() );
		// start the front-end functionality
		$this->front_end = new Front_End( $this );
	}

	/**
	 * Set plugin options
	 */
	private function set_plugin_options(){
		$defaults = [
			'enable_templates' => false, // use the video templates for themes
			'public' => true, // post type is public or not
			'archives' => false, // display video embed on archive pages
			'post_slug'	=> 'vimeo-video',
			'taxonomy_slug' => 'vimeo-videos',
			'tag_slug' => 'vimeo-tag',
			'import_tags' => true, // import tags retrieved from Vimeo
			'max_tags' => 3, // how many tags to import
			'import_title' => true, // import titles on custom posts
			'import_description' => 'content', // import descriptions on custom posts
			'import_date' => true, // import video date as post date
			'featured_image' => true, // set thumbnail as featured image; default import on video feed import (takes more time)
			'import_status' => 'publish', // default import status of videos
			// Vimeo oAuth
			'vimeo_consumer_key' => '',
			'vimeo_secret_key' => '',
			'oauth_token' => '' // retrieved from Vimeo; gets set after entering valid client ID and client secret
		];

		/**
		 * Options filter.
		 *
		 * @param array $defaults Default options array.
		 */
		$defaults = apply_filters(
			'vimeotheque\options_default',
			$defaults
		);

		$this->options = Options_Factory::get( '_cvm_plugin_settings', $defaults );
	}

	/**
	 * Set video player options
	 */
	private function set_player_options(){
		$defaults = [
			'title'	=> 1, 	// show video title
			'byline' => 1, 	// show player controls. Values: 0 or 1
			'portrait' => 1, 	// show author image
			'loop' => 0,
			// Autoplay may be blocked in some environments, such as IOS, Chrome 66+, and Safari 11+. In these cases, we’ll revert to standard playback requiring viewers to initiate playback.
			'autoplay' => 0, 	// 0 - on load, player won't play video; 1 - on load player plays video automatically
			'color'		=> '', 	// no color set by default; will use Vimeo's settings
			'dnt' => 0, // block Vimeo player from tracking session data or cookies (1) or allow it (0);
			// extra settings
			'aspect_ratio' => '16x9',
			'width'	=> 900,
			'max_height' => 0, // allows setup of a maximum embed height; must be a value over 50px to work
			'video_position' => 'below-content', // in front-end custom post, where to display the video: above or below post content
			'video_align' => 'align-left', // video alignment
			'lazy_load' => false, // lazy load videos
			'play_icon_color' => '#FF0000', // lazy load play icon color
			'volume' => 45, // video default volume
			// extra player settings controllable by widgets/shortcodes
			'playlist_loop' => 0,
			'aspect_override' => true,
			'start_time' => 0, // time in seconds when playback should start
			'muted' => false, // load video muted
			'background' => false, // load video in background mode (hides controls and mutes video)
			'transparent' => false, // video embed should be with background (false) or without it (true)
		];

		/**
		 * Player options filter.
		 *
		 * @param array $defaults Default options array.
		 */
		$defaults = apply_filters(
			'vimeotheque\player_options_default',
			$defaults
		);

		// get Plugin option
		$this->player_options = Options_Factory::get( '_cvm_player_settings', $defaults );
	}

	/**
	 * Runs on plugin activation and registers rewrite rules
	 * for video custom post type
	 *
	 * @return void
	 */
	public function activation_hook(){
		$this->set_post_type();
		// register custom post
		$this->get_cpt()->register_post();
		// create rewrite ( soft )
		flush_rewrite_rules( false );

		$this->add_admin();

		$wp_option = get_option( $this->get_options_obj()->get_option_name() );
		if( !$wp_option ){
			set_transient( 'vimeotheque_setup_activated' , time(), 30 );
		}
	}

	/**
	 * Returns plugin options array
	 *
	 * @return array
	 */
	public function get_options(){
		$options = $this->get_options_obj();
		return $options->get_options( is_customize_preview() );
	}

	/**
	 * Returns options object
	 *
	 * @return Options
	 */
	public function get_options_obj(){
		if( !$this->options ){
			$this->set_plugin_options();
		}

		return $this->options;
	}

	/**
	 * Returns player options object
	 *
	 * @return Options
	 */
	public function get_embed_options_obj(){
		if( !$this->player_options ){
			$this->set_player_options();
		}

		return $this->player_options;
	}

	/**
	 * @return array
	 */
	public function get_embed_options(){
		$options = $this->get_embed_options_obj();
		return $options->get_options( is_customize_preview() );
	}

	/**
	 * Callback function for hook "admin_init"
	 */
	public function admin_init(){
		$this->add_customizer();
		$this->add_admin();
	}

	/**
	 * Implements WP customizer functionality
	 */
	private function add_customizer(){
		if( is_admin() || is_customize_preview() ) {
			$this->customizer = new Customizer();

		}
	}

	/**
	 * Adds plugin administration functionality
	 */
	private function add_admin(){
		if( is_admin() || Helper::is_ajax() ) {
			$this->admin = new Admin( $this->get_cpt() );
		}
	}

	/**
	 * @param bool $cap
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function get_capability( $cap = false ){
		return $this->admin->get_capability( $cap );
	}

	/**
	 * @return array
	 */
	public function get_roles(){
		return $this->admin->get_roles();
	}

	/**
	 * @return Post_Type
	 */
	public function get_cpt(){
		return $this->cpt;
	}

	/**
	 * @return Posts_Import
	 */
	public function get_posts_importer(){
		return $this->posts_import;
	}

	/**
	 * Get the admin.
	 *
	 * Returns the Admin object that allows access to various admin sections like the menu
	 * or custom post type definitions.
	 *
	 * @return Admin
	 */
	public function get_admin(){
		if( is_null( $this->admin ) ){
			$this->add_admin();
		}
		return $this->admin;
	}

	/**
	 * @return Front_End
	 */
	public function get_front_end(){
		return $this->front_end;
	}

	/**
	 * @return Themes
	 */
	public function get_playlist_themes() {
		return $this->playlist_themes;
	}

	/**
	 * @param string $key - string key for the block
	 *
	 * @return Block_Abstract - returns the registered block
	 * @see Blocks_Factory::register_blocks() for all keys
	 */
	public function get_block( $key ) {
		return $this->blocks_factory->get_block( $key );
	}

	/**
	 * @return Blocks_Factory
	 */
	public function get_blocks(){
		return $this->blocks_factory;
	}

	/**
	 * @return Post_Registration
	 */
	public function get_registered_post_types(){
		return $this->registered_post_types;
	}

	/**
	 * @return Customizer
	 */
	public function get_customizer() {
		return $this->customizer;
	}

	/**
	 * @return Rest_Api
	 */
	public function get_rest_api() {
		return $this->rest_api;
	}

	/**
	 * Load dependencies
	 * @return void
	 */
	private function load(){
		add_action( 'vimeotheque_pro_loaded', function(){
			include_once VIMEOTHEQUE_PATH . 'includes/deprecated.php';
		} );
	}
}

Plugin::instance();