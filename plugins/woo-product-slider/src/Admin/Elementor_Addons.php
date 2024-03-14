<?php
/**
 * Elementor ShortCode Block functionality for the elemetor edit page.
 *
 * @link       https://shapedplugin.com/
 * @since      2.6.1
 * @package    woo-product-slider.
 * @subpackage woo-product-slider/Admin.
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

namespace ShapedPlugin\WooProductSlider\Admin;

/**
 * Elementor_Addons
 */
class Elementor_Addons {

	/**
	 * Instance
	 *
	 * @since  2.6.1
	 *
	 * @access private
	 * @static
	 *
	 * @var Elementor_Addons The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since  2.6.1
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
	 * @since  2.6.1
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'sp_wpsp_block_enqueue_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'sp_wpsp_block_enqueue_styles' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sp_wpsp_element_block_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since     2.6.1
	 * @return void
	 */
	public function sp_wpsp_element_block_icon() {
		wp_enqueue_style( 'wpsp_element_block_icon', SP_WPS_URL . 'Admin/assets/css/fontello.min.css', array(), SP_WPS_VERSION, 'all' );
	}

	/**
	 * Enqueue the JavaScript for the elementor block preview area.
	 *
	 * @since     2.6.1
	 */
	public function sp_wpsp_block_enqueue_scripts() {
		/**
		* Scripts of the element editor page.
		*/
		wp_enqueue_script( 'sp-wps-swiper-js' );
		wp_enqueue_script( 'sp-wps-scripts' );

	}

	/**
	 * Enqueue the styles for the elementor block preview area.
	 *
	 * @since     2.6.1
	 */
	public function sp_wpsp_block_enqueue_styles() {
		/**
		* Enqueue element editor style for backend.
		*/
		wp_enqueue_style( 'sp-wps-swiper' );
		wp_enqueue_style( 'sp-wps-font-awesome' );
		wp_enqueue_style( 'sp-wps-style' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since  2.6.1
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
	 * @since  2.6.1
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
	 * @since  2.6.1
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register( new ElementorAddons\Shortcode_Widget() );
	}

}

Elementor_Addons::instance();
