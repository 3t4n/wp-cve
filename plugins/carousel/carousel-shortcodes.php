<?php
	/*
	Plugin Name: Carousels Ultimate
	Plugin URI: https://themepoints.com/carouselpro/
	Description: Carousels ultimate allows you to use shortcode to display carousel, slider, post slider, logo, team in post/page or widgets.
	Version: 1.8
	Author: Themepoints
	Author URI: https://themepoints.com
	TextDomain: carosuelfree
	License: copyright@themepoints.com
	*/
	

	if( !defined( 'ABSPATH' ) ){
	    exit;
	}

	define('THEMEPOINTS_CAROUSEL_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('themepoints_carousel_plugin_dir', plugin_dir_path(__FILE__) );
	
	add_filter('widget_text', 'do_shortcode');

	function tp_ultimate_carousel_script_init(){

		wp_enqueue_script( 'jquery' );
		wp_enqueue_style( 'caropro-fontawesome-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'assets/css/font-awesome.min.css' );
		wp_enqueue_style( 'caropro-owl-min-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'assets/css/owl.carousel.min.css' );
		wp_enqueue_style( 'caropro-owl-theme-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'assets/css/owl.theme.default.css' );
		wp_enqueue_style( 'caropro-animate-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'assets/css/animate.css' );
		wp_enqueue_style( 'caropro-style-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'assets/css/style.css' );

		wp_enqueue_script( 'carpros_pro_ajax_js', plugins_url( 'assets/js/app_script.js', __FILE__), array(), '1.0.0', true );
		wp_localize_script( 'carpros_pro_ajax_js', 'carpros_pro_ajax', array( 'carpros_pro_ajaxurl' => admin_url( 'admin-ajax.php')));
		wp_enqueue_script( 'caropro_slider_js', plugins_url( '/assets/js/owl.carousel.js' , __FILE__ ) , array( 'jquery' ) );
		wp_enqueue_script( 'caropro_mousewheel_js', plugins_url( '/assets/js/jquery.mousewheel.min.js' , __FILE__ ) , array( 'jquery' ) );
		wp_enqueue_script( 'caropro_colorpicker_js', plugins_url( '/assets/js/jscolor.js' , __FILE__ ) , array( 'jquery' ) );

	}
	add_action( 'init', 'tp_ultimate_carousel_script_init' );
	
	function tp_ultimate_add_google_fonts() {

	wp_enqueue_style( 'example-google-fonts1', 'https://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,700italic,400,700,300', false );
	wp_enqueue_style( 'example-google-fonts2', 'https://fonts.googleapis.com/css?family=Tangerine', false ); 
	}
	add_action( 'wp_enqueue_scripts', 'tp_ultimate_add_google_fonts' );
	
	# Carousel Pro wordpress Admin enqueue scripts
	function tpcarouel_pro_wordpress_admin_enqueue_scripts(){
		global $typenow;

		if(($typenow == 'carousel_shortcode')){
			wp_enqueue_style('tpcaro-admin-css', THEMEPOINTS_CAROUSEL_PLUGIN_PATH.'admin/css/tp-carousel-pro-admin.css');
			
			wp_enqueue_script( 'jquery' );
			wp_enqueue_script( 'tpcaro-admin-js', plugins_url( 'admin/js/tp-carousel-pro-admin.js', __FILE__), array(), '1.0.0', true );
		}
	}
	add_action('admin_enqueue_scripts', 'tpcarouel_pro_wordpress_admin_enqueue_scripts');
	

	function caropro_slider_load_admin_scripts() {

		wp_enqueue_style( 'wp-color-picker' );
		wp_enqueue_script( 'wp-color-picker-alpha', plugins_url( '/assets/js/wp-color-picker-alpha.js', __FILE__ ), array( 'wp-color-picker' ));
	}
	add_action('admin_enqueue_scripts', 'caropro_slider_load_admin_scripts');	


	# Load plugin Translations
	function caropro_slider_load_textdomain(){

		load_plugin_textdomain('carosuelfree', false, dirname( plugin_basename( __FILE__ ) ) .'/languages/' );

	}
	add_action('plugins_loaded', 'caropro_slider_load_textdomain');

	# Post Type
	require_once( 'lib/post-type/caropro-slider-post-type.php' );

	# Metabox
	require_once( 'lib/metaboxes/caropro-slider-post-metaboxes.php' );

	# Core
	require_once( 'lib/core/caropro-slider-post-core.php' );

	#Shortcode
	require_once( 'lib/shortcodes/caropro-slider-post-shortcode.php' );

?>