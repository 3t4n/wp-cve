<?php
/*
Plugin Name: Education Addon for Elementor
Plugin URI: https://nicheaddons.com/demos/education
Description: Education Addon covers all the Must-Have elements for creating a perfect Education website using Elementor Page Builder. 15+ Unique & Basic Elementor widget covers all of the Education elements.
Author: NicheAddons
Author URI: https://nicheaddons.com/
Version: 1.3.1
Text Domain: education-addon
*/

include_once ABSPATH . 'wp-admin/includes/plugin.php';

// Pro Codes
// Create a helper function for easy SDK access.
if ( !function_exists( 'naedu_fs' ) ) {
	function naedu_fs() {
	  global  $naedu_fs;

	  if ( !isset( $naedu_fs ) ) {
	    // Include Freemius SDK.
	    require_once dirname( __FILE__ ) . '/freemius/start.php';
	    $naedu_fs = fs_dynamic_init( array(
	      'id'             => '8305',
	      'slug'           => 'education-addon',
	      'type'           => 'plugin',
	      'public_key'     => 'pk_2b48a52722d53254a50b463b5088e',
	      'is_premium'          => true,
        'premium_suffix'      => 'Premium',
	      'has_premium_version' => true,
	      'has_addons'          => false,
	      'has_paid_plans'      => true,
	      'trial'               => array(
	        'days'               => 7,
	        'is_require_payment' => true,
	      ),
	      'menu'           => array(
	        'slug'           => 'naedu_admin_page',
	        'override_exact' => true,
	        'support'        => false,
	        'parent'         => array(
	          'slug' => 'naedu_admin_page',
	        ),
	      ),
	      'is_live'        => true,
	      // Set the SDK to work in a sandbox mode (for development & testing).
	      // IMPORTANT: MAKE SURE TO REMOVE SECRET KEY BEFORE DEPLOYMENT.
	      'secret_key'          => 'sk_g8X8#_lg#dP+ll:j)ACKi=ZIk0Q0Q',
	    ) );
	  }
	  return $naedu_fs;
	}

	// Init Freemius.
	naedu_fs();
	// Signal that SDK was initiated.
	do_action( 'naedu_fs_loaded' );
	function naedu_fs_settings_url() {
	  return admin_url( 'admin.php?page=naedu_admin_page' );
	}

	naedu_fs()->add_filter( 'connect_url', 'naedu_fs_settings_url' );
	naedu_fs()->add_filter( 'after_skip_url', 'naedu_fs_settings_url' );
	naedu_fs()->add_filter( 'after_connect_url', 'naedu_fs_settings_url' );
	naedu_fs()->add_filter( 'after_pending_connect_url', 'naedu_fs_settings_url' );

}

/**
* Enqueue Files for BackEnd
*/
function naedu_admin_scripts_styles() {
  wp_enqueue_style( 'naedu-admin-styles', plugins_url( '/', __FILE__ ) . 'assets/css/admin-styles.css', true );
}
add_action( 'admin_enqueue_scripts', 'naedu_admin_scripts_styles' );

// Admin Pages
require_once plugin_dir_path( __FILE__ ) . '/elementor/naedu-admin-page.php';
require_once plugin_dir_path( __FILE__ ) . '/elementor/naedu-admin-sub-page.php';
require_once plugin_dir_path( __FILE__ ) . '/elementor/naedu-admin-basic-fields.php';
require_once plugin_dir_path( __FILE__ ) . '/elementor/naedu-admin-pro-fields.php';
add_action( 'admin_init', 'naedu_bw_settings_init' );
add_action( 'admin_init', 'naedu_uw_settings_init' );

add_action( 'admin_menu', 'naedu_admin_menu' );
function naedu_admin_menu() {
  add_menu_page(
    'Education Addon',
    'Education Addon',
    'manage_options',
    'naedu_admin_page',
    'naedu_admin_page',
    'dashicons-carrot',
    80
  );
  add_submenu_page(
    'naedu_admin_page',
    'Enable & Disable',
    'Enable & Disable',
    'manage_options',
    'naedu_admin_sub_page',
    'naedu_admin_sub_page'
  );
}

