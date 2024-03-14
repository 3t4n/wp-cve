<?php
/**
 * Nouvello WeManage Worker
 *
 * @package     Nouvello WeManage Worker
 * @author      Nouvello Studio
 * @copyright   2023 Nouvello Studio. https://nouvellostudio.com
 *
 * Plugin Name: WEmanage App Worker
 * Description: Website management tool by WEmanage.
 * Version:     1.2.0
 * Author:      WEmanage
 * Author URI:  https://wemanage.app
 * Text Domain: ns-wmw
 * License: Private. You may not use this plugin without a proper license.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

define( 'NSWMW_ROOT_PATH', plugin_dir_path( __FILE__ ) );
define( 'NSWMW_ROOT_DIR', plugin_dir_url( __FILE__ ) );
define( 'NSWMW_BASE_NAME', plugin_basename( __FILE__ ) );
define( 'NSWMW_THEME_NAME', strtolower( wp_get_theme() ) );
define( 'NSWMW_VER', '1.2.0' ); // important - set version number here.

/**
 * Main class for Nouvello WeManage Worker.
 */
require NSWMW_ROOT_PATH . '/includes/class-nouvello-wemanage-worker.php';
