<?php
/**
 * Script Class
 * Handles the script and style functionality of plugin
 *
 * @package WP News and Scrolling Widgets
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Wpnw_Script {

	function __construct() {

		// Action to add style at admin side
		add_action( 'admin_enqueue_scripts', array($this, 'wpnw_news_admin_style_script') );

		// Action to add style and script at front side
		add_action( 'wp_enqueue_scripts', array($this, 'wpnw_news_plugin_style_script') );

		// Action to add admin script and style when edit with elementor at admin side
		add_action( 'elementor/editor/after_enqueue_scripts', array($this, 'wpnw_admin_builder_script_style') );

		// Action to add admin script and style when edit with SiteOrigin at admin side
		add_action('siteorigin_panel_enqueue_admin_scripts', array($this, 'wpnw_admin_builder_script_style'), 10, 2);
	}

	/**
	 * Function to register admin scripts and styles
	 * 
	 * @since 4.3
	 */
	function wpnw_register_admin_assets() {

		/* Styles */
		// Registring admin css
		wp_register_style( 'sp-news-admin-css', WPNW_URL.'assets/css/wpnw-admin.css', array(), WPNW_VERSION );

		/* Scripts */
		// Registring admin script
		wp_register_script( 'sp-news-admin-js', WPNW_URL.'assets/js/wpnw-admin.js', array('jquery'), WPNW_VERSION, true );
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @since 4.4
	 */
	function wpnw_news_admin_style_script( $hook ) {

		global $typenow;

		$this->wpnw_register_admin_assets();

		/* Styles */
		// Taking pages array
		$pages_arr = array( WPNW_POST_TYPE );

		if( in_array( $typenow, $pages_arr ) ) {
			wp_enqueue_style( 'sp-news-admin-css' );
		}

		/* Scripts */
		// How it work page
		if( $hook == WPNW_POST_TYPE.'_page_wpnw-designs' || $hook == WPNW_POST_TYPE.'_page_wpnw-solutions-features' ) {
			wp_enqueue_script( 'sp-news-admin-js' );
		}
	}

	/**
	 * Function to add style and script at front side
	 * 
	 * @since 1.0.0
	 */
	function wpnw_news_plugin_style_script() {

		global $post;

		/* Styles */
		// Registring and enqueing public css
		wp_register_style( 'sp-news-public', WPNW_URL.'assets/css/wpnw-public.css', array(), WPNW_VERSION );
		wp_enqueue_style( 'sp-news-public' );


		/* Scripts */
		if( ! wp_script_is( 'wpos-vticker-jquery', 'registered' ) ) {
			wp_register_script( 'wpos-vticker-jquery', WPNW_URL . 'assets/js/jquery.newstape.js', array('jquery'), WPNW_VERSION, true );
		}

		// Register Elementor script
		wp_register_script( 'wpnw-elementor-js', WPNW_URL.'assets/js/elementor/wpnw-elementor.js', array('jquery'), WPNW_VERSION, true );

		// Register Public Script
		wp_register_script( 'sp-news-public', WPNW_URL.'assets/js/wpnw-public.js', array('jquery'), WPNW_VERSION, true );

		// Enqueue Script for Elementor Preview
		if ( defined('ELEMENTOR_PLUGIN_BASE') && isset( $_GET['elementor-preview'] ) && $post->ID == (int) $_GET['elementor-preview'] ) {

			wp_enqueue_script( 'wpos-vticker-jquery' );
			wp_enqueue_script( 'sp-news-public' );
			wp_enqueue_script( 'wpnw-elementor-js' );
		}

		// Enqueue Style & Script for Beaver Builder
		if ( class_exists( 'FLBuilderModel' ) && FLBuilderModel::is_builder_active() ) {

			$this->wpnw_register_admin_assets();

			// Enqueue admin style
			wp_enqueue_style( 'sp-news-admin-css' );
			wp_enqueue_script( 'sp-news-admin-js' );
			wp_enqueue_script( 'wpos-vticker-jquery' );
			wp_enqueue_script( 'sp-news-public' );
		}
	}

	/**
	 * Function to add script at admin side
	 * 
	 * @since 4.3
	 */
	function wpnw_admin_builder_script_style() {

		$this->wpnw_register_admin_assets();

		wp_enqueue_style( 'sp-news-admin-css' );
		wp_enqueue_script( 'sp-news-admin-js' );
	}
}

$wpnw_script = new Wpnw_Script();