<?php
/**
 * The plugin shortcode block.
 *
 * @link       https://shapedplugin.com/
 * @since      1.4.4
 *
 * @package    Woo_Category_Slider
 * @subpackage Woo_Category_Slider/admin
 * @author     ShapedPlugin <support@shapedplugin.com>
 */

/**
 * Elementor shortcode block.
 */
class Woo_Category_Slider_Free_Element_Shortcode_Addons {
	/**
	 * Instance
	 *
	 * @since 1.4.4
	 *
	 * @access private
	 * @static
	 *
	 * @var Woo_Category_Slider_Free_Element_Shortcode_Addons The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Script and style suffix
	 *
	 * @since 1.4.4
	 * @access protected
	 * @var string
	 */
	protected $suffix;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.4.4
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
	 * @since 1.4.4
	 *
	 * @access public
	 */
	public function __construct() {
		$this->suffix = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) || ( defined( 'WP_DEBUG' ) && WP_DEBUG ) ? '' : '.min';
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'wcs_addons_enqueue_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'wcs_addons_enqueue_styles' ) );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'woo_category_slider_free_addons_icon' ) );

	}

	/**
	 * Elementor block icon.
	 *
	 * @since    1.4.4
	 * @return void
	 */
	public function woo_category_slider_free_addons_icon() {
		wp_enqueue_style( 'woo_category_slider_free_elementor_addons_icon', SP_WCS_URL . 'admin/css/fontello.min.css', array(), SP_WCS_VERSION, 'all' );
	}

	/**
	 * Enqueue the style for the elementor block area.
	 *
	 * @since   1.4.4
	 */
	public function wcs_addons_enqueue_styles() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Category_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Category_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_style( 'sp-wcs-swiper' );
		wp_enqueue_style( 'sp-wcs-font-awesome' );
		wp_enqueue_style( 'woo-category-slider-grid' );
	}

	/**
	 * Enqueue the JavaScript for the elementor block area.
	 *
	 * @since   1.4.4
	 */
	public function wcs_addons_enqueue_scripts() {
		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Woo_Category_Slider_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Woo_Category_Slider_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'sp-wcs-swiper-js' );
		wp_enqueue_script( 'sp-wcs-preloader' );
		wp_enqueue_script( 'sp-wcs-swiper-config' );
		wp_enqueue_script( 'woo-category-slider-grid-admin-js' );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.4.4
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
	 * @since 1.4.4
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
	 * @since 1.4.4
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		require_once SP_WCS_PATH . 'admin/ElementAddons/Sp_Category_Shortcode_Widget.php';
		\Elementor\Plugin::instance()->widgets_manager->register( new Sp_Category_Shortcode_Widget() );
	}

}

Woo_Category_Slider_Free_Element_Shortcode_Addons::instance();
