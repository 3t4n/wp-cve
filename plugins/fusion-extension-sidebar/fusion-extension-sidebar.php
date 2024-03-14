<?php
/**
 * @package Fusion_Extension_Sidebar
 */
/**
 * Plugin Name: Fusion : Extension - Sidebar
 * Plugin URI: http://www.agencydominion.com/fusion/
 * Description: Sidebar Extension Package for Fusion.
 * Version: 1.1.3
 * Author: Agency Dominion
 * Author URI: http://agencydominion.com
 * Text Domain: fusion-extension-sidebar
 * Domain Path: /languages/
 * License: GPL2
 */
 
/**
 * FusionExtensionSidebar class.
 *
 * Class for initializing an instance of the Fusion Sidebar Extension.
 *
 * @since 1.0.0
 */


class FusionExtensionSidebar	{ 
	public function __construct() {
						
		// Initialize the language files
		add_action('plugins_loaded', array($this, 'load_textdomain'));
		
		// Enqueue front end scripts and styles
		add_action('wp_enqueue_scripts', array($this, 'front_enqueue_scripts_styles'));
		
	}
	
	/**
	 * Load Textdomain
	 *
	 * @since 1.1.3
	 *
	 */
	 
	public function load_textdomain() {
		load_plugin_textdomain( 'fusion-extension-sidebar', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Enqueue JavaScript and CSS on Front End pages.
	 *
	 * @since 1.0.0
	 *
	 */
	 
	public function front_enqueue_scripts_styles() {
		wp_enqueue_style( 'fsn_sidebar', plugin_dir_url( __FILE__ ) . 'includes/css/fusion-extension-sidebar.css', false, '1.0.0' );
	}
	
}

$fsn_extension_sidebar = new FusionExtensionSidebar();

//EXTENSIONS

//sidebar
require_once('includes/extensions/sidebar.php');

?>