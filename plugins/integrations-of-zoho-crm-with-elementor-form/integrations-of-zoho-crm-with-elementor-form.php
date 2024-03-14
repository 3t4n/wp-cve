<?php

/**
 * Plugin Name: Integrations of Zoho CRM with Elementor form
 * Plugin URI:  https://formsintegrations.com/elementor-forms-integration-with-zoho-crm
 * Description: This plugin integrates Elementor forms with Zoho CRM
 * Version:     1.0.3
 * Author:      Forms Integrations
 * Author URI:  https://formsintegrations.com
 * Text Domain: elementor-to-zoho-crm
 * Requires PHP: 5.6
 * Domain Path: /languages
 * License: GPLv2 or later
 */

/***
 * If try to direct access  plugin folder it will Exit
 **/
if (!defined('ABSPATH')) {
    exit;
}

// Define most essential constants.
define('IZCRMEF_VERSION', '1.0.3');
define('IZCRMEF_DB_VERSION', '1.0.0');
define('IZCRMEF_PLUGIN_MAIN_FILE', __FILE__);

require_once plugin_dir_path(__FILE__) . 'includes/loader.php';
if (!function_exists('izcrmef_activate_plugin')) {
    function izcrmef_activate_plugin()
    {
        global $wp_version;
        if (version_compare($wp_version, '5.1', '<')) {
            wp_die(
                esc_html__('This plugin requires WordPress version 5.1 or higher.', 'elementor-to-zoho-crm'),
                esc_html__('Error Activating', 'elementor-to-zoho-crm')
            );
        }
        if (version_compare(PHP_VERSION, '5.6.0', '<')) {
            wp_die(
                esc_html__('Forms Integrations requires PHP version 5.6.', 'elementor-to-zoho-crm'),
                esc_html__('Error Activating', 'elementor-to-zoho-crm')
            );
        }
        do_action('izcrmef_activation');
    }
}

register_activation_hook(__FILE__, 'izcrmef_activate_plugin');

if (!function_exists('izcrmef_deactivation')) {
    function izcrmef_deactivation()
    {
        do_action('izcrmef_deactivation');
    }
}
register_deactivation_hook(__FILE__, 'izcrmef_deactivation');

if (!function_exists('izcrmef_uninstall_plugin')) {
    function izcrmef_uninstall_plugin()
    {
        do_action('izcrmef_uninstall');
    }
}
register_uninstall_hook(__FILE__, 'izcrmef_uninstall_plugin');
