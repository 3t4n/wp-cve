<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Testimonials with rotator widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wptww_Script {

	function __construct() {

		// Action to add style && script in backend
		add_action( 'admin_enqueue_scripts', array( $this, 'wptww_admin_styles_scripts' ) );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'wptww_front_end_styles_scripts' ) );
	
		// Action to add admin script and style when edit with elementor at admin side
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'wtwp_admin_builder_script_style' ) );

		// Action to add admin script and style when edit with SiteOrigin at admin side
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'wtwp_admin_builder_script_style' ), 10, 2 );
	}


	/**
	 * Function to register admin scripts and styles
	 *
	 * @since 1.5
	 */
	function wtwp_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'wtwp-admin-css', WTWP_URL.'assets/css/wtwp-admin.css', array(), WTWP_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'wtwp-admin-js', WTWP_URL.'assets/js/wtwp-admin.js', array('jquery'), WTWP_VERSION, true );
		
	}

	/**
	 * Enqueue admin script
	 * 
	 * @since 2.6
	 */
	function wptww_admin_styles_scripts( $hook ) {

		global $typenow;

		$this->wtwp_register_admin_assets();

		// Taking pages array
		$pages_arr = array( WTWP_POST_TYPE );

		if( in_array( $typenow, $pages_arr ) ) {
			wp_enqueue_style( 'wtwp-admin-css' );
		}

		if( $hook == WTWP_POST_TYPE.'_page_wptww-designs' || $hook == WTWP_POST_TYPE.'_page_wtwp-solutions-features' ) {
			wp_enqueue_script( 'wtwp-admin-js' );
		}
	}

	/**
	 * Function to add style and script at front side
	 * 
	 * @since 1.0.0
	 */
	function wptww_front_end_styles_scripts() {

		global $post;

		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		/***** Registering Styles *****/		
		// Registring and enqueing font awesome css
		if( ! wp_style_is( 'wpos-font-awesome', 'registered' ) ) {
			wp_register_style( 'wpos-font-awesome', WTWP_URL.'assets/css/font-awesome.min.css', array(), WTWP_VERSION );
		}

		// Registring and enqueing slick css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WTWP_URL.'assets/css/slick.css', array(), WTWP_VERSION );
		}

		// Registring and enqueing public css
		wp_register_style( 'wtwp-public-css', WTWP_URL.'assets/css/wtwp-public.css', array(), WTWP_VERSION );

		/***** Registering Scripts *****/
		// Registring slick slider script
		if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WTWP_URL.'assets/js/slick.min.js', array( 'jquery' ), WTWP_VERSION, true );
		}

		wp_register_script( 'wtwp-public-script', WTWP_URL.'assets/js/wtwp-public.js', array('jquery'), WTWP_VERSION, true );
		wp_localize_script( 'wtwp-public-script', 'Wtwp', array(
													'is_rtl'			=> ( is_rtl() ) ? 	1 : 0,
													'is_avada'			=> ( class_exists( 'FusionBuilder' ) ) ? 1 : 0,
													'elementor_preview'	=> $elementor_preview,
						) );

		// Register Elementor script
		wp_register_script( 'wtwp-elementor-script', WTWP_URL.'assets/js/elementor/wtwp-elementor.js', array( 'jquery' ), WTWP_VERSION, true );

		/***** Enqueue Styles *****/
		wp_enqueue_style( 'wpos-font-awesome' );	// FontAwesome
		wp_enqueue_style( 'wpos-slick-style' );		// Slick
		wp_enqueue_style( 'wtwp-public-css' );		// Public

		// Enqueue Script for Elementor Preview
		if ( defined( 'ELEMENTOR_PLUGIN_BASE' ) && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wtwp-public-script' );
			wp_enqueue_script( 'wtwp-elementor-script' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wtwp_register_admin_assets();

			wp_enqueue_style( 'wtwp-admin-css');
			wp_enqueue_script( 'wtwp-admin-js' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wtwp-public-script' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wtwp_register_admin_assets();

			wp_enqueue_style( 'wtwp-admin-css');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wtwp_register_admin_assets();

			wp_enqueue_style( 'wtwp-admin-css');
		}

	}


	/**
	 * Function to add script at admin side
	 * 
	 * @since 1.4
	 */
	function wtwp_admin_builder_script_style() {

		$this->wtwp_register_admin_assets();

		wp_enqueue_style( 'wtwp-admin-css' );
	}

}

$wptww_script = new Wptww_Script();