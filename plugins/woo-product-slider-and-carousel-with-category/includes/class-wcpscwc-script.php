<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Product Slider and Carousel with Category for WooCommerce
 * @since 2.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wcpscwc_Script {

	function __construct() {

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wcpscwc_admin_script_style') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wcpscwc_front_script_style') );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 2.5
	 */
	function wcpscwc_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'wcpscwc-admin-css', WCPSCWC_URL.'assets/css/wcpscwc-admin.css', array(), WCPSCWC_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'wcpscwc-admin-script', WCPSCWC_URL.'assets/js/wcpscwc-admin.js', array('jquery'), WCPSCWC_VERSION, true );
	}

	/**
	 * Function to add style and script at admin side
	 * 
	 * @since 2.5
	 */
	function wcpscwc_admin_script_style( $hook ) {

		$this->wcpscwc_register_admin_assets();

		if( $hook == 'toplevel_page_wcpscwc-about' ) {
			wp_enqueue_script( 'wcpscwc-admin-script' );
		}

		if( 'woo-product-slider_page_wcpscwc-solutions-features' == $hook || 'woo-product-slider_page_wcpscwc-premium' == $hook ) {
			wp_enqueue_style( 'wcpscwc-admin-css');
		}
	}

	/**
	 * Function to add script at front side
	 * 
	 * @since 2.5
	 */
	function wcpscwc_front_script_style() {

		global $post;

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		/* Styles */
		// Slick CSS
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WCPSCWC_URL.'assets/css/slick.css', array(), WCPSCWC_VERSION );
		}

		// Registring and enqueing public css
		wp_register_style( 'wcpscwc-public-style', WCPSCWC_URL.'assets/css/wcpscwc-public.css', array(), WCPSCWC_VERSION );

		wp_enqueue_style( 'wpos-slick-style' );
		wp_enqueue_style( 'wcpscwc-public-style' );


		/* Scripts */
		// Registring slick slider script
		if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WCPSCWC_URL.'assets/js/slick.min.js', array('jquery'), WCPSCWC_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'wcpscwc-elementor-js', WCPSCWC_URL.'assets/js/elementor/wcpscwc-elementor.js', array('jquery'), WCPSCWC_VERSION, true );

		// Registring and enqueing public script
		wp_register_script( 'wcpscwc-public-jquery', WCPSCWC_URL.'assets/js/wcpscwc-public.js', array('jquery'), WCPSCWC_VERSION, true );
		wp_localize_script( 'wcpscwc-public-jquery', 'Wcpscwc', array(
																	'elementor_preview'	=> $elementor_preview,
																	'is_avada'			=> ( class_exists( 'FusionBuilder' ))	? 1 : 0,
																));

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wcpscwc-public-jquery' );
			wp_enqueue_script( 'wcpscwc-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {
			$this->wcpscwc_register_admin_assets();

			wp_enqueue_script( 'wcpscwc-admin-script' );
			wp_dequeue_script( 'wcpscwc-public-jquery' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wcpscwc-public-jquery' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wcpscwc_register_admin_assets();

			wp_enqueue_style( 'wcpscwc-admin-css');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wcpscwc_register_admin_assets();

			wp_enqueue_style( 'wcpscwc-admin-css');
		}
	}
}

$wcpscwc_script = new Wcpscwc_Script();