<?php
namespace TXElementorAddons;

/**
 * Class Plugin
 *
 * Main Plugin class
 * @since 1.2.0
 */
class Plugin {

	/**
	 * Addon Version
	 *
	 * @since 1.0.0
	 * @var string The addon version.
	 */
	const VERSION = '1.0.0';
	
	/**
	 * Minimum Elementor Version
	 *
	 * @since 1.0.0
	 * @var string Minimum Elementor version required to run the addon.
	 */
	const MINIMUM_ELEMENTOR_VERSION = '3.7.0';

	/**
	 * Minimum PHP Version
	 *
	 * @since 1.0.0
	 * @var string Minimum PHP version required to run the addon.
	 */
	const MINIMUM_PHP_VERSION = '7.3';
	
	

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
	 * Constructor
	 *
	 * Perform some compatibility checks to make sure basic requirements are meet.
	 * If all compatibility checks pass, initialize the functionality.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {

		if ( $this->is_compatible() ) {
			add_action( 'elementor/init', [ $this, 'init' ] );
		}

	}

	/**
	 * Compatibility Checks
	 *
	 * Checks whether the site meets the addon requirement.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function is_compatible() {

		// Check if Elementor installed and activated
		if ( ! did_action( 'elementor/loaded' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
			return false;
		}

		// Check for required Elementor version
		if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
			return false;
		}

		// Check for required PHP version
		if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
			add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
			return false;
		}

		return true;

	}
	
	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have Elementor installed or activated.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_missing_main_plugin() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor */
			esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'elementor-test-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'elementor-test-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-addon' ) . '</strong>'
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}

	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required Elementor version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_elementor_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'elementor-test-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'Elementor', 'elementor-test-addon' ) . '</strong>',
			 self::MINIMUM_ELEMENTOR_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}
	
	/**
	 * Admin notice
	 *
	 * Warning when the site doesn't have a minimum required PHP version.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function admin_notice_minimum_php_version() {

		if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

		$message = sprintf(
			/* translators: 1: Plugin name 2: PHP 3: Required PHP version */
			esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'elementor-test-addon' ),
			'<strong>' . esc_html__( 'Elementor Test Addon', 'elementor-test-addon' ) . '</strong>',
			'<strong>' . esc_html__( 'PHP', 'elementor-test-addon' ) . '</strong>',
			 self::MINIMUM_PHP_VERSION
		);

		printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );

	}	
	
	/**
	 * Create Widgets category
	 *
	 *
	 * @since 1.2.0
	 * @access public
	 */
    public function add_elementor_category()
    {
        \Elementor\Plugin::instance()->elements_manager->add_category( 'templatesnext-addons', array(
            'title' => __( 'TemplatesNext Addons', 'tx' ),
            'icon'  => 'fa fa-plug',
        ), 1 );
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
		wp_register_script( 'elementor-tx-portfolios', plugins_url( '/assets/js/tx-portfolios.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'elementor-tx-slider', plugins_url( '/assets/js/tx-slider.js', __FILE__ ), [ 'jquery' ], false, true );	
		wp_register_script( 'elementor-tx-team', plugins_url( '/assets/js/tx-team.js', __FILE__ ), [ 'jquery' ], false, true );	
		wp_register_script( 'elementor-tx-posts', plugins_url( '/assets/js/tx-posts.js', __FILE__ ), [ 'jquery' ], false, true );
		wp_register_script( 'elementor-tx-testimonials', plugins_url( '/assets/js/tx-testimonials.js', __FILE__ ), [ 'jquery' ], false, true );								
	}

    public function txel_widget_styles() {
        wp_register_style( 'elementor-tx-styles', plugins_url( '/assets/css/txel-addons.css', __FILE__ ), array(), '1.0.1' );
		wp_enqueue_style( 'elementor-tx-styles' );
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
		require_once( __DIR__ . '/widgets/portfolios.php' );
		require_once( __DIR__ . '/widgets/slider.php' );
		require_once( __DIR__ . '/widgets/team.php' );
		require_once( __DIR__ . '/widgets/txposts.php' );
		require_once( __DIR__ . '/widgets/testimonials.php' );			
		if(class_exists('WPCF7')) {
			require_once( __DIR__ . '/widgets/wpcf7.php' );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			require_once( __DIR__ . '/widgets/txwoo.php' );
		}
	}

	/**
	 * Register Widgets
	 *
	 * Register new Elementor widgets.
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function register_widgets( $widgets_manager ) {
		// Its is now safe to include Widgets files
		$this->include_widgets_files();

		// Register Widgets
		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_team() );
		$widgets_manager->register( new Widgets\tx_team() );

		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_portfolio() );
		$widgets_manager->register( new Widgets\tx_portfolio() );

		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_slider() );
		$widgets_manager->register( new Widgets\tx_slider() );

		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_posts() );
		$widgets_manager->register( new Widgets\tx_posts() );

		//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_testimonials() );
		$widgets_manager->register( new Widgets\tx_testimonials() );

		if(class_exists('WPCF7')) {
			//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_wpcf7() );
			$widgets_manager->register( new Widgets\tx_wpcf7() );
		}
		if ( class_exists( 'WooCommerce' ) ) {
			//\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new Widgets\tx_woo() );
			$widgets_manager->register( new Widgets\tx_woo() );
		}
	}

	/**
	 *  Plugin class constructor
	 *
	 * Register plugin action hooks and filters
	 *
	 * @since 1.2.0
	 * @access public
	 */
	public function init() {

		// Register widget scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );

		// Register widgets
		//add_action( 'elementor/widgets/widgets_registered', [ $this, 'register_widgets' ] );
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
		
		// Register styles
		//add_action( 'elementor/widgets/widgets_registered', [ $this, 'txel_widget_styles' ] );
		add_action( 'elementor/widgets/register', [ $this, 'txel_widget_styles' ] );
		
		// Add category
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_elementor_category' ] );			
	}
}

// Instantiate Plugin Class
Plugin::instance();
