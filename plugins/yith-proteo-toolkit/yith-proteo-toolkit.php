<?php
/**
 * Plugin Name:         YITH Proteo Toolkit
 * Plugin URI:          https://yithemes.com
 * Description:         Add extra features and a setup wizard to YITH Proteo theme.
 * Version:             1.2.4
 * Author:              YITH
 * Author URI:          https://yithemes.com/
 * Requires at least:   5.3
 * Tested up to:        6.4
 *
 * Text Domain: yith-proteo-toolkit
 *
 * @package YITH_Proteo_tookit
 * @author YITH
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'YITH_PROTEO_TOOLKIT' ) ) {
	define( 'YITH_PROTEO_TOOLKIT', 'YITH_PROTEO_TOOLKIT' );
}

if ( ! defined( 'YITH_PROTEO_TOOLKIT_VERSION' ) ) {
	define( 'YITH_PROTEO_TOOLKIT_VERSION', '1.2.4' );
}

if ( ! defined( 'YITH_PROTEO_TOOLKIT_PATH' ) ) {
	define( 'YITH_PROTEO_TOOLKIT_PATH', plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'YITH_PROTEO_TOOLKIT_URL' ) ) {
	define( 'YITH_PROTEO_TOOLKIT_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'YITH_PROTEO_TOOLKIT_TEMPLATE_PATH' ) ) {
	define( 'YITH_PROTEO_TOOLKIT_TEMPLATE_PATH', YITH_PROTEO_TOOLKIT_PATH . 'templates/' );
}

require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/class-yith-proteo-toolkit.php';
require_once YITH_PROTEO_TOOLKIT_PATH . 'includes/yith-proteo-toolkit-utils.php';
