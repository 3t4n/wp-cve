<?php
/*
Plugin Name: Charity Addon for Elementor
Plugin URI: https://nicheaddons.com/demos/charity
Description: Charity Addon for Elementor covers all the must-needed elements for creating a perfect Charity website using Elementor Page Builder. 30+ Unique & Basic Elementor widget covers all of the Charity elements. Including getting a list of donation posts from most popular Charity WordPress plugins. Like, Causes, Campaigns, Countdown, Donors, Donation Form, Donation History, and Donate Buttons.
Author: NicheAddons
Author URI: https://nicheaddons.com/
Version: 1.3.0
Text Domain: charity-addon-for-elementor
*/

// ABSPATH
if ( ! function_exists( 'nacharity_block_direct_access' ) ) {
	function nacharity_block_direct_access() {
		if ( ! defined( 'ABSPATH' ) ) {
			exit( 'Forbidden' );
		}
	}
}

// Plugin URL
define( 'NACEP_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

// Plugin PATH
define( 'NACEP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'NACEP_PLUGIN_ASTS', NACEP_PLUGIN_URL . 'assets' );
define( 'NACEP_PLUGIN_IMGS', NACEP_PLUGIN_ASTS . '/images' );
define( 'NACEP_PLUGIN_CSS', NACEP_PLUGIN_ASTS . '/css' );
define( 'NACEP_PLUGIN_SCRIPTS', NACEP_PLUGIN_ASTS . '/js' );

// Charity Addon for Elementor Elementor Shortcode Path
define( 'NACEP_EM_SHORTCODE_BASE_PATH', NACEP_PLUGIN_PATH . 'elementor/' );
define( 'NACEP_EM_UNIQUE_SHORTCODE_PATH', NACEP_EM_SHORTCODE_BASE_PATH . 'widgets/charity-unique/' );
define( 'NACEP_EM_SHORTCODE_PATH', NACEP_EM_SHORTCODE_BASE_PATH . 'widgets/charity/' );
define( 'NACEP_EM_BASIC_SHORTCODE_PATH', NACEP_EM_SHORTCODE_BASE_PATH . 'widgets/basic/' );

// Initial File
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('charity-addon-for-elementor/charity-addon-for-elementor.php')) {
  if ( file_exists( NACEP_EM_SHORTCODE_BASE_PATH . '/em-setup.php' ) ){
    require_once( NACEP_EM_SHORTCODE_BASE_PATH . '/em-setup.php' );
  }
}

// Plugin language
if ( ! function_exists( 'nacharity_plugin_language_setup' ) ) {
  function nacharity_plugin_language_setup() {
    load_plugin_textdomain( 'charity-addon-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
  }
  add_action( 'init', 'nacharity_plugin_language_setup' );
}

// Check if Elementor installed and activated
if ( ! function_exists( 'nacharity_load_plugin' ) ) {
  function nacharity_load_plugin() {
    if ( ! did_action( 'elementor/loaded' ) ) {
      add_action( 'admin_notices', 'admin_notice_missing_main_plugin' );
      return;
    }
  }
  add_action( 'plugins_loaded', 'nacharity_load_plugin' );
}

// Warning when the site doesn't have Elementor installed or activated.
if ( ! function_exists( 'admin_notice_missing_main_plugin' ) ) {
  function admin_notice_missing_main_plugin() {
    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor */
      esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'charity-addon-for-elementor' ),
      '<strong>' . esc_html__( 'Charity Addon for Elementor', 'charity-addon-for-elementor' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'charity-addon-for-elementor' ) . '</strong>'
    );
    printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
  }
}

// Enqueue Files for FrontEnd
if ( ! function_exists( 'nacharity_scripts_styles' ) ) {
  function nacharity_scripts_styles() {
    // Styles
    wp_enqueue_style( 'font-awesome', NACEP_PLUGIN_CSS . '/font-awesome.min.css', array(), '4.7.0', 'all' );
    wp_enqueue_style( 'animate', NACEP_PLUGIN_CSS .'/animate.min.css', array(), '3.7.2', 'all' );
    wp_enqueue_style( 'themify-icons', NACEP_PLUGIN_CSS .'/themify-icons.min.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'linea', NACEP_PLUGIN_CSS .'/linea.min.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'magnific-popup', NACEP_PLUGIN_CSS .'/magnific-popup.min.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'owl-carousel', NACEP_PLUGIN_CSS .'/owl.carousel.min.css', array(), '2.3.4', 'all' );
    wp_enqueue_style( 'juxtapose', NACEP_PLUGIN_CSS .'/juxtapose.css', array(), '1.2.1', 'all' );
    wp_enqueue_style( 'nacharity-styles', NACEP_PLUGIN_CSS .'/styles.css', array(), '1.2.6', 'all' );
    wp_enqueue_style( 'nacharity-responsive', NACEP_PLUGIN_CSS .'/responsive.css', array(), '1.0', 'all' );

    // Scripts
    wp_enqueue_script( 'waypoints', NACEP_PLUGIN_SCRIPTS . '/jquery.waypoints.min.js', array( 'jquery' ), '4.0.1', true );
    wp_enqueue_script( 'imagesloaded', NACEP_PLUGIN_SCRIPTS . '/imagesloaded.pkgd.min.js', array( 'jquery' ), '4.1.4', true );
    wp_enqueue_script( 'magnific-popup', NACEP_PLUGIN_SCRIPTS . '/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
    wp_enqueue_script( 'circle-progress', NACEP_PLUGIN_SCRIPTS . '/circle-progress.min.js', array( 'jquery' ), '1.2.1', true );
    wp_enqueue_script( 'juxtapose', NACEP_PLUGIN_SCRIPTS . '/juxtapose.js', array( 'jquery' ), '1.2.1', true );
    wp_enqueue_script( 'typed', NACEP_PLUGIN_SCRIPTS . '/typed.min.js', array( 'jquery' ), '2.0.11', true );
    wp_enqueue_script( 'owl-carousel', NACEP_PLUGIN_SCRIPTS . '/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );
    wp_enqueue_script( 'countdown-plugin', NACEP_PLUGIN_SCRIPTS . '/jquery.plugin.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'countdown', NACEP_PLUGIN_SCRIPTS . '/jquery.countdown.min.js', array( 'jquery' ), '2.1.0', true );
    wp_enqueue_script( 'matchheight', NACEP_PLUGIN_SCRIPTS . '/jquery.matchHeight.min.js', array( 'jquery' ), '0.7.2', true );
    wp_enqueue_script( 'isotope', NACEP_PLUGIN_SCRIPTS . '/isotope.min.js', array( 'jquery' ), '3.0.6', true );
    wp_enqueue_script( 'packery-mode', NACEP_PLUGIN_SCRIPTS . '/packery-mode.pkgd.min.js', array( 'jquery' ), '2.0.1', true );
    wp_enqueue_script( 'nacharity-scripts', NACEP_PLUGIN_SCRIPTS . '/scripts.js', array( 'jquery' ), '1.0', true );
  }
  add_action( 'wp_enqueue_scripts', 'nacharity_scripts_styles' );
}