// ABSPATH
function naedu_block_direct_access() {
  if ( !defined( 'ABSPATH' ) ) {
    exit( 'Forbidden' );
  }
}

// Initial File
// Only for free users
if ( naedu_fs()->is_free_plan() ) {
  if ( is_plugin_active( 'elementor/elementor.php' ) && is_plugin_active( 'education-addon/education-addon.php' ) ) {
    if ( file_exists( plugin_dir_path( __FILE__ ) . '/elementor/em-setup.php' ) ) {
      require_once plugin_dir_path( __FILE__ ) . '/elementor/em-setup.php';
    }
  }
}
// is_premium 

// Only for premium users
if ( naedu_fs()->is__premium_only() ) {
  // Only if the user in a trial mode or have a valid license.
  if ( naedu_fs()->can_use_premium_code() ) {

    if ( is_plugin_active( 'elementor/elementor.php' ) && is_plugin_active( 'education-addon-pro/education-addon.php' ) ) {
      if ( file_exists( plugin_dir_path( __FILE__ ) . '/elementor/em-setup.php' ) ) {
      }
    }

  } // can_premium
} // is_premium

// Development Purpose
require_once plugin_dir_path( __FILE__ ) . '/elementor/em-setup.php';

// Plugin language
function naedu_plugin_language_setup() {
  load_plugin_textdomain( 'education-addon', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
}
add_action( 'init', 'naedu_plugin_language_setup' );

// Check if Elementor installed and activated
function naedu_load_plugin() {
  if ( !did_action( 'elementor/loaded' ) ) {
    add_action( 'admin_notices', 'naedu_missing_main_plugin' );
    return;
  }
}
add_action( 'plugins_loaded', 'naedu_load_plugin' );

// Warning when the site doesn't have Elementor installed or activated.
function naedu_missing_main_plugin() {
  if ( isset( $_GET['activate'] ) ) {
    unset( $_GET['activate'] );
  }
  $message = sprintf(
    /* translators: 1: Plugin name 2: Elementor */
    esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'education-addon' ),
    '<strong>' . esc_html__( 'Education Addon', 'education-addon' ) . '</strong>',
    '<strong>' . esc_html__( 'Elementor', 'education-addon' ) . '</strong>'
  );
  printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
}

// Both Free and Pro activated
if ( is_plugin_active( 'education-addon/education-addon.php' ) && is_plugin_active( 'education-addon-pro/education-addon.php' ) ) {
  add_action( 'admin_notices', 'naedu_deactivate_free' );
}

// Warning when the site have Both Free and Pro activated.
function naedu_deactivate_free() {
  if ( isset( $_GET['activate'] ) ) {
    unset( $_GET['activate'] );
  }
  $message = sprintf(
    /* translators: 1: Plugin name */
    esc_html__( 'Please deactivate the free version of "%1$s".', 'education-addon' ),
    '<strong>' . esc_html__( 'Education Addon', 'education-addon' ) . '</strong>'
  );
  printf( '<div class="notice notice-error is-dismissible"><p>%1$s</p></div>', $message );
}

// Enable & Dissable Notice
add_action( 'admin_notices', 'naedu_enable_dissable' );
function naedu_enable_dissable() {
  if ( isset( $_GET['settings-updated'] ) ) {
    $message = sprintf( esc_html__( 'Widgets Settings Saved.', 'education-addon' ) );
    printf( '<div class="notice notice-success is-dismissible"><p>%1$s</p></div>', $message );
  }
}

