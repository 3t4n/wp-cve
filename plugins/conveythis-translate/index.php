<?php
/*
Plugin Name: ConveyThis Translate
Plugin URI: https://www.conveythis.com/?utm_source=widget&utm_medium=wordpress
Description: Translate your WordPress site into over 100 languages using professional and instant machine translation technology. ConveyThis will help provide you with an SEO-friendy, multilingual website in minutes with no coding required.
Version: 221
Author: ConveyThis Translate Team
Author URI: https://www.conveythis.com/?utm_source=widget&utm_medium=wordpress
Text Domain: conveythis-translate
License: GPL2
*/

/**
 * Config
 */
require_once plugin_dir_path(__FILE__) .  "config.php";

/**
 * Add file templ
 */
require_once plugin_dir_path(__FILE__) .  "app/connect/DebugTest.php";
require_once plugin_dir_path(__FILE__) .  "app/connect/ConveyThisStart.php";

/**
 * Class
 */
require_once plugin_dir_path(__FILE__) . 'app/class/ConveyThis.php';
require_once plugin_dir_path(__FILE__) . 'app/class/ConveyThisWidget.php';
require_once plugin_dir_path(__FILE__) . 'app/class/ConveyThisAdminNotices.php';
require_once plugin_dir_path(__FILE__) . 'app/class/ConveyThisCache.php';
require_once plugin_dir_path(__FILE__) . 'app/class/ConveyThisCron.php';

if (
    isset($_POST['convey_event']) &&
    strlen($_POST['convey_event_name'] ) > 0
)
{
    $convey = ConveyThis::Instance();
    $convey::sendEvent($_POST['convey_event_name']);
    die(json_encode(true));
}

/**
 * Plugin
 */

register_activation_hook( __FILE__, array( 'ConveyThis', 'plugin_activate' ) );
register_deactivation_hook( __FILE__, array( 'ConveyThis', 'plugin_deactivate' ) );
register_uninstall_hook( __FILE__, array( 'ConveyThis', 'plugin_uninstall' ) );

add_action( 'plugins_loaded', array( 'ConveyThis', 'Instance' ), 10 );
add_action('activated_plugin', array('ConveyThis', 'redirect_after_activate'));
add_action( 'admin_bar_menu',  array( 'ConveyThis', 'modify_admin_bar' ), 999);

if (
    isset($_POST['api_key'])
)
{
    $convey_settings = ConveyThis::Instance();
    $convey_settings->getSettingsOnStart($_POST['api_key']);
}

/**
 * Cron
 */
// Start method
add_action('ConveyThisClearCache', array('ConveyThisCron', 'ClearCache'));
// Settings
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( 'ConveyThis', '_settings_link' ) );
// Connect cron
add_filter('cron_schedules', array('ConveyThisCron', 'ConveyThisСustomСronSchedule'));

register_activation_hook(__FILE__, array('ConveyThisCron', 'ConveyThisActivationCron'));
register_deactivation_hook(__FILE__, array('ConveyThisCron', 'ConveyThisDeactivationCron'));



