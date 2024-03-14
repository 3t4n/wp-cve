<?php
/**
 * Plugin Name:			BEW Menu Cart
 * Plugin URI:			https://briefcasewp.com/BEW-menu-cart
 * Description:			Add Woocommerce and EDD Menu Cart Widget to the popular free page builder Elementor.
 * Version:				1.0.3
 * Author:				BriefcaseWP
 * Author URI:			https://briefcasewp.com
 * Requires at least:	4.9.0
 * Tested up to:		4.9.4
 *
 * Text Domain: BEW-menu-cart-lite
 * Domain Path: /languages/
 *
 * @package BEW-menu-cart-lite
 * @category Core
 * @author BriefcaseWP
 */


// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Returns the main instance of BEW_menu_cart_lite to prevent the need to use globals.
 *
 * @since  1.0.0
 * @return object BEW_menu_cart_lite
 */
function BEW_menu_cart_lite() {
	return BEW_menu_cart_lite::instance();
} // End BEW_menu_cart_lite()

BEW_menu_cart_lite();

/**
 * Main BEW_menu_cart_lite Class
 *
 * @class BEW_menu_cart_lite
 * @version	1.0.0
 * @since 1.0.0
 * @package	BEW_menu_cart_lite
 */
final class BEW_menu_cart_lite {
	/**
	 * BEW_menu_cart_lite The single instance of BEW_menu_cart_lite.
	 * @var 	object
	 * @access  private
	 * @since 	1.0.0
	 */
	private static $_instance = null;

	/**
	 * The token.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $token;

	/**
	 * The version number.
	 * @var     string
	 * @access  public
	 * @since   1.0.0
	 */
	public $version;

	// Admin - Start
	/**
	 * The admin object.
	 * @var     object
	 * @access  public
	 * @since   1.0.0
	 */
	public $admin;	
	 
	public function __construct() {
		$this->token 			= 'BEW-menu-cart-lite';
		$this->plugin_url 		= plugin_dir_url( __FILE__ );
		$this->plugin_path 		= plugin_dir_path( __FILE__ );
		$this->version 			= '1.0.3';
		
		add_action( 'init', array( $this, 'bew_load_plugin_textdomain' ) );
		add_action( 'init', array( $this, 'bew_setup' ) );
		
		// Add new category for Elementor
		add_action( 'elementor/init', array( $this, 'elementor_init' ), 1 );
		
		// Add the action here so that the widgets are always visible
		add_action( 'elementor/widgets/widgets_registered', array( $this, 'bew_widgets_registered' ) );	
		
	}

	/**
	 * Main BEW_menu_cart_lite Instance
	 *
	 * Ensures only one instance of BEW_menu_cart_lite is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @static
	 * @see BEW_menu_cart_lite()
	 * @return Main BEW_menu_cart_lite instance
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) )
			self::$_instance = new self();
		return self::$_instance;
	} // End instance()

	/**
	 * Load the localisation file.
	 * @access  public
	 * @since   1.0.0
	 * @return  void
	 */
	public function bew_load_plugin_textdomain() {
		load_plugin_textdomain( 'BEW-menu-cart-lite', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	/**
	 * Cloning is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __clone() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}

	/**
	 * Unserializing instances of this class is forbidden.
	 *
	 * @since 1.0.0
	 */
	public function __wakeup() {
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?' ), '1.0.0' );
	}
	
	/**
	 * Log the plugin version number.
	 * @access  private
	 * @since   1.0.0
	 * @return  void
	 */
	private function _log_version_number() {
		// Log the version number.
		update_option( $this->token . '-version', $this->version );
	}

	/**
	 * Setup all the things.
	 * executes for all themes.
	 * @return void
	 */
	public function bew_setup() {		
											
			add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_scripts' ) );
			add_action( 'elementor/frontend/after_register_styles', array( $this, 'bew_styles' ) );
			
			require_once( $this->plugin_path .'/includes/woo-config.php' );
	}
	
	/**
	 * Add new category for Elementor.
	 *
	 * @since 1.0.0
	 */
	public function elementor_init() {

		
		$elementor = \Elementor\Plugin::$instance;

		// Add element category in panel
		$elementor->elements_manager->add_category(
			'briefcasewp-elements-lite',
			[
				'title' => 'Briefcasewp' . ' ' . __( 'Elements Lite', 'briefcase-elementor-widgets' ),
				'icon' => 'font',
			],
			1
		);
	}

	/**
	 * Enqueue scripts.
	 *
	 * @since 1.0.0
	 */
	 
	public function bew_scripts() {

		// Load custom js methods						
		wp_register_script( 'woocart-script', plugins_url( '/assets/js/woocart-script.js', __FILE__ ), [ 'jquery'], false, true );
	}

	/**
	 * Enqueue styles.
	 *
	 * @since 1.0.0
	 */
	public function bew_styles() {
		
	// Load font awesome style
		wp_enqueue_style( 'font-awesome', plugins_url( '/assets/css/third/font-awesome.min.css', __FILE__ ), array());	
	
	// Load main stylesheet
	if( function_exists( 'EDD' )) { 
		if ( edd_get_option( 'disable_styles', false ) ) {
		wp_enqueue_style( 'bew-edd-style-o', plugins_url( '/assets/css/edd.min.css', __FILE__ ), array());
		wp_enqueue_style( 'bew-edd-style', plugins_url( '/assets/css/bew-edd.css', __FILE__ ), array());
		}	
		else {
		wp_enqueue_style( 'bew-edd-style', plugins_url( '/assets/css/bew-edd.css', __FILE__ ), array( 'edd-styles' ));
		}		
	}
		
	// Load WooCommerce CSS
	if( class_exists( 'WooCommerce' ) ) { 
		
		$current_theme = wp_get_theme();
		
		switch ( $current_theme ) {
			case 'OceanWP':	
			// Load OceanWP CSS compatibility			
			wp_enqueue_style( 'bew-woocommerce-owp', plugins_url( '/assets/css/bew-woocommerce-owp.css', __FILE__ ), array('oceanwp-woocommerce'));		
			break;
			case 'Astra':	
			// Load Astra CSS compatibility	
			wp_enqueue_style( 'bew-woocommerce', plugins_url( '/assets/css/bew-woocommerce.css', __FILE__ ), array('woocommerce-layout', 'woocommerce-smallscreen', 'woocommerce-general'));		
			break;
			default:
			wp_enqueue_style( 'bew-woocommerce', plugins_url( '/assets/css/bew-woocommerce.css', __FILE__ ), array());	
			  
			}
		
	}
		
	}

		
	/**
	 * Register the widgets
	 *
	 * @since 1.0.0
	 */
	public function bew_widgets_registered() {

		// We check if the Elementor plugin has been installed / activated.
		if ( defined( 'ELEMENTOR_PATH' ) && class_exists( 'Elementor\Widget_Base' ) ) {

			// Define dir
			$dir = $this->plugin_path .'widgets/';

			// Array of new widgets
			$build_widgets = apply_filters( 'bew_widgets', array(				
						
				'edd_menu_cart-lite' 				=> $dir .'edd_menu_cart_lite.php',				
				'woo_menu_cart-lite' 				=> $dir .'woo_menu_cart_lite.php',
			) );

			// Load files
			foreach ( $build_widgets as $widget_filename ) {
				include $widget_filename;
			}

		}

	}
	
} // End Class