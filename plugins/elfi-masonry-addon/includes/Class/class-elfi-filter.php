<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Elfi Masonry Addon
 * @author     BakshiWp <sharabindu.bakshi@gmail.com>
 */
final class Elfi_Light_Filter {

	/**
	 * Plugin Version
	 *
	 * @since 1.4.0
	 *
	 * @var string The plugin version.
	 */
	const VERSION = '1.4.0';

	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.4.0
	 *
	 * @var string Minimum Elementor version required to run the plugin.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '2.0.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.4.0
	 *
	 * @var string Minimum PHP version required to run the plugin.
	 */
	const MINIMUM_PHP_VERSION = '5.7';

	/**
	 * Instance
	 *
	 * @since 1.4.0
	 *
	 * @access private
	 * @static
	 *
	 * @var Elfi_Masonry_Filter The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Instance
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 * @static
	 *
	 * @return Elfi_Masonry_Filter An instance of the class.
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
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'init', [ $this, 'i18n' ] );
		add_action( 'plugins_loaded', [ $this, 'init' ] );

	}

	/**
	 * Load Textdomain
	 *
	 * Load plugin localization files.
	 *
	 * Fired by `init` action hook.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function i18n() {

		load_plugin_textdomain('elfi-masonry-addon',false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}
	/**
	 * Initialize the plugin
	 *
	 * Load the plugin only after Elementor (and other plugins) are loaded.
	 * Checks for basic plugin requirements, if one check fail don't continue,
	 * if all check have passed load the files required to run the plugin.
	 *
	 * Fired by `plugins_loaded` action hook.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function init() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return;
		}
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'admin_styles' ] );

		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );

	}
	public function admin_styles() {

		wp_enqueue_style( 'elfi-admin-css', ELFI_URL_LIGHT . '/assets/css/elfi-admin.css',array(),ELFI_VERSION_LIGHT,false );

	}



	public function widget_styles() {


		wp_enqueue_style('elfi-masonry', ELFI_URL_LIGHT . '/assets/css/elfi-masonry-addon.css', array() , ELFI_VERSION_LIGHT, false);

	

		wp_register_script('isotope-pkgd', ELFI_URL_LIGHT . '/assets/js/isotope.pkgd.min.js', array(
		    'jquery'
		) ,ELFI_VERSION_LIGHT, true);

		wp_register_script( 'elfi-masonry-addon', ELFI_URL_LIGHT . '/assets/js/elfi-masonry-addon.js',array('jquery','isotope-pkgd','imagesloaded'),ELFI_VERSION_LIGHT ,true);	

		wp_register_script( 'elfi-masonry-gallery', ELFI_URL_LIGHT . '/assets/js/elfi-masonry-gallery.js',array('jquery','isotope-pkgd','imagesloaded'),ELFI_VERSION_LIGHT ,true);	

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elfi-masonry-addon' ),
			'<strong>' . esc_html__( 'Elfi Masonry Filter', 'elfi-masonry-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elfi-masonry-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elfi-masonry-addon' ),
			'<strong>' . esc_html__( 'Elfi Masonry Filter', 'elfi-masonry-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elfi-masonry-addon' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elfi' ),
			'<strong>' . esc_html__( 'Elfi Masonry Filter', 'elfi' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elfi' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Init Widgets
	 *
	 * Include widgets files and register them
	 *
	 * @since 1.4.0
	 *
	 * @access public
	 */
	public function init_widgets() {

		// Include Widget files

		require_once ELFI_PATH_LIGHT. 'includes/Elements/elfi-masonry-widget.php';
		require_once ELFI_PATH_LIGHT. 'includes/Elements/elfi-masonry-gallery-widget.php';

		// Register widget
	
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elfi_Light_Masonry_Widget() );	
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elfi_Light_Masonry_Gallery() );	

	}


}

Elfi_Light_Filter::instance();
