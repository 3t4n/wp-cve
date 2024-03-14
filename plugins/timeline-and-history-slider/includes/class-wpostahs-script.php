<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package Timeline and History slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Tahs_Script {

	function __construct() {

		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'tahs_admin_script' ));

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'tahs_front_style' ));

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array( $this, 'tahs_front_script' ));
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @package Timeline and History Slider
	 * @since 1.5
	 */
	function tahs_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'tahs-admin-css', WPOSTAHS_URL.'assets/css/wpostahs-admin.css', array(), WPOSTAHS_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'tahs-admin-script', WPOSTAHS_URL.'assets/js/wpostahs-admin.js', array('jquery'), WPOSTAHS_VERSION, true );

	}

	/**
	 * Function to add script at admin side
	 * 
	 * @package Timeline and History slider
	 * @since 1.4
	 */
	function tahs_admin_script( $hook ) {

		global $typenow;

		$this->tahs_register_admin_assets();

		// Taking pages array
		$pages_arr = array( WPOSTAHS_POST_TYPE );

		if( in_array( $typenow, $pages_arr )) {
			wp_enqueue_style( 'tahs-admin-css' );
		}

		if( $hook == WPOSTAHS_POST_TYPE.'_page_wpostahs-designs' ) {

			// Enqueue Admin Scripts
			wp_enqueue_script( 'tahs-admin-script' );
		
		}
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function tahs_front_style() {

		// Registring and enqueing slick slider css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WPOSTAHS_URL.'assets/css/slick.css', array(), WPOSTAHS_VERSION );
		}
		wp_enqueue_style( 'wpos-slick-style' );

		// Registring and enqueing public css
		wp_register_style( 'tahs-public-style', WPOSTAHS_URL.'assets/css/slick-slider-style.css', array(), WPOSTAHS_VERSION );
		wp_enqueue_style( 'tahs-public-style' );
	}

	/**
	 * Function to add script at front side
	 * 
	 * @package Timeline and History slider
	 * @since 1.0.0
	 */
	function tahs_front_script() {

		global $post;

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		// Registring slick slider script
		if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WPOSTAHS_URL.'assets/js/slick.min.js', array('jquery'), WPOSTAHS_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'wpostahs-elementor-js', WPOSTAHS_URL.'assets/js/elementor/wpostahs-elementor.js', array('jquery'), WPOSTAHS_VERSION, true );

		// Registring and enqueing public script
		wp_register_script( 'wpostahs-public-js', WPOSTAHS_URL.'assets/js/wpostahs-public-js.js', array('jquery'), WPOSTAHS_VERSION, true );
		wp_localize_script( 'wpostahs-public-js', 'Wpostahs', array(
																	'elementor_preview'	=> $elementor_preview,
																	'is_mobile'	=> (wp_is_mobile())	? 1 : 0,
																	'is_rtl'	=> (is_rtl())		? 1 : 0,
																	'is_avada'	=> (class_exists( 'FusionBuilder' ))	? 1 : 0,
																	));

		// Enqueue Script for Elementor Preview
		if( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpostahs-public-js' );
			wp_enqueue_script( 'wpostahs-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->tahs_register_admin_assets();

			wp_enqueue_style( 'tahs-admin-css' );
			wp_enqueue_script( 'tahs-admin-script' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wpostahs-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->tahs_register_admin_assets();

			wp_enqueue_style( 'tahs-admin-css' );
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->tahs_register_admin_assets();

			wp_enqueue_style( 'tahs-admin-css');
		}

	}
}

$tahs_script = new Tahs_Script();