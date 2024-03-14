<?php
/*
Plugin Name: Rublon Multi-Factor Authentication (MFA)
Text Domain: rublon
Plugin URI: http://wordpress.org/plugins/rublon/
Description: Rublon is cloud-based cybersecurity software that helps companies protect their users, data and applications by providing trusted access via easy-to-use multi-factor authentication.
Version: 4.4.3
Author: Rublon
Author URI: https://rublon.com
License: http://opensource.org/licenses/gpl-license.php GNU Public License, version 2 
*/

/*
 * Define some constants for future usage
*/

define('RUBLON2FACTOR_PLUGIN_URL', plugins_url() . '/' . basename(dirname(__FILE__)));
define('RUBLON2FACTOR_BASE_PATH', dirname(plugin_basename(__FILE__)));
define('RUBLON2FACTOR_PLUGIN_PATH', __FILE__);

/**
 * Ensure proper version migration
 **/

function rublon2factor_add_settings_link($links, $file)
{

    static $rublon2factor_plugin = null;

    if (is_null($rublon2factor_plugin)) {
        $rublon2factor_plugin = plugin_basename(__FILE__);
    }

    if ($file == $rublon2factor_plugin) {
        $settings_link = '<a href="admin.php?page=rublon">' . __('Settings', 'rublon') . '</a>';
        array_unshift($links, $settings_link);
    }
    return $links;

}

add_filter('plugin_action_links', 'rublon2factor_add_settings_link', 10, 2);


// For compatibility with version 3.5.x
if (!function_exists('wp_get_session_token')) {
    function wp_get_session_token()
    {
        $cookie = wp_parse_auth_cookie('', 'logged_in');
        return !empty($cookie['token']) ? $cookie['token'] : '';
    }
}

/* 
 * For compatibility with WP version < 4.0
 * wp_destroy_current_session available since WP 4.0
 */
if (!function_exists('wp_destroy_current_session')) {
    function wp_destroy_current_session()
    {
        wp_logout();
    }
}

require dirname(RUBLON2FACTOR_PLUGIN_PATH) . '/vendor/autoload.php';

// If AutoloadVerifier::class exists it means that classes are loaded using composer autoloader
if (!class_exists(Rublon_WordPress\Libs\Autoload\AutoloadVerifier::class)) {
    require_once dirname(__FILE__) . '/includes/Libs/Autoload/Psr4AutoloaderClass.php';
    $loader = new Psr4AutoloaderClass();
    $loader->addNamespace('Rublon_WordPress', dirname(__FILE__) . '/includes');
    $loader->register();
}

/*
 * Include files which are not compatible with PSR-4 autoload.
*/
require_once dirname(__FILE__) . '/includes/Libs/Classes/class-rublon-transients.php';
require_once dirname(__FILE__) . '/includes/Libs/Classes/class-rublon-pointers.php';
require_once dirname(__FILE__) . '/includes/Libs/Classes/class-rublon-garbage-man.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_config.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_helper.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_multisite_helper.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_cookies.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_requests.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_initialization.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_admin.php';
require_once dirname(__FILE__) . '/includes/rublon2factor_hooks.php';

// Initialize rublon2factor plug-in
add_action('plugins_loaded', 'rublon2factor_plugins_loaded', 9);
