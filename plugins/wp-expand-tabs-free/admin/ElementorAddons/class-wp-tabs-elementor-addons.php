<?php
/**
 * The plugin elementor addons.
 *
 * @link       https://shapedplugin.com/
 * @since      2.1.10
 *
 * @package    WP_Tabs
 * @subpackage WP_Tabs/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Elementor shortcode addon.
 */
class WP_Tabs_Elementor_Addons {
	/**
	 * Instance
	 *
	 * @since 2.1.10
	 *
	 * @access private
	 * @static
	 *
	 * @var WP_Tabs_Elementor_Addons The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.1.10
	 *
	 * @access public
	 * @static
	 *
	 * @return Elementor_Test_Extension An instance of the class.
	 */
	public static function instance() {

		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;

	}

	/**
	 * Constructor
	 *
	 * @since 2.1.10
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'wp_tabs_enqueue_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'wp_tabs_enqueue_styles' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sp_tabs_addons_icon' ) );
	}

	/**
	 * Register the JavaScript files for the elementor block area.
	 *
	 * @since    2.1.10
	 */
	public function wp_tabs_enqueue_scripts() {
		// Js file enqueue.
		wp_enqueue_script( 'sptpro-tab' );
		wp_enqueue_script( 'sptpro-collapse' );
		wp_enqueue_script( 'sptpro-script' );
	}

	/**
	 * Register the CSS files for the elementor block area.
	 *
	 * @since   2.1.14
	 */
	public function wp_tabs_enqueue_styles() {
		// Style file enqueue.
		wp_enqueue_style( 'sptpro-accordion-style' );
		wp_enqueue_style( 'sptpro-style' );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    2.1.10
	 * @return void
	 */
	public function sp_tabs_addons_icon() {
		wp_enqueue_style( 'sp_tabs_elementor_addons_icon', WP_TABS_URL . 'admin/css/elementor-icon.css', array(), WP_TABS_VERSION, 'all' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.1.10
	 *
	 * @access public
	 */
	public function on_plugins_loaded() {
		add_action( 'elementor/init', array( $this, 'init' ) );
	}

	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.1.10
	 *
	 * @access public
	 */
	public function init() {
		// Add Plugin actions.
		add_action( 'elementor/widgets/register', array( $this, 'init_widgets' ) );
	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 2.1.10
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		require_once __DIR__ . '/widgets/shortcode.php';

		\Elementor\Plugin::instance()->widgets_manager->register( new WP_Tabs_Free_Eelementor_Shortcode_Widget() );

	}

}

WP_Tabs_Elementor_Addons::instance();
