<?php

/**
 * Easy Related Posts 
 *
 * @package   Easy related posts
 * @author    Panagiotis Vagenas <pan.vagenas@gmail.com>
 * @link      http://erp.xdark.eu
 * @copyright 2014 Panagiotis Vagenas <pan.vagenas@gmail.com>
 *
 * @wordpress-plugin
 * Plugin Name:       Easy Related Posts 
 * Plugin URI:        http://erp.xdark.eu
 * Description:       A powerfull plugin to display related posts
 * Version:           2.0.2
 * Author:            Panagiotis Vagenas <pan.vagenas@gmail.com>
 * Author URI:        http://erp.xdark.eu
 * Text Domain:       easy-related-posts-eng
 * Domain Path:       /languages
 */
// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}
/* ----------------------------------------------------------------------------*
 * Global definitions
 * ---------------------------------------------------------------------------- */
if (!defined('ERP_SLUG')) {
    define('ERP_SLUG', 'erp');
}
if (!defined('EPR_MAIN_OPTIONS_ARRAY_NAME')) {
    define('EPR_MAIN_OPTIONS_ARRAY_NAME', ERP_SLUG . '_main_options');
}
if (!defined('EPR_BASE_PATH')) {
    define('EPR_BASE_PATH', plugin_dir_path(__FILE__));
}
if (!defined('EPR_BASE_URL')) {
    define('EPR_BASE_URL', plugin_dir_url(__FILE__));
}
if (!defined('EPR_DEFAULT_THUMBNAIL')) {
    define('EPR_DEFAULT_THUMBNAIL', plugin_dir_url(__FILE__) . 'front/assets/img/noImage.png');
}
if (!defined('ERP_RELATIVE_TABLE')) {
    define('ERP_RELATIVE_TABLE', ERP_SLUG . '_related');
}

/* ----------------------------------------------------------------------------*
 * Core classes
 * ---------------------------------------------------------------------------- */

if (!class_exists('erpDefaults')) {
    require_once EPR_BASE_PATH . 'core/options/erpDefaults.php';
}
if (!class_exists('erpPaths')) {
    require_once EPR_BASE_PATH . 'core/helpers/erpPaths.php';
}

/* ----------------------------------------------------------------------------*
 * Public-Facing Functionality
 * ---------------------------------------------------------------------------- */
erpPaths::requireOnce(erpPaths::$erpWidget);
erpPaths::requireOnce(erpPaths::$easyRelatedPosts);

/*
 * Register hooks that are fired when the plugin is activated or deactivated.
 * When the plugin is deleted, the uninstall.php file is loaded.
 */
register_activation_hook(__FILE__, array('easyRelatedPosts', 'activate'));
register_deactivation_hook(__FILE__, array('easyRelatedPosts', 'deactivate'));

/**
 * Register plugin and widget
 */
add_action('plugins_loaded', array('easyRelatedPosts', 'get_instance'));

function regERPWidget() {
    register_widget("ERP_Widget");
}

add_action('widgets_init', 'regERPWidget');
/* ----------------------------------------------------------------------------*
 * Dashboard and Administrative Functionality
 * ---------------------------------------------------------------------------- */
/**
 */
if (is_admin()) {
    erpPaths::requireOnce(erpPaths::$easyRelatedPostsAdmin);
    erpPaths::requireOnce(erpPaths::$WP_Admin_Notices);
    add_action('plugins_loaded', array('easyRelatedPostsAdmin', 'get_instance'));
}
