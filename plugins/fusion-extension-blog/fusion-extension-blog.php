<?php
/**
 * @package Fusion_Extension_Blog
 */
/**
 * Plugin Name: Fusion : Extension - Blog
 * Plugin URI: http://www.agencydominion.com/fusion/
 * Description: Blog Extension Package for Fusion.
 * Version: 1.1.3
 * Author: Agency Dominion
 * Author URI: http://agencydominion.com
 * Text Domain: fusion-extension-blog
 * Domain Path: /languages/
 * License: GPL2
 */
 
/**
 * FusionExtensionBlog class.
 *
 * Class for initializing an instance of the Fusion Blog Extension.
 *
 * @since 1.0.0
 */


class FusionExtensionBlog	{ 
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
		load_plugin_textdomain( 'fusion-extension-blog', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	
	/**
	 * Enqueue JavaScript and CSS on Front End pages.
	 *
	 * @since 1.0.0
	 *
	 */
	 
	public function front_enqueue_scripts_styles() {
		wp_enqueue_style( 'fsn_blog', plugin_dir_url( __FILE__ ) . 'includes/css/fusion-extension-blog.css', false, '1.0.0' );
	}
	
}

$fsn_extension_blog = new FusionExtensionBlog();

//EXTENSIONS

//blog
require_once('includes/extensions/blog.php');

?>