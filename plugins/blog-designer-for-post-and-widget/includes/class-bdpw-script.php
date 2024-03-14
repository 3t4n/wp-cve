<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Blog Designer - Post and Widget
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Bdpw_Script {

	function __construct() {

		// Action to add style && script in backend
		add_action( 'admin_enqueue_scripts', array($this, 'bdpw_admin_style_script') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'bdpw_front_style_script') );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 1.4
	 */
	function bdpw_register_admin_assets() {

		/* Styles */
		wp_register_style( 'bdpw-admin-css', BDPW_URL.'assets/css/bdpw-admin.css', array(), BDPW_VERSION );

		/* Scripts */
		wp_register_script( 'bdpw-admin-js', BDPW_URL.'assets/js/bdpw-admin.js', array('jquery'), BDPW_VERSION, true );
	}

	/**
	 * Enqueue admin script
	 * 
	 * @since 2.1
	 */
	function bdpw_admin_style_script( $hook ) {

		$this->bdpw_register_admin_assets();

		if( 'blog-designer_page_bdpw-solutions-features' == $hook || 'blog-designer_page_bdpw-premium' == $hook ) {
			wp_enqueue_style( 'bdpw-admin-css' );
		}

		if( 'toplevel_page_bdpw-about' == $hook || 'blog-designer_page_bdpw-solutions-features' == $hook ) {
			wp_enqueue_script( 'bdpw-admin-js' );
		}
	}

	/**
	 * Function to add script at front side
	 * 
	 * @since 1.0
	 */
	function bdpw_front_style_script() {

		global $post;

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && isset( $post->ID ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		/* Styles */
		// Registring and enqueing slick css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', BDPW_URL.'assets/css/slick.css', array(), BDPW_VERSION );
		}
		wp_enqueue_style( 'wpos-slick-style');

		// Registring and enqueing public css
		wp_register_style( 'bdpw-public-css', BDPW_URL.'assets/css/bdpw-public.css', array(), BDPW_VERSION );
		wp_enqueue_style( 'bdpw-public-css' );

		/* Scripts */
		// Registring slick slider script
		if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', BDPW_URL.'assets/js/slick.min.js', array('jquery'), BDPW_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'bdpw-elementor-script', BDPW_URL.'assets/js/elementor/bdpw-elementor.js', array('jquery'), BDPW_VERSION, true );

		// Registring public script
		wp_register_script( 'bdpw-public-js', BDPW_URL.'assets/js/bdpw-public-js.js', array('jquery'), BDPW_VERSION, true );
		wp_localize_script( 'bdpw-public-js', 'Bdpw', array(
															'is_mobile'	=> ( wp_is_mobile() ) ? 1 : 0,
															'is_rtl'	=> ( is_rtl() ) ? 1 : 0,
															'is_avada'	=> (class_exists( 'FusionBuilder' )) ? 1 : 0,
															'elementor_preview'	=> $elementor_preview,
														));

		// Enqueue Script for Elementor Preview
		if( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'bdpw-public-js' );
			wp_enqueue_script( 'bdpw-elementor-script' );
		}

		// Enqueue Style & Script for Beaver Builder
		if( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->bdpw_register_admin_assets();

			wp_enqueue_script( 'bdpw-admin-js' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'bdpw-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->bdpw_register_admin_assets();

			wp_enqueue_style( 'bdpw-admin-css');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->bdpw_register_admin_assets();

			wp_enqueue_style( 'bdpw-admin-css');
		}
	}
}

$bdpw_script = new Bdpw_Script();