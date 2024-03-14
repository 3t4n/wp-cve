<?php

/**
 * WP Maintenance Page
 *
 * Plugin Name: WP Maintenance Page
 * Plugin URI: https://twitter.com/kosugi_kun/
 * Description: You can easily place your page into maintenance mode. You can customize and create your own maintenance page.
 * Version: 1.2.18
 * Author: kosugikun
 * Author URI: https://twitter.com/kosugi_kun/
 * Twitter: kosugi_kun
 * GitHub Plugin URI: https://github.com/kosugikun/wp-maintenance-page
 * GitHub Branch: master
 * Text Domain: wp-maintenance-page
 * License: GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /languages
 */
// Exit if accessed directly
if (!defined('ABSPATH'))
    exit;

/**
 * DEFINE PATHS
 */
define('WPMP_PATH', plugin_dir_path(__FILE__));
define('WPMP_CLASSES_PATH', WPMP_PATH . 'includes/classes/');
define('WPMP_FUNCTIONS_PATH', WPMP_PATH . 'includes/functions/');
define('WPMP_LANGUAGES_PATH', basename(WPMP_PATH) . '/languages/');
define('WPMP_VIEWS_PATH', WPMP_PATH . 'views/');
define('WPMP_CSS_PATH', WPMP_PATH . 'assets/css/');

/**
 * DEFINE URLS
 */
define('WPMP_URL', plugin_dir_url(__FILE__));
define('WPMP_JS_URL', WPMP_URL . 'assets/js/');
define('WPMP_CSS_URL', WPMP_URL . 'assets/css/');
define('WPMP_IMAGES_URL', WPMP_URL . 'assets/images/');
define('WPMP_AUTHOR_UTM', '?utm_source=wpplugin&utm_medium=wpmaintenance');

/**
 * OTHER DEFINES
 */
define('WPMP_ASSETS_SUFFIX', (defined('SCRIPT_DEBUG') && SCRIPT_DEBUG) ? '' : '.min');

/**
 * FUNCTIONS
 */
require_once(WPMP_FUNCTIONS_PATH . 'helpers.php');
if (is_multisite() && !function_exists('is_plugin_active_for_network')) {
    require_once( ABSPATH . '/wp-admin/includes/plugin.php' );
}

/**
 * FRONTEND
 */
require_once(WPMP_CLASSES_PATH . 'wp-maintenance-page-shortcodes.php');
require_once(WPMP_CLASSES_PATH . 'wp-maintenance-page.php');
register_activation_hook(__FILE__, array('WP_Maintenance_Page', 'activate'));
register_deactivation_hook(__FILE__, array('WP_Maintenance_Page', 'deactivate'));

add_action('plugins_loaded', array('WP_Maintenance_Page', 'get_instance'));

/**
 * DASHBOARD
 */
if (is_admin()) {
    require_once(WPMP_CLASSES_PATH . 'wp-maintenance-page-admin.php');
    add_action('plugins_loaded', array('WP_Maintenance_Page_Admin', 'get_instance'));
}