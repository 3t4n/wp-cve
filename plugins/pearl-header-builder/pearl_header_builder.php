<?php
/*
Plugin Name: WordPress Header Builder Plugin – Pearl
Plugin URI: https://stylemixthemes.com/headerbuilder/
Description: Pearl Header Builder gives you complete freedom to compose a header that perfectly suits your site.
Author: StylemixThemes
Author URI: https://stylemixthemes.com
Version: 1.3.6
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

define( 'STM_HB_VER', '1.3.6' );
define( 'STM_HB_DIR', plugin_dir_path( __FILE__ ) );
define( 'STM_HB_URL', plugins_url( '/', __FILE__ ) );
define( 'STM_HB_PATH', plugin_basename( __FILE__ ) );

require_once STM_HB_DIR . 'frontend/functions.php';

if ( ! is_textdomain_loaded( 'pearl-header-builder' ) ) {
	load_plugin_textdomain( 'pearl-header-builder', false, 'pearl-header-builder/languages' );
}

if ( is_admin() ) {
	$includes_path = STM_HB_DIR . 'includes/';
	require_once $includes_path . 'presets.php';
	require_once $includes_path . 'helpers.php';
	require_once $includes_path . 'screen.php';
	require_once $includes_path . 'enqueue.php';
	require_once $includes_path . 'js_translations.php';
}
