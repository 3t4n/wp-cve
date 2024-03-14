<?php 
/*
Plugin Name: WPTD Video Popup
Plugin URI: https://plugins.wpthemedevelopers.com/wptd-video-popup
Description: Simple video popup plugin for elementor. You can make video lightbox popup in elementor. YouTube, Vimeo videos are supported. Here we used magnific popup js.
Version: 1.5.1
Author: wpthemedevelopers
Author URI: https://wpthemedevelopers.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'WPTD_EVP_DIR', plugin_dir_path( __FILE__ ) );
define( 'WPTD_EVP_URL', plugin_dir_url( __FILE__ ) );

/*
* Intialize and Sets up the plugin
*/
class WPTD_Elementor_Video_Popup {
	
	private static $_instance = null;
	
	public static $version = '1.5.1';
	
	/**
	* Sets up needed actions/filters for the plug-in to initialize.
	* @since 1.0.0
	* @access public
	* @return void
	*/
	public function __construct() {

		//WPTD video popup setup page
		add_action( 'plugins_loaded', array( $this, 'wptd_elementor_video_popup_setup') );
		
		//WPTD video popup shortcodes
		add_action( 'init', array( $this, 'wptd_elementor_video_init_addons' ), 20 );
		
	}
	
	/**
	* Installs translation text domain
	* @since 1.0.0
	* @access public
	* @return void
	*/
	public function wptd_elementor_video_popup_setup() {
		//Load text domain
		$this->wptd_elementor_video_load_domain();
	}
	
	/**
	 * Load plugin translated strings using text domain
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function wptd_elementor_video_load_domain() {
		load_plugin_textdomain( 'wptd-video-popup', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/');
	}
		
	/**
	* Load required file for addons integration
	* @return void
	*/
	public function wptd_elementor_video_init_addons() {
		//Settings
		require_once ( WPTD_EVP_DIR . 'admin/wptd-settings.php' );
		
		//Addon
		require_once ( WPTD_EVP_DIR . 'inc/class.elementor.settings.php' );
	}
	
	/**
	 * Creates and returns an instance of the class
	 * @since 2.6.8
	 * @access public
	 * return object
	 */
	public static function get_instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

}
WPTD_Elementor_Video_Popup::get_instance();