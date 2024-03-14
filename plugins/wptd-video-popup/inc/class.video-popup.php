<?php
/**
 * WPTD Video Popup Shortcode Class
 * The main class that initiates and runs the plugin. 
 * @since 1.0.0
 */
final class Elementor_Video_Popup_Shortcode {
	
	/**
	 * Instance
	 * @since 1.0.0
	 * @access private
	 * @static
	 * @var Shortcode The single instance of the class.
	 */
	private static $_instance = null;

	/**
	 * Constructor
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		
		$this->init();

	}
	
	public function init() {
			
		// Create Catgeory
		$this->create_wptd_elementor_category();
		
		// Register Elementor Widgets
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
		
		// Register Widget Scripts
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
		
		// Include WP Video Popup Shortcode
		$this->init_shortcode();
	}
	
	/**
     * Register plugin shortcode category
	 * @since 2.6.8
	 * @access public
	 * @return void
	 */
	public function create_wptd_elementor_category() {
	   \Elementor\Plugin::instance()->elements_manager->add_category(
			'wptd',
			array(
				'title' => esc_html__( 'WPTD', 'wptd-video-popup' )
			),
		1);
	}
	
	/**
	 * Widget Scripts
	 * Include widgets scripts
	 * @since 1.0.0
	 * @access public
	 */
	public function widget_scripts() {
		wp_register_style( 'magnific-popup', WPTD_EVP_URL .'assets/css/magnific-popup.min.css', array(), '1.1.0', 'all');
		wp_register_style( 'wptd-video-popup', WPTD_EVP_URL .'assets/css/wptd-video-popup.css', array(), '1.0', 'all');
		wp_register_script( 'magnific-popup', WPTD_EVP_URL . 'assets/js/jquery.magnific.popup.min.js',  array( 'jquery' ), '1.1.0', true );
		wp_register_script( 'wptd-video-popup', WPTD_EVP_URL . 'assets/js/wptd-video-popup.js',  array( 'jquery' ), '1.0', true );
	}
	
	/**
	 * Init Widgets
	 * Include widgets files and register them
	 * @since 1.0.0
	 * @access public
	 */
	public function init_widgets() {
		// Connect Widget File
		require_once( WPTD_EVP_DIR . 'widgets/video-popup-elementor.php' );
		
		//Call Widget Class
		\Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \Elementor_Video_Popup_Widget() );
	}
	
	/**
	 * Include WP Shortcode
	 * Include default wp video popup shortcode 
	 * @since 1.0.0
	 * @access public
	 */
	public function init_shortcode() {
		// Connect Shortcode File
		require_once( WPTD_EVP_DIR . 'widgets/video-popup-wp-shortcodes.php' );
	}
	
	public static function get_random_series(){
		static $eptd_evp_rand = 1;
		return $eptd_evp_rand++;
	}
	
	/**
	 * Creates and returns an instance of the class
	 * @since 2.6.8
	 * @access public
	 * return object
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	
	
} 
Elementor_Video_Popup_Shortcode::instance();