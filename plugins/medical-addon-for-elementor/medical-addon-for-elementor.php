<?php
/*
Plugin Name: Medical Addon for Elementor
Plugin URI: https://nicheaddons.com/demos/medical
Description: Medical Addon for Elementor covers all the must-needed elements for creating a perfect Medical website using Elementor Page Builder. 35+ Unique & Basic Elementor widget covers all of the Medical elements. Including book an appointment using most popular Appointment plugins. And also like, Admissions, Appointment, Working Hours, Departments, Stats, Tools, Full Calendar, Hospitals, Resources, Pricing, and Benefits.
Author: NicheAddons
Author URI: https://nicheaddons.com/
Version: 1.4
Text Domain: medical-addon-for-elementor
*/

// ABSPATH
if ( ! function_exists( 'namedical_block_direct_access' ) ) {
	function namedical_block_direct_access() {
		if ( ! defined( 'ABSPATH' ) ) {
			exit( 'Forbidden' );
		}
	}
}

// Plugin URL
define( 'NAMEP_PLUGIN_URL', plugins_url( '/', __FILE__ ) );

// Plugin PATH
define( 'NAMEP_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );
define( 'NAMEP_PLUGIN_ASTS', NAMEP_PLUGIN_URL . 'assets' );
define( 'NAMEP_PLUGIN_IMGS', NAMEP_PLUGIN_ASTS . '/images' );
define( 'NAMEP_PLUGIN_CSS', NAMEP_PLUGIN_ASTS . '/css' );
define( 'NAMEP_PLUGIN_SCRIPTS', NAMEP_PLUGIN_ASTS . '/js' );

// Medical Addon for Elementor Elementor Shortcode Path
define( 'NAMEP_EM_SHORTCODE_BASE_PATH', NAMEP_PLUGIN_PATH . 'elementor/' );
define( 'NAMEP_EM_UNIQUE_SHORTCODE_PATH', NAMEP_EM_SHORTCODE_BASE_PATH . 'widgets/medical-unique/' );
define( 'NAMEP_EM_SHORTCODE_PATH', NAMEP_EM_SHORTCODE_BASE_PATH . 'widgets/medical/' );
define( 'NAMEP_EM_BASIC_SHORTCODE_PATH', NAMEP_EM_SHORTCODE_BASE_PATH . 'widgets/basic/' );

// Initial File
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if (is_plugin_active('medical-addon-for-elementor/medical-addon-for-elementor.php')) {
  if ( defined('ELEMENTOR_PATH') && file_exists( NAMEP_EM_SHORTCODE_BASE_PATH . '/em-setup.php' ) ){
    require_once( NAMEP_EM_SHORTCODE_BASE_PATH . '/em-setup.php' );
  }
}

// Plugin language
if ( ! function_exists( 'namedical_plugin_language_setup' ) ) {
  function namedical_plugin_language_setup() {
    load_plugin_textdomain( 'medical-addon-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
  }
  add_action( 'init', 'namedical_plugin_language_setup' );
}

// Check if Elementor installed and activated
if ( ! function_exists( 'namedical_load_plugin' ) ) {
  function namedical_load_plugin() {
    if ( ! did_action( 'elementor/loaded' ) ) {
      add_action( 'admin_notices', 'admin_notice_missing_main_plugin' );
      return;
    }
  }
  add_action( 'plugins_loaded', 'namedical_load_plugin' );
}

// Warning when the site doesn't have Elementor installed or activated.
if ( ! function_exists( 'admin_notice_missing_main_plugin' ) ) {
  function admin_notice_missing_main_plugin() {
    if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );
    $message = sprintf(
      /* translators: 1: Plugin name 2: Elementor */
      esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'medical-addon-for-elementor' ),
      '<strong>' . esc_html__( 'Medical Addon for Elementor', 'medical-addon-for-elementor' ) . '</strong>',
      '<strong>' . esc_html__( 'Elementor', 'medical-addon-for-elementor' ) . '</strong>'
    );
    printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
  }
}

