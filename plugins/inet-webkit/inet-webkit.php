<?php
/**
 * Plugin Name: iNET Webkit
 * Plugin URI:  https://wordpress.org/plugins/inet-webkit-plugin/
 * Description: The best WordPress All-in-One plugin. Powered by iNET Software Co., Ltd.
 * Version:     1.1.6
 * Author:      iNET
 * Author URI:  https://inet.vn/hosting/inet-webkit-plugin
 * Text Domain: inet-webkit
 * Domain Path: /languages
 * License:     GPL2
 */

defined( 'ABSPATH' ) || exit;

if (!defined('INET_WK_FILE')) {
    define('INET_WK_FILE', __FILE__);
}

// Include the main iNET Webkit class.
if (!class_exists('INET_WK_Plugin', false)) {
    include_once dirname(INET_WK_FILE) . '/inc/class-inet-webkit.php';
}

function create_instance_inet_wk()
{
    return INET_WK_Plugin::instance();
}

function inet_wk_loader()
{
    load_plugin_textdomain('inet-webkit', false, basename(dirname(INET_WK_FILE)) . '/languages/');
    $GLOBALS['inet-webkit'] = create_instance_inet_wk();
}

add_action('plugins_loaded', 'inet_wk_loader');