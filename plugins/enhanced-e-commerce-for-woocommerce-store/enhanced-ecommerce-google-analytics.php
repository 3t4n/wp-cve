<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              conversios.io
 * @since             1.0.0
 * @package           Enhanced E-commerce for Woocommerce store
 *
 * @wordpress-plugin
 * Plugin Name:       Conversios.io - All-in-one Google Analytics, Pixels and Product Feed Manager for WooCommerce
 * Plugin URI:        https://www.conversios.io/
 * Description:       Track ecommerce events and conversions for GA4 and for the ad channels like Google Ads, Facebook, Tiktok, Snapchat and more. Automate end to end server side tracking. Create quality feeds for google shopping, tiktok, facebook and more. Leverage data driven decision making by enhanced ecommerce reporting and AI powered insights to increase sales.
 * Version:           7.0.5
 * Author:            Conversios
 * Author URI:        conversios.io
 * License:           GPLv3
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain:       enhanced-e-commerce-for-woocommerce-store
 * Domain Path:       /languages
 * WC requires at least: 3.5.0
 * WC tested up to: 8.6.1
 */

/**
 * If this file is called directly, abort.
 */
if (!defined('WPINC')) {
    die;
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */

function is_EeAioPro_active()
{
    if (!function_exists('is_plugin_active')) {
        include_once(ABSPATH . 'wp-admin/includes/plugin.php');
    }
    return is_plugin_active('enhanced-e-commerce-pro-for-woocommerce-store/enhanced-ecommerce-pro-google-analytics.php');
}

add_action('before_woocommerce_init', function () {
    if (class_exists(\Automattic\WooCommerce\Utilities\FeaturesUtil::class)) {
        \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', __FILE__, true);
    }
});

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-enhanced-ecommerce-google-analytics-activator.php
 */

function activate_enhanced_ecommerce_google_analytics()
{
    if (is_EeAioPro_active()) {
        deactivate_plugins(plugin_basename(__FILE__));
        wp_safe_redirect(esc_url(site_url() . "/wp-admin/plugins.php?convmsg=pro_already_installed"));
        exit;
    }
    require_once plugin_dir_path(__FILE__) . 'includes/class-enhanced-ecommerce-google-analytics-activator.php';
    Enhanced_Ecommerce_Google_Analytics_Activator::activate();
    set_transient('_conversios_activation_redirect', 1, 30);
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-enhanced-ecommerce-google-analytics-deactivator.php
 */
function deactivate_enhanced_ecommerce_google_analytics()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-enhanced-ecommerce-google-analytics-deactivator.php';
    Enhanced_Ecommerce_Google_Analytics_Deactivator::deactivate();
    wp_clear_scheduled_hook('tvc_add_cron_interval_for_product_sync');
}
register_activation_hook(__FILE__, 'activate_enhanced_ecommerce_google_analytics');
register_deactivation_hook(__FILE__, 'deactivate_enhanced_ecommerce_google_analytics');


if (is_EeAioPro_active()) {
    return;
}


define('PLUGIN_TVC_VERSION', '7.0.5');
$fullName = plugin_basename(__FILE__);
$dir = str_replace('/enhanced-ecommerce-google-analytics.php', '', $fullName);

//APP ID
if (!defined('CONV_APP_ID')) {
    define('CONV_APP_ID', 1);
}
//Screen ID
if (!defined('CONV_SCREEN_ID')) {
    define('CONV_SCREEN_ID', 'conversios_page_');
}
//Top Menu
if (!defined('CONV_TOP_MENU')) {
    define('CONV_TOP_MENU', 'Conversios');
}
//Menu Slug
if (!defined('CONV_MENU_SLUG')) {
    define('CONV_MENU_SLUG', 'conversios');
}

if (!defined('ENHANCAD_PLUGIN_NAME')) {
    define('ENHANCAD_PLUGIN_NAME', $dir);
}
// Store the directory of the plugin
if (!defined('ENHANCAD_PLUGIN_DIR')) {
    define('ENHANCAD_PLUGIN_DIR', plugin_dir_path(__FILE__));
}
// Store the url of the plugin
if (!defined('ENHANCAD_PLUGIN_URL')) {
    define('ENHANCAD_PLUGIN_URL', plugins_url() . '/' . ENHANCAD_PLUGIN_NAME);
}

if (!defined('TVC_API_CALL_URL')) {
    define('TVC_API_CALL_URL', 'https://connect.tatvic.com/laravelapi/public/api');
}

if (!defined('TVC_AUTH_CONNECT_URL')) {
    define('TVC_AUTH_CONNECT_URL', 'conversios.io');
}

if (!defined('TVC_Admin_Helper')) {
    include(ENHANCAD_PLUGIN_DIR . '/admin/class-tvc-admin-helper.php');
}

if (!defined('CNVS_LOG')) {
    define('CNVS_LOG', ENHANCAD_PLUGIN_DIR . 'logs/');
}

add_action('upgrader_process_complete', 'tvc_upgrade_function', 10, 2);
function tvc_upgrade_function($upgrader_object, $options)
{
    $fullName = plugin_basename(__FILE__);
    if ($options['action'] == 'update' && $options['type'] == 'plugin' && is_array($options['plugins'])) {
        foreach ($options['plugins'] as $each_plugin) {
            if ($each_plugin == $fullName) {
                $TVC_Admin_Helper = new TVC_Admin_Helper();
                $TVC_Admin_Helper->update_app_status();
            }
        }
    }
}


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-enhanced-ecommerce-google-analytics.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */

function run_enhanced_ecommerce_google_analytics()
{

    $plugin = new Enhanced_Ecommerce_Google_Analytics();
    $plugin->run();
}
run_enhanced_ecommerce_google_analytics();
