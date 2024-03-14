<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package WP Trending Post Slider and Widget
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wtpsw_Script { 

	function __construct() {

		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array( $this, 'wtpsw_admin_script' ) );

		// Action to add style on frontend
		add_action( 'wp_enqueue_scripts', array( $this, 'wtpsw_front_end_style_script' ) );

		// Action to add admin script and style when edit with elementor at admin side
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'wtpsw_admin_builder_script_style' ) );

		// Action to add admin script and style when edit with SiteOrigin at admin side
		add_action( 'siteorigin_panel_enqueue_admin_scripts', array( $this, 'wtpsw_admin_builder_script_style' ), 10, 2 );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 1.5
	 */
	function wtpsw_register_admin_assets() {

		/* Styles */
		wp_register_style( 'wtpsw-admin-css', WTPSW_URL.'assets/css/wtpsw-admin.css', array(), WTPSW_VERSION );

		/* Scripts */
		wp_register_script( 'wtpsw-admin-script', WTPSW_URL.'assets/js/wtpsw-admin.js', array( 'jquery' ), WTPSW_VERSION, true );
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @since 1.4
	 */
	function wtpsw_admin_script( $hook ) {

		$this->wtpsw_register_admin_assets();

		if( $hook == 'trending-post_page_wtpsw-help' ) {
			wp_enqueue_script( 'wtpsw-admin-script' );
		}
	}

	/**
	 * Enqueue front styles
	 * 
	 * @since 1.0.0
	 */
	function wtpsw_front_end_style_script() {

		global $post;

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		// Taking post id to update post view count
		$post_id			= isset( $post->ID ) ? $post->ID : '';
		$post_view_count	= 0;

		$supported_posts = wtpsw_get_option( 'post_types', array() ); // suppoterd post type

		if( ! empty( $post_id ) && !is_preview() && ! empty( $supported_posts ) && is_singular( $supported_posts ) && !is_front_page() && !is_home() && !is_feed() && !is_robots() ) {
			$post_view_count	= $post_id;
		}

		/* Styles */
		// Registring and enqueing slick slider css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WTPSW_URL.'assets/css/slick.css', array(), WTPSW_VERSION );
			wp_enqueue_style('wpos-slick-style');
		}

		// Registring slider style
		wp_register_style( 'wtpsw-public-style', WTPSW_URL.'assets/css/wtpsw-public.css', array(), WTPSW_VERSION );
		wp_enqueue_style( 'wtpsw-public-style' );

		/* Scripts */
		// Registring slider script
		if( !wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WTPSW_URL.'assets/js/slick.min.js', array( 'jquery' ), WTPSW_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'wtpsw-elementor-js', WTPSW_URL.'assets/js/elementor/wtpsw-elementor.js', array( 'jquery' ), WTPSW_VERSION, true );

		// Registering Public Script (Slider Script)
		wp_register_script( 'wtpsw-public-script', WTPSW_URL.'assets/js/wtpsw-public.js', array( 'jquery' ), WTPSW_VERSION, true );
		wp_localize_script( 'wtpsw-public-script', 'Wtpsw', array(
																'elementor_preview'		=> $elementor_preview,
																'ajaxurl'				=> admin_url( 'admin-ajax.php', ( is_ssl() ? 'https' : 'http' ) ),
																'is_mobile'				=> ( wp_is_mobile() )	? 1 : 0,
																'is_avada' 				=> ( class_exists( 'FusionBuilder' ) ) ? 1 : 0,
																'is_rtl'				=> ( is_rtl() )			? 1 : 0,
																'post_view_count'		=> $post_view_count,
																'data_nonce'			=> wp_create_nonce( 'wtpsw-post-view-count-data' ),
															));
		wp_enqueue_script( 'wtpsw-public-script' );

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			// Dequeue public script
			wp_dequeue_script( 'wtpsw-public-script' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wtpsw-public-script' );
			wp_enqueue_script( 'wtpsw-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wtpsw_register_admin_assets();

			// Dequeue admin style
			wp_enqueue_style( 'wtpsw-admin-css' );
			wp_enqueue_script( 'wtpsw-admin-script' );

			// Dequeue public script
			wp_dequeue_script( 'wtpsw-public-script' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wtpsw-public-script' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wtpsw_register_admin_assets();

			wp_enqueue_style( 'wtpsw-admin-css' );
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wtpsw_register_admin_assets();

			wp_enqueue_style( 'wtpsw-admin-css' );
		}
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @since 1.5
	 */
	function wtpsw_admin_builder_script_style() {
		$this->wtpsw_register_admin_assets();

		wp_enqueue_style( 'wtpsw-admin-css' );
		wp_enqueue_script( 'wtpsw-admin-script' );
	}
}

$wtpsw_script = new Wtpsw_Script();