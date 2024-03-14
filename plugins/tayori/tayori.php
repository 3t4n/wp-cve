<?php
/*
Plugin Name: Tayori
Plugin URI: http://tayori.com
Description: Tayori Form Plugin For WordPress.
Version: 1.2.9
Author: PRTIMES Inc.
Author URI: http://prtimes.co.jp
License: GPL2
Text Domain: tayori
Domain Path: /languages/
*/

define( 'TAYORI_VERSION', '1.2.9' );

define( 'TAYORI_REQUIRED_WP_VERSION', '4.1' );

define( 'TAYORI_PLUGIN', __FILE__ );

if (function_exists('plugin_basename')) {
  define( 'TAYORI_PLUGIN_BASENAME', plugin_basename( TAYORI_PLUGIN ) );
  define( 'TAYORI_PLUGIN_NAME', trim( dirname( TAYORI_PLUGIN_BASENAME ), '/' ) );
}

if ( ! defined( 'TAYORI_LOAD_JS' ) ) {
	define( 'TAYORI_LOAD_JS', true );
}

if ( ! defined( 'TAYORI_LOAD_CSS' ) ) {
	define( 'TAYORI_LOAD_CSS', true );
}

if ( ! defined( 'TAYORI_VERIFY_NONCE' ) ) {
	define( 'TAYORI_VERIFY_NONCE', true );
}

if (function_exists('untrailingslashit')) {
  define( 'TAYORI_PLUGIN_DIR', untrailingslashit( dirname( TAYORI_PLUGIN ) ) );
  define( 'TAYORI_PLUGIN_MODULES_DIR', TAYORI_PLUGIN_DIR . '/modules' );
  define( 'TAYORI_INLCLUDES_DIR', TAYORI_PLUGIN_DIR . '/includes' );

  // Deprecated, not used in the plugin core. Use tayori_plugin_url() instead.
  define( 'TAYORI_PLUGIN_URL', untrailingslashit( plugins_url( '', TAYORI_PLUGIN ) ) );

  require_once(TAYORI_INLCLUDES_DIR . '/tayori.php');
  register_activation_hook(__FILE__, array('Tayori', 'activate'));

  require_once TAYORI_PLUGIN_DIR . '/settings.php';
}