// Enqueue Files for Elementor Editor
if ( is_plugin_active( 'elementor/elementor.php' ) ) {
  // Css Enqueue
  add_action( 'elementor/editor/before_enqueue_scripts', function () {
    wp_enqueue_style(
      'naedu-ele-editor-linea',
      plugins_url( '/', __FILE__ ) . 'assets/css/linea.min.css',
      [],
      '1.0.0'
    );
    wp_enqueue_style(
      'naedu-ele-editor-themify',
      plugins_url( '/', __FILE__ ) . 'assets/css/themify-icons.min.css',
      [],
      '1.0.0'
    );
    wp_enqueue_style(
      'naedu-ele-editor-icofont',
      plugins_url( '/', __FILE__ ) . 'assets/css/icofont.min.css',
      [],
      '1.0.1'
    );
  } );
}

// Enqueue Files for FrontEnd
function naedu_scripts_styles() {
  // Styles
  wp_enqueue_style(
    'niche-frame',
    plugins_url( '/', __FILE__ ) . 'assets/css/niche-frame.css',
    array(),
    '1.0',
    'all'
  );
  wp_enqueue_style(
    'font-awesome',
    plugins_url( '/', __FILE__ ) . 'assets/css/font-awesome.min.css',
    array(),
    '4.7.0',
    'all'
  );
  wp_enqueue_style( 
    'animate', 
    plugins_url( '/', __FILE__ ) . 'assets/css/animate.min.css', 
    array(), 
    '3.7.2', 
    'all' 
  );
  wp_enqueue_style(
    'all',
    plugins_url( '/', __FILE__ ) . 'assets/css/all.min.css',
    array(),
    '5.15.2',
    'all'
  );
  wp_enqueue_style(
    'themify-icons',
    plugins_url( '/', __FILE__ ) . 'assets/css/themify-icons.min.css',
    array(),
    '1.0.0',
    'all'
  );
  wp_enqueue_style(
    'linea',
    plugins_url( '/', __FILE__ ) . 'assets/css/linea.min.css',
    array(),
    '1.0.0',
    'all'
  );
  wp_enqueue_style(
    'icofont',
    plugins_url( '/', __FILE__ ) . 'assets/css/icofont.min.css',
    array(),
    '1.0.1',
    'all'
  );
  wp_enqueue_style(
    'time-circles',
    plugins_url( '/', __FILE__ ) . 'assets/css/time-circles.css',
    array(),
    '1.0',
    'all'
  );
  wp_enqueue_style(
    'owl-carousel',
    plugins_url( '/', __FILE__ ) . 'assets/css/owl.carousel.min.css',
    array(),
    '2.3.4',
    'all'
  );
  wp_enqueue_style(
    'naedu-styles',
    plugins_url( '/', __FILE__ ) . 'assets/css/styles.css',
    array(),
    '1.0',
    'all'
  );
  wp_enqueue_style(
    'naedu-responsive',
    plugins_url( '/', __FILE__ ) . 'assets/css/responsive.css',
    array(),
    '1.0',
    'all'
  );

  // Scripts
  wp_enqueue_script(
    'waypoints',
    plugins_url( '/', __FILE__ ) . 'assets/js/jquery.waypoints.min.js',
    array( 'jquery' ),
    '4.0.1',
    true
  );
  wp_enqueue_script(
    'html5shiv',
    plugins_url( '/', __FILE__ ) . 'assets/js/html5shiv.min.js',
    array( 'jquery' ),
    '3.7.3',
    true
  );
  wp_enqueue_script(
    'time-circles',
    plugins_url( '/', __FILE__ ) . 'assets/js/time-circles.js',
    array( 'jquery' ),
    '1.0',
    true
  );
  wp_enqueue_script(
    'owl-carousel',
    plugins_url( '/', __FILE__ ) . 'assets/js/owl.carousel.min.js',
    array( 'jquery' ),
    '2.3.4',
    true
  );
  wp_enqueue_script(
    'naedu-scripts',
    plugins_url( '/', __FILE__ ) . 'assets/js/scripts.js',
    array( 'jquery' ),
    '1.0',
    true
  );
}
add_action( 'wp_enqueue_scripts', 'naedu_scripts_styles' );
