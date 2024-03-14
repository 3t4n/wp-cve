<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Slick Slider and Image Carousel
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpsisac_Script {

	function __construct() {

		// Action to add style && script in backend
		add_action( 'admin_enqueue_scripts', array( $this, 'wpsisac_admin_style_script' ) );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'wpsisac_front_style_script' ) );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 1.6
	 */
	function wpsisac_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'wpsisac-admin-style', WPSISAC_URL.'assets/css/wpsisac-admin.css', array(), WPSISAC_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'wpsisac-admin-js', WPSISAC_URL.'assets/js/wpsisac-admin.js', array('jquery'), WPSISAC_VERSION, true );

	}

	/**
	 * Enqueue admin script
	 * 
	 * @since 1.1
	 */
	function wpsisac_admin_style_script( $hook ) {

		global $typenow;

		$this->wpsisac_register_admin_assets();

		/* Styles */
		if( WPSISAC_POST_TYPE == $typenow ) {
			wp_enqueue_style( 'wpsisac-admin-style' );
		}

		/* Scripts */
		if( $hook == WPSISAC_POST_TYPE.'_page_wpsisacm-designs' || $hook == WPSISAC_POST_TYPE.'_page_wpsisac-solutions-features') {
			wp_enqueue_script( 'wpsisac-admin-js' );
		}
	}

	/**
	 * Function to add style at front side
	 * 
	 * @since 1.0.0
	 */
	function wpsisac_front_style_script() {
		
		global $post;

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		/* Styles */
		// Registring and enqueing slick slider css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WPSISAC_URL.'assets/css/slick.css', array(), WPSISAC_VERSION );
		}
		wp_enqueue_style( 'wpos-slick-style' );

		// Registring and enqueing public css
		wp_register_style( 'wpsisac-public-style', WPSISAC_URL.'assets/css/wpsisac-public.css', array(), WPSISAC_VERSION );
		wp_enqueue_style( 'wpsisac-public-style' );

		/* Scripts */
		// Registring slick slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WPSISAC_URL.'assets/js/slick.min.js', array('jquery'), WPSISAC_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'wpsisac-elementor-script', WPSISAC_URL.'assets/js/elementor/wpsisac-elementor.js', array('jquery'), WPSISAC_VERSION, true );

		// Registring and enqueing public script
		wp_register_script( 'wpsisac-public-script', WPSISAC_URL.'assets/js/wpsisac-public.js', array('jquery'), WPSISAC_VERSION, true );
		wp_localize_script( 'wpsisac-public-script', 'Wpsisac', array(
																	'elementor_preview'	=> $elementor_preview,
																	'is_mobile'			=> ( wp_is_mobile() )	? 1 : 0,
																	'is_rtl'			=> ( is_rtl() )			? 1 : 0,
																	'is_avada'			=> ( class_exists( 'FusionBuilder' ) )	? 1 : 0,
																));

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpsisac-public-script' );
			wp_enqueue_script( 'wpsisac-elementor-script' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wpsisac_register_admin_assets();

			wp_enqueue_script( 'wpsisac-admin-js' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpsisac-public-script' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wpsisac_register_admin_assets();

			wp_enqueue_style( 'wpsisac-admin-style');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wpsisac_register_admin_assets();

			wp_enqueue_style( 'wpsisac-admin-style');
		}
	}
}

$wpsisac_script = new Wpsisac_Script();