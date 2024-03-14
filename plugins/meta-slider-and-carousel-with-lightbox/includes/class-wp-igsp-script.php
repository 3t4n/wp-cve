<?php
/**
 * Script Class
 * Handles the script and style functionality of plugin
 *
 * @package Meta slider and carousel with lightbox
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class WP_Igsp_Script {

	function __construct() {

		// Action to add style and script in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wp_igsp_admin_style_script') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wp_igsp_front_style_script') );
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 1.4
	 */
	function wp_igsp_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'wp-igsp-admin-style', WP_IGSP_URL.'assets/css/wp-igsp-admin.css', null, WP_IGSP_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'wp-igsp-admin-script', WP_IGSP_URL.'assets/js/wp-igsp-admin.js', array('jquery'), WP_IGSP_VERSION, true );
		wp_localize_script( 'wp-igsp-admin-script', 'WpIgspAdmin', array(
																'img_edit_popup_text'	=> esc_js( __('Edit Image in Popup', 'meta-slider-and-carousel-with-lightbox') ),
																'attachment_edit_text'	=> esc_js( __('Edit Image', 'meta-slider-and-carousel-with-lightbox') ),
																'img_delete_text'		=> esc_js( __('Remove Image', 'meta-slider-and-carousel-with-lightbox') ),
																'all_img_delete_text'	=> esc_js( __('Are you sure to remove all images from this gallery!', 'meta-slider-and-carousel-with-lightbox') ),
															));
	}

	/**
	 * Function to enqueue admin scripts and styles
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_admin_style_script( $hook ) {

		$this->wp_igsp_register_admin_assets();

		global $post_type;

		$registered_posts = wp_igsp_get_post_types(); // Getting registered post types

		/* Admin Style Enqueue*/
		// If page is plugin setting page then enqueue script
		if( in_array($post_type, $registered_posts) ) {
			wp_enqueue_style( 'wp-igsp-admin-style' );
		}

		/* Admin Script Enqueue*/
		if( in_array($post_type, $registered_posts) ) {

			// Enqueue required inbuilt sctipt
			wp_enqueue_script( 'jquery-ui-sortable' );

			wp_enqueue_script( 'wp-igsp-admin-script' );
			wp_enqueue_media(); // For media uploader
		}

		if( $hook == WP_IGSP_POST_TYPE.'_page_igsp-designs' || $hook == WP_IGSP_POST_TYPE.'_page_wp-igsp-solutions-features' ) {
			wp_enqueue_script( 'wp-igsp-admin-script' );
		}

		if( $hook == WP_IGSP_POST_TYPE.'_page_wp-igsp-solutions-features' || $hook == WP_IGSP_POST_TYPE.'_page_wp-igsp-premium' ) {
			wp_enqueue_style( 'wp-igsp-admin-style' );
		}
	}

	/**
	 * Function to add script at front side
	 * 
	 * @since 1.0.0
	 */
	function wp_igsp_front_style_script() {

		global $post;

		// Determine Elementor Preview Screen
		// Check elementor preview is there
		$elementor_preview = ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) ? 1 : 0;

		/* Styles */
		// Registring and enqueing magnific css
		if( ! wp_style_is( 'wpos-magnific-style', 'registered' ) ) {
			wp_register_style( 'wpos-magnific-style', WP_IGSP_URL.'assets/css/magnific-popup.css', array(), WP_IGSP_VERSION );
		}
		wp_enqueue_style( 'wpos-magnific-style');

		// Registring and enqueing slick css
		if( ! wp_style_is( 'wpos-slick-style', 'registered' ) ) {
			wp_register_style( 'wpos-slick-style', WP_IGSP_URL.'assets/css/slick.css', array(), WP_IGSP_VERSION );
		}
		wp_enqueue_style( 'wpos-slick-style');

		// Registring and enqueing public css
		wp_register_style( 'wp-igsp-public-css', WP_IGSP_URL.'assets/css/wp-igsp-public.css', null, WP_IGSP_VERSION );
		wp_enqueue_style( 'wp-igsp-public-css' );

		/* Scripts */
		// Registring magnific popup script
		if( ! wp_script_is( 'wpos-magnific-script', 'registered' ) ) {
			wp_register_script( 'wpos-magnific-script', WP_IGSP_URL.'assets/js/jquery.magnific-popup.min.js', array('jquery'), WP_IGSP_VERSION, true );
		}

		// Registring slick slider script
		if( ! wp_script_is( 'wpos-slick-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-slick-jquery', WP_IGSP_URL.'assets/js/slick.min.js', array('jquery'), WP_IGSP_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'wp-igsp-elementor-js', WP_IGSP_URL.'assets/js/elementor/wp-igsp-elementor.js', array('jquery'), WP_IGSP_VERSION, true );

		// Registring public script
		wp_register_script( 'wp-igsp-public-js', WP_IGSP_URL.'assets/js/wp-igsp-public.js', array('jquery'), WP_IGSP_VERSION, true );
		wp_localize_script( 'wp-igsp-public-js', 'WpIsgp', array(
															'elementor_preview'	=> $elementor_preview,
															'is_mobile'	=>	(wp_is_mobile())	? 1 : 0,
															'is_rtl'	=>	(is_rtl())			? 1 : 0,
															'is_avada'	=> ( class_exists( 'FusionBuilder' ) )	? 1 : 0,
														));

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-magnific-script' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wp-igsp-public-js' );
			wp_enqueue_script( 'wp-igsp-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wp_igsp_register_admin_assets();

			wp_enqueue_style( 'wp-igsp-admin-style');
			wp_enqueue_script( 'wpos-magnific-script' );
			wp_enqueue_script( 'wpos-slick-jquery' );
			wp_enqueue_script( 'wp-igsp-admin-script' );
			wp_enqueue_script( 'wp-igsp-public-js' );
		}

		// Enqueue Admin Style & Script for Divi Page Builder
		if( function_exists( 'et_core_is_fb_enabled' ) && isset( $_GET['et_fb'] ) && $_GET['et_fb'] == 1 ) {
			$this->wp_igsp_register_admin_assets();

			wp_enqueue_style( 'wp-igsp-admin-style');
		}

		// Enqueue Admin Style for Fusion Page Builder
		if( class_exists( 'FusionBuilder' ) && (( isset( $_GET['builder'] ) && $_GET['builder'] == 'true' ) ) ) {
			$this->wp_igsp_register_admin_assets();

			wp_enqueue_style( 'wp-igsp-admin-style');
		}
	}
}

$wp_igsp_script = new WP_Igsp_Script();