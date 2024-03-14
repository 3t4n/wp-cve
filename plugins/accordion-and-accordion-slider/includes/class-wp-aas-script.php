<?php
/**
 * Script Class
 *
 * Handles the script and style functionality of plugin
 *
 * @package accordion-and-accordion-slider
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class WP_aas_Script {

	function __construct() {

		// Action to add style at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wp_aas_front_style') );

		// Action to add script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wp_aas_front_script') );

		// Action to add style in backend
		add_action( 'admin_enqueue_scripts', array($this, 'wp_aas_admin_style') );

		// Action to add script at admin side
		add_action( 'admin_enqueue_scripts', array($this, 'wp_aas_admin_script') );
	}

	/**
	 * Function to add style at front side
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_front_style() {

		// Registring and enqueing public css
		wp_register_style( 'wp-aas-public-css', WP_AAS_URL.'assets/css/wp-aas-public.css', array(), WP_AAS_VERSION );
		wp_enqueue_style( 'wp-aas-public-css' );

	}

	/**
	 * Function to add script at front side
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_front_script() {

		wp_register_script( 'wpos-accordion-slider-js', WP_AAS_URL.'assets/js/wpos-accordion-slider-js.js', array('jquery'), WP_AAS_VERSION, true );

		// Registring public script
		wp_register_script( 'wp-aas-public-js', WP_AAS_URL.'assets/js/wp-aas-public.js', array('jquery'), WP_AAS_VERSION, true );
	}

	/**
	 * Enqueue admin styles
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_admin_style( $hook ) {

		global $post_type, $typenow;

		$registered_posts = array(WP_AAS_POST_TYPE); // Getting registered post types

		// If page is plugin setting page then enqueue script
		if( in_array($post_type, $registered_posts) ) {

			// Registring admin script
			wp_register_style( 'wp-aas-admin-style', WP_AAS_URL.'assets/css/wp-aas-admin.css', array(), WP_AAS_VERSION );
			wp_enqueue_style( 'wp-aas-admin-style' );
		}
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @package accordion-and-accordion-slider
	 * @since 1.0.0
	 */
	function wp_aas_admin_script( $hook ) {

		global $wp_version, $wp_query, $typenow, $post_type;

		$registered_posts = array(WP_AAS_POST_TYPE); // Getting registered post types
		$new_ui = $wp_version >= '3.5' ? '1' : '0'; // Check wordpress version for older scripts

		// Registring admin script
		wp_register_script( 'wp-aas-admin-script', WP_AAS_URL.'assets/js/wp-aas-admin.js', array('jquery'), WP_AAS_VERSION, true );
		wp_localize_script( 'wp-aas-admin-script', 'WpAasAdmin', array(
																'new_ui' 				=>	$new_ui,
																'img_edit_popup_text'	=> __('Edit Image in Popup', 'accordion-and-accordion-slider'),
																'attachment_edit_text'	=> __('Edit Image', 'accordion-and-accordion-slider'),
																'img_delete_text'		=> __('Remove Image', 'accordion-and-accordion-slider'),
															));
		// Enqueue required inbuilt sctipt
		wp_enqueue_script( 'jquery-ui-sortable' );

		if( in_array($post_type, $registered_posts) ) {
			wp_enqueue_script( 'wp-aas-admin-script' );
			wp_enqueue_media(); // For media uploader
		}

		if($hook = WP_AAS_POST_TYPE . '_page_wp-aas-designs') {
			wp_enqueue_script( 'wp-aas-admin-script' );
		}

	}
}

$wp_aas_script = new WP_aas_Script();