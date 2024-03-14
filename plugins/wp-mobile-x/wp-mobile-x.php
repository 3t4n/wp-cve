<?php
/**
 * Plugin Name: WP Mobile X
 * Plugin URI: https://www.wpcom.cn
 * Description: Enable a theme for mobile device
 * Version: 1.4.0
 * Author: WPCOM
 * Author URI: https://www.wpcom.cn
 * Network: True
 * Requires PHP: 7.0
 */

define( 'MobX_VERSION', '1.4.0' );

if(DIRECTORY_SEPARATOR === "\\"){
    $dir = str_replace(untrailingslashit(ABSPATH), '', plugin_dir_path( __FILE__ ));
    $dir = untrailingslashit(ABSPATH) . str_replace('\\', '/', $dir);
}else{
    $dir = plugin_dir_path( __FILE__ );
}
define( 'MobX_DIR', $dir );
define( 'MobX_URL', plugins_url( '/', __FILE__ ) );

if (!defined('WPCOM_ADMIN_FREE_PATH')) {
    define('WPCOM_ADMIN_FREE_PATH', is_dir($framework_path = plugin_dir_path(__FILE__) . '/admin/') ? $framework_path : plugin_dir_path(__DIR__) . '/Themer-free/admin/');
    define('WPCOM_ADMIN_FREE_URI', is_dir($framework_path) ? plugins_url('/admin/', __FILE__) : plugins_url('/Themer-free/admin/', __DIR__));
}

require_once MobX_DIR . 'includes/class-mobile-x.php';