<?php

/**
  Plugin Name: Pluglab
  Plugin URI:
  Description: Pluglab contain all features which are required to create a complete website. Main motive behind this plugin is to boost up functionality of Unibird themes.
  Version: 0.2.7
  Author: UnibirdTech
  Text Domain: pluglab
  Author URI:
 */
defined( 'ABSPATH' ) || exit;

if ( ! defined( 'PL_PLUGIN_VERSION' ) ) {
	define( 'PL_PLUGIN_VERSION', '0.2.7' );
}

if ( ! defined( 'PL_PLUGIN_FILE' ) ) {
	define( 'PL_PLUGIN_FILE', __FILE__ );
}

if ( ! defined( 'PL_PLUGIN_DIR' ) ) {
	define( 'PL_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'PL_PLUGIN_URL' ) ) {
	define( 'PL_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'PL_PLUGIN_INC' ) ) {
	define( 'PL_PLUGIN_INC', PL_PLUGIN_DIR . 'includes/' );
}

if ( ! defined( 'PL_PLUGIN_INC_URL' ) ) {
	define( 'PL_PLUGIN_INC_URL', PL_PLUGIN_URL . 'includes/' );
}

require_once PL_PLUGIN_INC . 'class-pl-autoloader.php';

register_activation_hook( PL_PLUGIN_FILE, array( 'PL_Plugin', 'install' ) );

PL_Plugin::instance();
