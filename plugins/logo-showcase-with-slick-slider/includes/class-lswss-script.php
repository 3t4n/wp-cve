<?php
/**
 * Script Class
 * Handles the script and style functionality of the plugin
 *
 * @package Logo Showcase with Slick Slider
 * @since 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Lswss_Scripts {

	function __construct() {

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'lswss_admin_script_style') );

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'lswss_front_script_style') );
	}

	/**
	 * Enqueue admin styles
	 * 
	 * @since 1.0
	 */
	function lswss_admin_script_style( $hook_suffix ) {

		global $post_type, $typenow;
		
		// For VC Front End Page Editing
		if( function_exists('vc_is_frontend_editor') && vc_is_frontend_editor() ) {
			wp_register_script( 'lswssp-vc-frontend', LSWSS_URL . 'assets/js/vc/lswss-vc-frontend.js', array(), LSWSS_VERSION, true );
			wp_enqueue_script( 'lswssp-vc-frontend' );
		}

		/***** Styles *****/
		// Registring admin style
		wp_register_style( 'lswssp-admin-style', LSWSS_URL.'assets/css/lswss-admin.css', array(), LSWSS_VERSION );


		/***** Scripts *****/
		// Registring admin script
		wp_register_script( 'lswssp-admin-script', LSWSS_URL.'assets/js/lswss-admin.js', array('jquery'), LSWSS_VERSION, true );
		wp_localize_script( 'lswssp-admin-script', 'LswssAdmin', array(
																'confirm_msg'			=> esc_js( __('Are you sure you want to do this?', 'logo-showcase-with-slick-slider') ),
																'img_edit_text'			=> esc_js( __('Edit Image in a Popup', 'logo-showcase-with-slick-slider') ),
																'attachment_edit_text'	=> esc_js( __('Edit Image via Attachment Page', 'logo-showcase-with-slick-slider') ),
																'img_del_text'			=> esc_js( __('Remove Image', 'logo-showcase-with-slick-slider') ),
																'all_img_del_text'		=> esc_js( __('Are you sure to remove all logo images from here!', 'logo-showcase-with-slick-slider') ),
															));

		// If page is plugin post type screen then enqueue script
		if( $post_type == LSWSS_POST_TYPE ) {

			// Admin Scripts
			wp_enqueue_script( 'jquery-ui-sortable' );
			wp_enqueue_media();
		}

		if( $typenow == LSWSS_POST_TYPE ) {

			// Admin Styles
			wp_enqueue_style( 'lswssp-admin-style' );

			// Admin Scripts
			wp_enqueue_script( 'lswssp-admin-script' );
		}
	}

	/**
	 * Function to add style and script at front side
	 * 
	 * @since 1.0
	 */
	function lswss_front_script_style() {
		
		global $post;
		
		// Taking post id 
		$post_id = isset($post->ID) ? $post->ID : '';

		/***** Styles *****/
		// Registring Public CSS
		wp_register_style( 'lswssp-public-css', LSWSS_URL.'assets/css/lswss-public.css', array(), LSWSS_VERSION );
		wp_enqueue_style( 'lswssp-public-css' );


		/* Scripts */
		// Registring Slick Slider Script
		if( ! wp_script_is( 'jquery-slick', 'registered' ) ) {
			wp_register_script( 'jquery-slick', LSWSS_URL.'assets/js/slick.min.js', array('jquery'), LSWSS_VERSION, true );
		}

		// Registring Public Script
		wp_register_script( 'lswssp-public-script', LSWSS_URL.'assets/js/lswss-public.js', array('jquery'), LSWSS_VERSION, true );
		wp_localize_script( 'lswssp-public-script', 'Lswssp', array(
																'is_mobile'	=> ( wp_is_mobile() )	? 1 : 0,
																'is_rtl'	=> ( is_rtl() )			? 1 : 0,
															));
	
	
		/*===== Page Builder Scripts =====*/
		// VC Front End Page Editing
		if ( function_exists('vc_is_page_editable') && vc_is_page_editable() ) {
			
			wp_enqueue_script( 'jquery-slick' );
			wp_enqueue_script( 'lswssp-public-script' );
		}
		
		// Elementor Frontend Editing
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post_id == (int) $_GET['elementor-preview'] ) {
			wp_register_script( 'lswssp-elementor-script', LSWSS_URL . 'assets/js/elementor/lswss-elementor.js', array(), LSWSS_VERSION, true );
			
			wp_enqueue_script( 'jquery-slick' );
			wp_enqueue_script( 'lswssp-public-script' );
			wp_enqueue_script( 'lswssp-elementor-script' );
		}
	}
}