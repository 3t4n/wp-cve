<?php

/**
 * Plugin Name: Layouts for Divi
 * Plugin URI: https://www.techeshta.com/product/layouts-for-divi/
 * Description: Beautifully designed, Free templates, Hand-crafted for popular Divi page builder.
 * Version: 1.0.7
 * Author: Techeshta
 * Author URI: https://www.techeshta.com
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 *
 * Text Domain: layouts-for-divi
 * Domain Path: /languages/
 */
/*
 * Exit if accessed directly
 */
if (!defined('ABSPATH')) {
    exit;
}

/*
 * Define variables
 */
define('LFD_FILE', __FILE__);
define('LFD_DIR', plugin_dir_path(LFD_FILE));
define('LFD_URL', plugins_url('/', LFD_FILE));
define('LFD_TEXTDOMAIN', 'layouts-for-divi');

/**
 * Main Plugin Layouts_For_Divi class.
 */
class Layouts_For_Divi {

    /**
     * Layouts_For_Divi constructor.
     *
     * The main plugin actions registered for WordPress
     */
    public function __construct() {
        add_action('init', array($this, 'lfd_check_dependencies'));
        $this->hooks();
        $this->lfd_include_files();
    }

    /**
     * Initialize
     */
    public function hooks() {
        add_action('plugins_loaded', array($this, 'lfd_load_language_files'));
        add_action('admin_enqueue_scripts', array($this, 'lfd_admin_scripts',));
    }

    /**
     * Load files
     */
    public function lfd_include_files() {
        include_once( LFD_DIR . 'includes/class-layout-importer.php' );
        include_once( LFD_DIR . 'includes/api/class-layouts-remote.php' );
    }

    /**
     * @return Loads plugin textdomain
     */
    public function lfd_load_language_files() {
        load_plugin_textdomain(LFD_TEXTDOMAIN, false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    /**
     * Check plugin dependencies
     * Check if Divi plugin is installed
     */
    public function lfd_check_dependencies() {

        if (!defined('ET_BUILDER_VERSION')) {
            add_action('admin_notices', array($this, 'lfd_layouts_widget_fail_load'));
            return;
        } else {
            add_action('admin_menu', array($this, 'lfd_menu'));
        }
        $divi_version_required = '2.21.2';
        if (!version_compare(ET_BUILDER_VERSION, $divi_version_required, '>=')) {
            add_action('admin_notices', array($this, 'lfd_layouts_divi_update_notice'));
            return;
        }
    }

    /**
     * This notice will appear if Divi Builder is not installed or activated or both
     */
    public function lfd_layouts_widget_fail_load() {

        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        $plugin = 'divi-builder/divi-builder.php';
        $file_path = 'divi-builder/divi-builder.php';
        $installed_plugins = get_plugins();

        if (isset($installed_plugins[$file_path])) { // check if plugin is installed
            if (!current_user_can('activate_plugins')) {
                return;
            }
            $activation_url = wp_nonce_url('plugins.php?action=activate&amp;plugin=' . $plugin . '&amp;plugin_status=all&amp;paged=1&amp;s', 'activate-plugin_' . $plugin);

            $message = '<p><strong>' . __('Layouts for Divi', LFD_TEXTDOMAIN) . '</strong>' . __(' plugin not working because you need to activate the Divi builder plugin.', LFD_TEXTDOMAIN) . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Divi Now', LFD_TEXTDOMAIN)) . '</p>';
        } else {
            if (!current_user_can('install_plugins')) {
                return;
            }

            $buy_now_url = esc_url('https://www.elegantthemes.com/');

            $message = '<p><strong>' . __('Layouts for Divi', LFD_TEXTDOMAIN) . '</strong>' . __(' plugin not working because you need to install the Divi Builder plugin', LFD_TEXTDOMAIN) . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary" target="_blank">%s</a>', $buy_now_url, __('Get Divi', LFD_TEXTDOMAIN)) . '</p>';
        }

        echo '<div class="error"><p>' . $message . '</p></div>';
    }

    /**
     * Display admin notice for Divi Builder update if Divi Builder version is old
     */
    public function lfd_layouts_divi_update_notice() {
        if (!current_user_can('update_plugins')) {
            return;
        }

        $file_path = 'divi-builder/divi-builder.php';

        $upgrade_link = esc_url('https://www.elegantthemes.com/');
        $message = '<p><strong>' . __('Layouts for Divi', LFD_TEXTDOMAIN) . '</strong>' . __(' plugin not working because you are using an old version of Divi Builder.', LFD_TEXTDOMAIN) . '</p>';
        $message .= '<p>' . sprintf('<a href="%s" class="button-primary" target="_blank">%s</a>', $upgrade_link, __('Get Latest Divi', LFD_TEXTDOMAIN)) . '</p>';
        echo '<div class="error">' . $message . '</div>';
    }

    /**
     *
     * @return Enqueue admin panel required css/js
     */
    public function lfd_admin_scripts() {
        $screen = get_current_screen();

        wp_register_style('lfd-admin-stylesheets', LFD_URL . 'assets/css/admin.css');
        wp_register_style('lfd-toastify-stylesheets', LFD_URL . 'assets/css/toastify.css');
        wp_register_script('lfd-admin-script', LFD_URL . 'assets/js/admin.js', array('jquery'), false, true);
        wp_register_script('lfd-toastify-script', LFD_URL . 'assets/js/toastify.js', array('jquery'), false, true);
        wp_localize_script('lfd-admin-script', 'js_object', array(
            'lfd_loading' => __('Importing...', LFD_TEXTDOMAIN),
            'lfd_tem_msg' => __('Template is successfully imported!.', LFD_TEXTDOMAIN),
            'lfd_msg' => __('Your page is successfully imported!', LFD_TEXTDOMAIN),
            'lfd_crt_page' => __('Please Enter Page Name.', LFD_TEXTDOMAIN),
            'lfd_sync' => __('Syncing...', LFD_TEXTDOMAIN),
            'lfd_sync_suc' => __('Templates library refreshed', LFD_TEXTDOMAIN),
            'lfd_sync_fai' => __('Error in library Syncing', LFD_TEXTDOMAIN),
            'lfd_error' => __('Something went wrong. Please try again.', LFD_TEXTDOMAIN),
            'LFD_URL' => LFD_URL,
        ));

        if ((isset($_GET['page']) && ( $_GET['page'] == 'lfd_layouts' || $_GET['page'] == 'lfd_started'))) {
            wp_enqueue_style('lfd-admin-stylesheets');
            wp_enqueue_style('lfd-toastify-stylesheets');
            wp_enqueue_script('lfd-toastify-script');
            wp_enqueue_script('lfd-admin-script');
            wp_enqueue_script('lfd-admin-live-script');
            add_thickbox();
        }
    }

    /**
     *
     * add menu at admin panel
     */
    public function lfd_menu() {
        add_menu_page(__('Layouts', LFD_TEXTDOMAIN), __('Layouts', LFD_TEXTDOMAIN), 'administrator', 'lfd_layouts', 'lfd_layouts_function', LFD_URL . 'assets/images/layouts-for-divi.png');

        /**
         *
         * @global type $wp_version
         * @return html Display setting options
         */
        function lfd_layouts_function() {
            include_once( 'includes/layouts.php' );
        }

    }

}

/*
 * Starts our plugin class, easy!
 */
new Layouts_For_Divi();
