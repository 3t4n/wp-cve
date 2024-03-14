<?php
namespace AllEmebdAddon;
use AllEmebdAddon\Widgets\youtube_addon;
use AllEmebdAddon\Widgets\vimeo_addon;
use AllEmebdAddon\Widgets\soundcloud_addon;
use AllEmebdAddon\Widgets\invison_addon;
use AllEmebdAddon\Widgets\jotform_addon;
use AllEmebdAddon\Widgets\google_addon;
use AllEmebdAddon\Widgets\appointly_addon;
use AllEmebdAddon\Widgets\spotify_addon;
use AllEmebdAddon\Widgets\giphy_addon;
use AllEmebdAddon\Widgets\imgur_addon;
use AllEmebdAddon\Widgets\slideshare_addon;
use AllEmebdAddon\Widgets\codepen_addon;
use AllEmebdAddon\Widgets\twitch_addon;
use AllEmebdAddon\Widgets\twitframe_addon;
use AllEmebdAddon\Widgets\bandcamp_addon;
use AllEmebdAddon\Widgets\dailymotion_addon;
use AllEmebdAddon\Widgets\dartfish_addon;
use AllEmebdAddon\Widgets\creddle_addon;
use AllEmebdAddon\Widgets\genial_addon;
use AllEmebdAddon\Widgets\Sirv_addon;
use AllEmebdAddon\Widgets\mixcloud_addon;
use AllEmebdAddon\Widgets\kuula_addon;
use AllEmebdAddon\Widgets\facebook_addon;
use AllEmebdAddon\Widgets\pinterest_addon;
use AllEmebdAddon\Widgets\linkedin_addon;
use AllEmebdAddon\Widgets\reddit_addon;
/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class allembed_Addon {

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
	 * Include Widgets files
	 *
	 * Load widgets files
	 *
	 * @since 1.2.0
	 * @access private
	 */
	private function include_widgets_files() {

		require_once( __DIR__ . '/widgets/youtube_addon.php' );
		require_once( __DIR__ . '/widgets/vimeo_addon.php' );
		require_once( __DIR__ . '/widgets/soundcloud.php' );
		require_once( __DIR__ . '/widgets/invison.php' );
		require_once( __DIR__ . '/widgets/jotform.php' );
		require_once( __DIR__ . '/widgets/google-map.php' );
		require_once( __DIR__ . '/widgets/appointly.php' );
		require_once( __DIR__ . '/widgets/spotify.php' );
		require_once( __DIR__ . '/widgets/giphy.php' );
		require_once( __DIR__ . '/widgets/imgur.php' );
		require_once( __DIR__ . '/widgets/slideshare.php' );
		require_once( __DIR__ . '/widgets/codepen.php' );
		require_once( __DIR__ . '/widgets/twitch.php' );
		require_once( __DIR__ . '/widgets/twitframe.php' );
		require_once( __DIR__ . '/widgets/bandcamp.php' );
		require_once( __DIR__ . '/widgets/dailymotion.php' );
		require_once( __DIR__ . '/widgets/dartfish.php' );
		require_once( __DIR__ . '/widgets/creddle.php' );
		require_once( __DIR__ . '/widgets/genial.php' );
		require_once( __DIR__ . '/widgets/Sirv.php' );
		require_once( __DIR__ . '/widgets/mixcloud.php' );
		require_once( __DIR__ . '/widgets/kuula.php' );
		require_once( __DIR__ . '/widgets/facebook.php' );
		require_once( __DIR__ . '/widgets/pinterest.php' );
		require_once( __DIR__ . '/widgets/linkedin.php' );
		require_once( __DIR__ . '/widgets/reddit.php' );
	
	}

	public function widget_styles(){

		wp_register_style("main-css",plugins_url("/assets/css/styler.css",__FILE__));
		wp_enqueue_style( 'main-css' );

	}


	//editor scripts
	function editor_scripts() {
		wp_register_style("my-style",plugins_url("/assets/css/style.css",__FILE__));
		wp_enqueue_style( 'my-style' );
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

		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\youtube_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\vimeo_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\soundcloud_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\invison_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\jotform_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\google_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\appointly_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\spotify_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\giphy_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\imgur_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\slideshare_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\codepen_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\twitch_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\twitframe_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\bandcamp_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\dailymotion_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\dartfish_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\creddle_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\genial_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\Sirv_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\mixcloud_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\kuula_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\facebook_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\pinterest_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\linkedin_addon() );
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\reddit_addon() );

	}
	
	//category registered
	public function add_elementor_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'AllEmbed',
			[
				'title' => __('All Embed For Elementor', 'allembed' ),
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

		// Register widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );

		//category registered
		add_action( 'elementor/elements/categories_registered',  [ $this,'add_elementor_widget_categories' ]);
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'editor_scripts' ] );
	}

}
allembed_Addon::instance();