// Enqueue Files for FrontEnd
if ( ! function_exists( 'namedical_scripts_styles' ) ) {
  function namedical_scripts_styles() {
    // Styles
    wp_enqueue_style( 'niche-frame', NAMEP_PLUGIN_CSS .'/niche-frame.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'font-awesome', NAMEP_PLUGIN_CSS . '/font-awesome.min.css', array(), '4.7.0', 'all' );
    wp_enqueue_style( 'animate', NAMEP_PLUGIN_CSS .'/animate.min.css', array(), '3.7.2', 'all' );
    wp_enqueue_style( 'themify-icons', NAMEP_PLUGIN_CSS .'/themify-icons.min.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'linea', NAMEP_PLUGIN_CSS .'/linea.min.css', array(), '1.0.0', 'all' );
    wp_enqueue_style( 'magnific-popup', NAMEP_PLUGIN_CSS .'/magnific-popup.min.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'owl-carousel', NAMEP_PLUGIN_CSS .'/owl.carousel.min.css', array(), '2.3.4', 'all' );
    wp_enqueue_style( 'slick-theme', NAMEP_PLUGIN_CSS .'/slick-theme.min.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'slick', NAMEP_PLUGIN_CSS .'/slick.min.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'juxtapose', NAMEP_PLUGIN_CSS .'/juxtapose.css', array(), '1.2.1', 'all' );
    wp_enqueue_style( 'namedical-styles', NAMEP_PLUGIN_CSS .'/styles.css', array(), '1.0', 'all' );
    wp_enqueue_style( 'namedical-responsive', NAMEP_PLUGIN_CSS .'/responsive.css', array(), '1.0', 'all' );

    // Scripts
    wp_enqueue_script( 'waypoints', NAMEP_PLUGIN_SCRIPTS . '/jquery.waypoints.min.js', array( 'jquery' ), '4.0.1', true );
    wp_enqueue_script( 'imagesloaded', NAMEP_PLUGIN_SCRIPTS . '/imagesloaded.pkgd.min.js', array( 'jquery' ), '4.1.4', true );
    wp_enqueue_script( 'magnific-popup', NAMEP_PLUGIN_SCRIPTS . '/jquery.magnific-popup.min.js', array( 'jquery' ), '1.1.0', true );
    wp_enqueue_script( 'juxtapose', NAMEP_PLUGIN_SCRIPTS . '/juxtapose.js', array( 'jquery' ), '1.2.1', true );
    wp_enqueue_script( 'typed', NAMEP_PLUGIN_SCRIPTS . '/typed.min.js', array( 'jquery' ), '2.0.11', true );
    wp_enqueue_script( 'owl-carousel', NAMEP_PLUGIN_SCRIPTS . '/owl.carousel.min.js', array( 'jquery' ), '2.3.4', true );
    wp_enqueue_script( 'slick', NAMEP_PLUGIN_SCRIPTS . '/slick.min.js', array( 'jquery' ), '1.9.0', true );
    wp_enqueue_script( 'matchheight', NAMEP_PLUGIN_SCRIPTS . '/jquery.matchHeight.min.js', array( 'jquery' ), '0.7.2', true );
    wp_enqueue_script( 'isotope', NAMEP_PLUGIN_SCRIPTS . '/isotope.min.js', array( 'jquery' ), '3.0.6', true );
    wp_enqueue_script( 'counterup', NAMEP_PLUGIN_SCRIPTS . '/jquery.counterup.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_script( 'packery-mode', NAMEP_PLUGIN_SCRIPTS . '/packery-mode.pkgd.min.js', array( 'jquery' ), '2.1.2', true );
    wp_enqueue_script( 'namedical-scripts', NAMEP_PLUGIN_SCRIPTS . '/scripts.js', array( 'jquery' ), '1.0', true );
  }
  add_action( 'wp_enqueue_scripts', 'namedical_scripts_styles' );
}
