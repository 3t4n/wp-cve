<?php
/**
 * Plugin Name: wpsection
 * Plugin URI: https://wordpress.org/plugins/wpsection/
 * Description: This Plugin is customize with any Elementor Based Theme.
 * Version: 1.2.7
 * Author: Rashid87
 * Text Domain: wpsection
 * Domain Path: /languages/
 * Author URI: https://profiles.wordpress.org/rashid87/
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

// Links and Values

$plugin_version = '1.2.7';
$plugin_name = esc_html('wpsection');
$plugin_path = plugin_dir_path(__FILE__);
$plugin_url = plugins_url('wpsection') . '/';
$plugin_file = plugin_basename(__FILE__);
$plugin_dir = plugin_dir_path(__FILE__);
if (!defined('THEME_NAME')) {
    $theme_info = wp_get_theme();
    define('THEME_NAME', $theme_info->Name);
}
$theme_name = defined('THEME_NAME') ? esc_html(THEME_NAME) : esc_html('Theme');
//Customizable Link
$api_url = esc_url('https://wpsection.com/demo/');
$tutorial_link = 'https://wpsection.com/home/tutorial';
$doc_link = 'https://wpsection.com/home/doc';
$demo_link = 'https://wpsection.com/home/';
$contact_link = 'https://wpsection.com/home/contact/';
$details_link = 'https://wpsection.com/';
$plugin_donate = esc_url('https://wpsection.com/home/donate');
$plugin_pro = esc_url('https://wpsection.com/home/pro');
// Define Constants

defined('WPSECTION_THEME_NAME') || define('WPSECTION_THEME_NAME', $theme_name);
defined('WPSECTION_VERSION') || define('WPSECTION_VERSION', $plugin_version);
defined('WPSECTION_PLUGIN_NAME') || define('WPSECTION_PLUGIN_NAME', $plugin_name);
defined('WPSECTION_PLUGIN_PATH') || define('WPSECTION_PLUGIN_PATH', $plugin_path);
defined('WPSECTION_PLUGIN_URL') || define('WPSECTION_PLUGIN_URL', $plugin_url);
defined('WPSECTION_PLUGIN_FILE') || define('WPSECTION_PLUGIN_FILE', $plugin_file);
defined('WPSECTION_PLUGIN_TUTORIAL') || define('WPSECTION_PLUGIN_TUTORIAL', $tutorial_link);
defined('WPSECTION_PLUGIN_DOC') || define('WPSECTION_PLUGIN_DOC', $doc_link);
defined('WPSECTION_PLUGIN_DEMO') || define('WPSECTION_PLUGIN_DEMO', $demo_link);
defined('WPSECTION_PLUGIN_CONTACT') || define('WPSECTION_PLUGIN_CONTACT', $contact_link);
defined('WPSECTION_PLUGIN_DETAILS') || define('WPSECTION_PLUGIN_DETAILS', $details_link);
defined('WPSECTION_API_URL') || define('WPSECTION_API_URL', $api_url);
defined('WPSECTION_PLUGIN_DIR') || define('WPSECTION_PLUGIN_DIR', $plugin_dir);
defined('WPSECTION_PLUGIN_DONATE') || define('WPSECTION_PLUGIN_DONATE', $plugin_donate);
defined('WPSECTION_PLUGIN_PRO') || define('WPSECTION_PLUGIN_PRO', $plugin_pro);

final class WPSection {
    // Define plugin constants
    const PLUGIN_NAME = 'wpsection';
    const PLUGIN_SLUG = 'wpsection';

    // Define the expected class name
    const EXPECTED_CLASS_NAME = 'WPSection';

    // Constructor function
    function __construct() {

        if (get_class($this) !== self::EXPECTED_CLASS_NAME) {
            // The class name has been changed, so exit or handle accordingly
            die();
        }

        // Admin Script
        add_action('admin_enqueue_scripts', [$this, 'wpsection_admin_scripts']);
        
        // Register plugin activation and deactivation hooks
        register_activation_hook(__FILE__, [$this, 'activate']);
        register_deactivation_hook(__FILE__, [$this, 'deactivate']);

        // Register plugin installation and uninstallation hooks
        register_uninstall_hook(__FILE__, ['WPSection', 'uninstall']);

        // This is for Elementor Widget Settings
        require_once plugin_dir_path(__FILE__) . '/theme/elementor/settings.php';

        // This is require_one GLOB of this root
        function wpsection_include_files() {
            $plugin_path = plugin_dir_path(__FILE__);

            foreach (glob($plugin_path . '/plugin/require_once/*.php') as $file) {
                require_once $file;
            }
        }
        wpsection_include_files();

        // This is All the Custom Templates such as Footer, Block,
        function wpsection_templates_files() {
            $plugin_path = plugin_dir_path(__FILE__);

            foreach (glob($plugin_path . '/theme/custompost/*.php') as $file) {
                require_once $file;
            }
        }
        wpsection_templates_files();
		

        // Glob all the shop related functions and Classes
        function wpsection_shop_files() {
            $plugin_path = plugin_dir_path(__FILE__);

            foreach (glob($plugin_path . '/theme/shop/*.php') as $file) {
                require_once $file;
            }
        }
        wpsection_shop_files();

        // This is Dashboard code wps-setting is all default functions library
        function wpsection_dashboard_files() {
            include_once(WPSECTION_PLUGIN_DIR . 'plugin/dashboard/class-wps-settings.php');
            include_once(WPSECTION_PLUGIN_DIR . 'plugin/dashboard/class-functions.php');
            include_once(WPSECTION_PLUGIN_DIR . 'plugin/dashboard/class-hooks.php');
            include_once(WPSECTION_PLUGIN_DIR . 'plugin/dashboard/functions.php');
        }
        wpsection_dashboard_files();

        /* Cron Run Code */
        function plugin_activated() {
            // Adding cron schedule
            if (!wp_next_scheduled('wpsection_update_data')) {
                wp_schedule_event(time(), 'daily', 'wpsection_update_data');
            }
        }
        plugin_activated();
    }

    // Localize script is called for admin scripts
    function wpsection_localize_scripts() {
        return apply_filters('wpsection_filters_localize_scripts', array(
            'ajaxurl' => admin_url('admin-ajax.php'),
            'importingText' => esc_html__('Importing...', 'wpsection'),
            'confirmRemoveText' => esc_html__('Do you really want to remove this item?', 'wpsection'),
        ));
    }

    function wpsection_admin_scripts() {
        wp_enqueue_style('wpsection-style', WPSECTION_PLUGIN_URL . 'plugin/assets/admin/css/style.css');
        wp_register_style('bootstrap', WPSECTION_PLUGIN_URL . 'plugin/assets/frontend/css/bootstrap.css');
        wp_enqueue_script('wpsection-admin', WPSECTION_PLUGIN_URL . 'plugin/assets/admin/js/script.js', array('jquery', 'jquery-ui-sortable'));
        wp_localize_script('wpsection-admin', 'wpsection', $this->wpsection_localize_scripts());
        wp_register_script('bootstrap', WPSECTION_PLUGIN_URL . 'plugin/assets/frontend/js/bootstrap.min.js', array('jquery'));
    }

    // Activate the plugin
    function activate() {
        // Code to run on activation
    }

    // Deactivate the plugin
    function deactivate() {
        // Code to run on deactivation
    }

    // Uninstall the plugin
    static function uninstall() {
        // Code to run on uninstallation
    }
}

// Instantiate the WPSection class
$wpsection = new WPSection();


