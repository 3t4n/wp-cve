<?php
	/*
		Plugin Name: Logo Showcase Free
		Plugin URI: https://pickelements.com/logoshowcasefree/
		Description: Logo Showcase is a lightweight & responsive plugin to display a list of clients, supporters, partners or sponsors logos in your WordPress website . You can easily create a slider, grid or list of images with external or internal links with title & description. You can manage everything via the option page. No need to required any coding skill.
		Version: 2.0.8
		Author: Pickelements
		Author URI: https://pickelements.com
		License: GPLv2
		Text Domain: logo-showcase-free
		Domain Path: /languages/
	*/


	/**********************************************************
	 * Exit if accessed directly
	 **********************************************************/

	if ( ! defined( 'ABSPATH' ) )
	die("Can't load this file directly");

	define('PICK_LOGO_FREE_PLUGIN_PATH', WP_PLUGIN_URL . '/' . plugin_basename( dirname(__FILE__) ) . '/' );
	define('pick_logo_free_plugin_dir', plugin_dir_path( __FILE__ ) );
	add_filter('widget_text', 'do_shortcode');
	
	# Load plugin Scripts
	function pic_logo_showcase_free_scripts(){
		wp_enqueue_style( 'pick-logo-fawesome', plugins_url( '/assets/css/font-awesome.min.css' , __FILE__ ) );
		wp_enqueue_style( 'pick-logo-slick', plugins_url( '/assets/css/slick.css' , __FILE__ ) );
		wp_enqueue_style( 'pick-logo-tooltipster', plugins_url( '/assets/css/tooltipster.bundle.min.css' , __FILE__ ) );
		wp_enqueue_style( 'pick-logo-appscripts', plugins_url( '/assets/css/appscripts.css' , __FILE__ ) );
	    wp_enqueue_script( 'jquery' );
	    wp_enqueue_script( "jquery-ui-sortable" );
	    wp_enqueue_script( "jquery-ui-draggable" );
	    wp_enqueue_script( "jquery-ui-droppable" );
		wp_enqueue_script( 'pick-logo-slick-js', plugins_url( '/assets/js/slick.js' , __FILE__ ) , array( 'jquery' ) );
		wp_enqueue_script( 'pick-logo-tooltipster-js', plugins_url( '/assets/js/tooltipster.bundle.min.js' , __FILE__ ) , array( 'jquery' ) );
		wp_enqueue_script( 'pick-logo-admin-js', plugins_url( '/assets/js/logo-showcase-free-main.js' , __FILE__ ) , array( 'jquery' ) );
	}
	add_action( 'init', 'pic_logo_showcase_free_scripts' );

	# Load plugin Translations
	function pic_logo_showcase_free_textdomain(){
 		load_plugin_textdomain( 'logo-showcase-free', FALSE, basename( dirname( __FILE__ ) ) . '/languages/' );
	}
	add_action('plugins_loaded', 'pic_logo_showcase_free_textdomain');

	# Load plugin Admin Scripts
	function pic_logo_showcase_admin_scripts(){
		wp_enqueue_style( 'pick_admin_css', plugins_url( '/assets/css/logo-showcase-free-admin.css' , __FILE__ ) );
	    wp_enqueue_script( 'jquery' );
		wp_enqueue_media();
		wp_enqueue_script('pick_admin_js', plugins_url( '/assets/js/logo-showcase-free-admin.js' , __FILE__ ) , array( 'jquery' ));
		wp_enqueue_style('wp-color-picker');
		wp_enqueue_script('logo_color_picker', plugins_url('/assets/js/color-picker.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
	}
	add_action( 'admin_enqueue_scripts', 'pic_logo_showcase_admin_scripts' );

	# Post Type
	require_once( 'lib/post-types/logo-showcase-free-posttype.php' );

	# Metabox
	require_once( 'lib/metaboxes/logo-showcase-free-metaboxes.php' );

	#Shortcode
	require_once( 'lib/shortcodes/logo-showcase-free-shortcode.php' );