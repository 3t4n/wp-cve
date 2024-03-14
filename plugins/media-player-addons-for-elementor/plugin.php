<?php
namespace BMianAddon;

use BMianAddon\Widgets\b_html5_addon;
use BMianAddon\Widgets\b_html5_audio;
use BMianAddon\Widgets\b_youtube_vedio;
use BMianAddon\Widgets\b_vemio_vedio;
use BMianAddon\Widgets\b_art_addon;
use BMianAddon\Widgets\Bplayer;
use BMianAddon\Widgets\d_player;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class b_Addon {

	/**
	 * Instance
	 *
	 * @since 1.2.0
	 * @access private
	 * @static
	 *
	 * @var Plugin The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.2.0
	 * @access public
	 *
	 * @return Plugin An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	/**
	 * widget_scripts
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_scripts() {

		 wp_enqueue_script('jquery');
		
         wp_register_script( 'plyr-js', plugins_url( '/assets/js/plyr.js', __FILE__ ), [ 'jquery' ], false, true );
		 wp_register_script( 'artplayer-js', plugins_url( '/assets/js/artplayer.js', __FILE__ ), [ 'jquery' ], false, true );
		 wp_register_script( 'danmuku-js', plugins_url( '/assets/js/artplayer-plugin-danmuku.js', __FILE__ ), [ 'jquery' ], false, true );


		wp_register_script( 'dplayermin-js', plugins_url( '/assets/js/dplayer.min.js', __FILE__ ), [ 'jquery' ], false, true );

		
		wp_register_script( 'bplayer-playlist', plugins_url( '/assets/js/main-scripts-playlist.js', __FILE__ ), [ 'jquery'], false, true );
		wp_register_script( 'bplayer-main', plugins_url( '/assets/js/main-scripts.js', __FILE__ ), [ 'jquery'], false, true );
		wp_register_script( 'bplayer-script', plugins_url( '/assets/js/bplayer.min.js', __FILE__ ), [ 'jquery'], false, true );

		wp_register_script( 'main-js', plugins_url( '/assets/js/main.js', __FILE__ ), ['jquery'], false, true );


	}
	
	/**
	 * widget_styles
	 *
	 * Load required plugin core files.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function widget_styles(){

		wp_register_style("ua-plyry",plugins_url("/assets/css/plyr.css",__FILE__));
		wp_enqueue_style( 'ua-plyry' );
		wp_register_style("ua-plyr-css",plugins_url("/assets/css/styler.css",__FILE__));
		wp_enqueue_style( 'ua-plyr-css' );

	}


	/**
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {

		require_once( __DIR__ . '/widgets/b_html5_addon.php' );
		require_once( __DIR__ . '/widgets/b-html5-audio.php' );
		require_once( __DIR__ . '/widgets/b-youtube-vedio-player.php' );
		require_once( __DIR__ . '/widgets/b-vemio-vedio-player.php' );
		require_once( __DIR__ . '/widgets/b-artplayer.php' );
		require_once( __DIR__ . '/widgets/d_player.php' );
		//for bplayer
		require_once( __DIR__ . '/widgets/bplayer-widget-audio.php' );
		require_once( __DIR__ . '/widgets/bplayer-widget-playlist-audio.php' );
		require_once( __DIR__ . '/widgets/bplayer-widget-playlist-video.php' );
		require_once( __DIR__ . '/widgets/bplayer-widget-video.php' );

	}
	//editor scripts
	function editor_scripts() {
		wp_register_style("ua-aa",plugins_url("/assets/css/style.css",__FILE__));
		wp_enqueue_style( 'ua-aa' );
	}
	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets() {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();
		// Register Widgets

		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\b_html5_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\b_html5_audio() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\b_youtube_vedio() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\b_vemio_vedio() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\b_art_addon() );
		//for bplayer
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Bplayer() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Bplayer_Playlist() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Bplayer_Video() );
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\Bplayer_Video_Playlist() );
		//dplayer
		\Elementor\Plugin::instance()->widgets_manager->register( new Widgets\d_player() );

	}
	
	//category registered
	public function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'baddon',
			[
				'title' => __('Media Player For Elementor', 'baddon' ),
				'icon' => 'fa fa-plug',
			]
		);
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function __construct() {

		// Enqueue widget styles
        add_action( 'elementor/frontend/after_register_styles', [ $this, 'widget_styles' ] , 100 );
        add_action( 'admin_enqueue_scripts', [ $this, 'widget_styles' ] , 100 );

		// Enqueue widget scripts
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ], 100 );
        add_action( 'admin_enqueue_scripts', [ $this, 'widget_scripts' ] , 100 );

		// Register widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );

		//category registered
		add_action( 'elementor/elements/categories_registered',  [ $this,'add_elementor_widget_categories' ]);
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );
	}

}
b_Addon::instance();


