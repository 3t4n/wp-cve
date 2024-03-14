<?php
/*
Plugin Name: The Plus Visual Composer Addons
Plugin URI: http://theplus.sagar-patel.com/
Description: Collection of most beautiful and modern Visual composer addons made by POSIMYTH Themes.
Version: 2.0.0
Author: Posimyth Themes
Author URI: http://posimyththemes.com
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define('THEPLUS_PLUGIN_URL',plugins_url().'/the-plus-visual-composer-addons/');
define('THEPLUS_PLUGIN_PATH',plugin_dir_path(__FILE__));
 defined( 'VERSION_THEPLUS' ) or define( 'VERSION_THEPLUS', '2.0.0' );
 

class ThePlus_addon {
	/**
	 * Core singleton class
	 * @var self - pattern realization
	 */
	private static $_instance;
	
	/**
	 * Get the instane of ThePlus_addon
	 *
	 * @return self
	 */
	public static function getInstance() {
		if ( ! ( self::$_instance instanceof self ) ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}
	public function __construct() {
		
		if ( class_exists( 'Vc_Manager', false ) ) {
			add_filter('plugin_action_links',array($this,  'pluginActionLinks'), 10, 2);
			add_action('plugins_loaded', array($this, 'pluginsLoaded'), 10);
			add_action( 'admin_enqueue_scripts', array( $this,'pt_theplus_admin_css') );
			add_action( 'wp_enqueue_scripts', array( $this,'pt_theplus_js_css') );
			add_filter('upload_mimes', array( $this,'pt_theplus_mime_types'));
			add_action('after_setup_theme', array($this, 'addVcElementsAddon'));
		}else{
			add_action('admin_notices', array($this, '_admin_notice__error'));
		}
		}
	
	/**
	 * Cloning disabled
	 */
	public function __clone() {
	}

	/**
		* Serialization disabled
	 */
	public function __sleep() {
	}

	/**
	 * De-serialization disabled
	 */
	public function __wakeup() {
	}
	
	function pluginsLoaded() {
		load_plugin_textdomain( 'pt_theplus', false, basename( dirname( __FILE__ ) ) . '/lang' ); 
	}
	
	public function addVcElementsAddon() {
		if ( class_exists( 'Vc_Manager', false ) ) {
			require_once(THEPLUS_PLUGIN_PATH.'vc_elements/vc_addon.php');			
		}
		require_once THEPLUS_PLUGIN_PATH.'post-type/tinymce/theme-shortcode.php';
		if ( ! class_exists( 'cmb_Meta_Box' ) ){
			require_once(THEPLUS_PLUGIN_PATH.'vc_elements/theplus_options.php');
		}
		if ( file_exists(THEPLUS_PLUGIN_PATH.'post-type/metabox/init.php' ) ) {
			require_once THEPLUS_PLUGIN_PATH . 'post-type/includes.php';
		}
	}
	
	/**
	 * Add Settings link in plugin's page
	 * @since 2.0.0
	 *
	 * @param $links
	 * @param $file
	 *
	 * @return array
	 */
	 public function pluginActionLinks( $links, $file ) {
	if ( plugin_basename( THEPLUS_PLUGIN_PATH.'theplus_addon.php' )== $file ) {
		$html = esc_html__( 'Settings', 'pt_theplus' );
			$title = __( 'Settings ThePlus Options', 'pt_theplus' );
			$link = '<a href="admin.php?page=theplus_options" title="' . esc_attr( $title ) . '">'.esc_html('Settings','pt_theplus').'</a>';
       
			array_unshift( $links, $link ); 
	}
		return $links;
	}
	
	public function pt_theplus_js_css() {
	
		wp_enqueue_style( 'pt_theplus-style',THEPLUS_PLUGIN_URL .'/vc_elements/css/main/theplus_style.css');
		wp_enqueue_style( 'fontawasome-fonts',THEPLUS_PLUGIN_URL .'/vc_elements/css/extra/font-awesome.min.css');
		wp_enqueue_style( 'lity_css', THEPLUS_PLUGIN_URL .'vc_elements/css/extra/lity.css'); //Lity css Pop-up
		wp_enqueue_style( 'theme_compatibility-style',THEPLUS_PLUGIN_URL .'/vc_elements/css/main/theme_compatibility.css');
		
		wp_enqueue_script("jquery-effects-core");
		wp_enqueue_script( 'waypoints-js', THEPLUS_PLUGIN_URL .'/vc_elements/js/extra/jquery.waypoints.js');// waypoint js
		wp_enqueue_script( 'circle-progress', THEPLUS_PLUGIN_URL .'/vc_elements/js/extra/circle-progress.js',array(),'', false ); //circle-progress js
		wp_enqueue_script( 'vivus_js', THEPLUS_PLUGIN_URL .'/vc_elements/js/extra/vivus.min.js');//svg draw js 
		wp_enqueue_script( 'downCount-js', THEPLUS_PLUGIN_URL .'/vc_elements/js/extra/jquery.downCount.js',array(),'', true );// countdown js
		wp_enqueue_script( 'lity-js', THEPLUS_PLUGIN_URL .'/vc_elements/js/extra/lity.min.js',array(),'', true );// popup js
		wp_enqueue_script( 'pt-theplus-custom', THEPLUS_PLUGIN_URL .'/vc_elements/js/main/pt-theplus-custom.js',array('jquery'),VERSION_THEPLUS, false);
	}

	function pt_theplus_admin_css() {   
		wp_enqueue_style( 'pt-theplus-admin', THEPLUS_PLUGIN_URL .'/vc_elements/css/admin/pt-theplus-admin.css', array() );
		wp_enqueue_script( 'pt_plus-admin-js', THEPLUS_PLUGIN_URL .'/vc_elements/js/admin/pt-theplus-vc.js',array(),'', false );
	}
	function pt_theplus_mime_types($mimes) {
		$mimes['svg'] = 'image/svg+xml';
		$mimes['svgz'] = 'image/svg+xml';
	  return $mimes;
	}
	
	/*
	 * Admin notice text
	 */
	public function _admin_notice__error() {
		echo '<div class="notice notice-error is-dismissible">';
			echo '<p>'. esc_html__( ' The Plus Ultimate addon is enabled but not effective. It requires Visual Composer Plugins.', 'pt_theplus' ) .'</p>';
		echo '</div>';
	}
}
$ThePlus_addon = new ThePlus_addon();