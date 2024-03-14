<?php
/**
 * Elementor shortcode block.
 *
 * @since      2.2.5
 * @package   WP_Team_Free
 * @subpackage Team_Free/src/Admin
 */

namespace ShapedPlugin\WPTeam\Admin;

/**
 * Team_Element_Shortcode_Block_Deprecated
 */
class Team_Element_Shortcode_Block_Deprecated {
	/**
	 * Instance
	 *
	 * @since 2.2.1
	 *
	 * @access private
	 * @static
	 *
	 * @var Team_Element_Shortcode_Block_Deprecated The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 2.2.1
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
	 * @since 2.2.1
	 *
	 * @access public
	 */
	public function __construct() {
		$this->on_plugins_loaded();
		add_action( 'elementor/preview/enqueue_styles', array( $this, 'sptp_block_enqueue_style' ) );
		add_action( 'elementor/preview/enqueue_scripts', array( $this, 'sptp_block_enqueue_scripts' ) );

		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'sptp_element_block_icon' ) );
	}

	/**
	 * Elementor block icon.
	 *
	 * @since    2.2.1
	 * @return void
	 */
	public function sptp_element_block_icon() {
		wp_enqueue_style( 'sptp_element_block_icon', SPT_PLUGIN_ROOT . 'src/Admin/css/fontello.css', array(), SPT_PLUGIN_VERSION, 'all' );
	}

	/**
	 * Register all styles for the elementor block area.
	 *
	 * @since    2.2.10
	 */
	public function sptp_block_enqueue_style() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Team_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Team_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( 'team-free-swiper' );
		wp_enqueue_style( 'team-free-fontawesome' );
		wp_enqueue_style( SPT_PLUGIN_SLUG );
	}

	/**
	 * Register the JavaScript for the elementor block area.
	 *
	 * @since    2.2.1
	 */
	public function sptp_block_enqueue_scripts() {

		/**
		 * An instance of this class should be passed to the run() function
		 * defined in Team_Pro_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Team_Pro_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		wp_enqueue_script( 'team-free-swiper' );
		wp_enqueue_script( SPT_PLUGIN_SLUG );
	}

	/**
	 * On Plugins Loaded
	 *
	 * Checks if Elementor has loaded, and performs some compatibility checks.
	 * If All checks pass, inits the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 2.2.1
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
	 * @since 2.2.1
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
	 * @since 2.2.1
	 *
	 * @access public
	 */
	public function init_widgets() {
		// Register widget.
		\Elementor\Plugin::instance()->widgets_manager->register( new ElementBlock_Deprecated\Shortcode_Widget_Deprecated() );

	}

}

Team_Element_Shortcode_Block_Deprecated::instance();
