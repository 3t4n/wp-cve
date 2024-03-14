<?php

namespace ShopWP;

if (!defined('ABSPATH')) {
    exit();
}

use ShopWP\Options;
use ShopWP\Utils;
use ShopWP\Utils\Data as Utils_Data;

class Backend
{
    public $plugin_settings;
    public $DB_Settings_General;
    public $DB_Products;
    public $Data_Bridge;
    public $DB_Collections;

    public function __construct(
        $plugin_settings,
        $DB_Settings_General,
        $DB_Products,
        $DB_Collections,
        $Data_Bridge
    ) {
        $this->plugin_settings = $plugin_settings;
        $this->DB_Settings_General = $DB_Settings_General;
        $this->DB_Products = $DB_Products;
        $this->DB_Collections = $DB_Collections;
        $this->Data_Bridge = $Data_Bridge;
    }

    /*

     Checks for a valid admin page

     */
    public function is_valid_admin_page()
    {
        $screen = \get_current_screen();

        if (empty($screen)) {
            return false;
        }

        if (!is_admin()) {
            return false;
        }

        return $screen;
    }

    /*

     Checks for a valid admin page

     */
    public function get_screen_id()
    {
        $screen = $this->is_valid_admin_page();

        if (empty($screen)) {
            return false;
        }

        return $screen->id;
    }

    /*

     Checks for the correct admin page to load CSS

     */
    public function should_load_css()
    {
        if (!$this->is_valid_admin_page()) {
            return;
        }

        $screen_id = $this->get_screen_id();

        if (
            $this->is_admin_settings_page($screen_id) ||
            $this->is_admin_posts_page($screen_id) ||
            $this->is_admin_plugins_page($screen_id)
        ) {
            return true;
        }

        return false;
    }

    public function is_wizard_page()
    {
        return $this->get_screen_id() === 'dashboard_page_shopwp-wizard' ||
            $this->get_screen_id() === 'admin_page_shopwp-wizard';
    }

    public function is_plugin_specific_pages()
    {
        return $this->is_admin_settings_page($this->get_screen_id());
    }

    /*

     Checks for the correct admin page to load JS

     */
    public function should_load_js()
    {
        if (!$this->is_valid_admin_page()) {
            return;
        }

        $screen_id = $this->get_screen_id();

        if ($this->is_admin_settings_page($screen_id) || $this->is_admin_posts_page($screen_id)) {
            return true;
        }

        return false;
    }

    /*

     Is wp posts page

     */
    public function is_admin_posts_page($current_admin_screen_id)
    {
        if (
            $current_admin_screen_id ===
                SHOPWP_COLLECTIONS_POST_TYPE_SLUG ||
            $current_admin_screen_id === SHOPWP_PRODUCTS_POST_TYPE_SLUG ||
            $current_admin_screen_id === 'edit-wps_products' ||
            $current_admin_screen_id === 'edit-wps_collections'
        ) {
            return true;
        }
    }

    /*

     Is wp plugins page

     */
    public function is_admin_plugins_page($current_admin_screen_id)
    {
        if ($current_admin_screen_id === 'plugins') {
            return true;
        }
    }

    /*

     Is plugin settings page

     */
    public function is_admin_settings_page($current_admin_screen_id = false)
    {
        if (
            Utils::str_contains($current_admin_screen_id, 'shopwp-pro') ||
            Utils::str_contains($current_admin_screen_id, 'shopwp')
        ) {
            return true;
        }
    }

    /*

     Admin styles

     */
    public function admin_styles()
    {
        if ($this->should_load_css()) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_style( 'wp-format-library' );

            wp_enqueue_style(
                'animate-css',
                SHOPWP_PLUGIN_URL . 'admin/css/vendor/animate.min.css',
                [],
                filemtime(
                    SHOPWP_PLUGIN_DIR_PATH .
                        'admin/css/vendor/animate.min.css'
                )
            );

            wp_enqueue_style( 'wp-format-library' );

            wp_enqueue_style(
                'shopwp' . '-styles-backend',
                SHOPWP_PLUGIN_URL . 'dist/admin.min.css',
                ['wp-color-picker', 'wp-components', 'animate-css', 'wp-edit-blocks'],
                filemtime(SHOPWP_PLUGIN_DIR_PATH . 'dist/admin.min.css')
            );
        }
    }

    public function replace_rest_protocol()
    {
        if (is_ssl()) {
            return str_replace("http://", "https://", get_rest_url());
        }

        return get_rest_url();
    }

    // TODO: Check the $hook variable for valid wps page 
    public function admin_scripts($hook)
    {

        if ($this->should_load_js() && !$this->is_wizard_page()) {
            global $wp_version;

            if (!is_admin()) {
                return;
            }

            if (version_compare($wp_version, '5.4', '<')) {
                wp_die(
                    "Sorry, ShopWP requires WordPress version 5.4 or higher. Please look through <a href=\"https://docs.wpshop.io/#/getting-started/requirements?utm_medium=plugin&utm_source=notice&utm_campaign=help\" target=\"_blank\">our requirements</a> page to learn more. Often times you can simply ask your webhost to upgrade for you. <br><br><a href=" .
                        admin_url('plugins.php') .
                        " class=\"button button-primary\">Back to plugins</a>."
                );
            }

            if (
                !function_exists('version_compare') ||
                version_compare(PHP_VERSION, '5.6.0', '<')
            ) {
                wp_die(
                    "Sorry, ShopWP requires PHP version 5.6 or higher. Please look through <a href=\"https://docs.wpshop.io/#/getting-started/requirements?utm_medium=plugin&utm_source=notice&utm_campaign=help\" target=\"_blank\">our requirements</a> page to learn more. Often times you can simply ask your webhost to upgrade for you. <br><br><a href=" .
                        admin_url('plugins.php') .
                        " class=\"button button-primary\">Back to plugins</a>."
                );
            }


            $runtime_path = 'dist/runtime.130a29a2.js';
            $runtime_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $runtime_path);
            $runtime_url = SHOPWP_PLUGIN_URL . $runtime_path;
            
            $vendors_admin_path = 'dist/vendors-admin.130a29a2.js';
            $vendors_admin_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $vendors_admin_path);
            $vendors_admin_url = SHOPWP_PLUGIN_URL . $vendors_admin_path;

            $main_path = 'dist/admin.130a29a2.js';
            $main_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $main_path) . time();

            $main_url = SHOPWP_PLUGIN_URL . $main_path;

            wp_register_script('shopwp-runtime', $runtime_url, [], $runtime_filetime);
            wp_register_script('shopwp-vendors-admin', $vendors_admin_url, [], $vendors_admin_filetime);

            wp_register_script(
                'shopwp-admin',
                $main_url,
                [
                    'wp-plugins', 
                    'wp-edit-post',
                    'wp-blocks',
                    'wp-element',
                    'wp-editor',
                    'wp-components',
                    'wp-i18n',
                    'shopwp-runtime',
                    'shopwp-vendors-admin',
                ],
                $main_filetime,
                true
            );

            // Global plugin JS settings
            $this->Data_Bridge->add_settings_script('shopwp-admin', true);

            wp_set_script_translations(
                'shopwp-admin',
                'shopwp',
                SHOPWP_PLUGIN_DIR . SHOPWP_LANGUAGES_FOLDER
            );

            wp_enqueue_script('shopwp-runtime');
            wp_enqueue_script('shopwp-vendors-admin');
            wp_enqueue_script('shopwp-admin');

        }
    }

    /*

   Registering the admin menu into the WordPress Dashboard menu.
   Adding a settings page to the Settings menu.

   */
    public function add_dashboard_menus()
    {

         $user = wp_get_current_user();

        if (apply_filters('shopwp_show_dashboard', current_user_can('edit_pages'), $user)) {
            if (empty($this->plugin_settings['general'])) {
                $setting_lite_sync = true;
                $setting_is_syncing_posts = false;
            } else {
                $setting_lite_sync =
                    $this->plugin_settings['general']['is_lite_sync'];
                $setting_is_syncing_posts =
                    $this->plugin_settings['general']['is_syncing_posts'];
            }

            $plugin_name = SHOPWP_PLUGIN_NAME_FULL;


            global $submenu;

            $icon_svg =
                'data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZpZXdCb3g9IjAgMCAzNTIuMyAzNTIuMyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOm5ldyAwIDAgMzUyLjMgMzUyLjMiIHhtbDpzcGFjZT0icHJlc2VydmUiPjxwYXRoIGQ9Ik0yMDcuMiAxODguOWMtNS40IDEzLjQtMTcuMiAyMS44LTMwLjcgMjIuMWgtLjZjLTEzLjggMC0yNS44LTguNS0zMS4zLTIyLjFsLS41LTEuM2gtMjMuNGwxLjIgMy44YzQuMSAxMyAxMS43IDIzLjcgMjEuOSAzMS4xIDkuMSA2LjYgMjAuMiAxMC4zIDMxLjUgMTAuNWguNGMxMS40IDAgMjIuOS0zLjcgMzIuMi0xMC41IDEwLjItNy4zIDE3LjgtMTguMSAyMS45LTMxLjFsMS4yLTMuOGgtMjMuNGwtLjQgMS4zeiIvPjxwYXRoIGQ9Ik0xNzYuMiAyLjdjLTk2LjEgMC0xNzQgNzcuOS0xNzQgMTc0czc3LjkgMTc0IDE3NCAxNzQgMTc0LTc3LjkgMTc0LTE3NGMtLjEtOTYuMS03OC0xNzQtMTc0LTE3NHptMS40IDU0LjJjMjAuNiAwIDM4LjEgMTYgNDQuMyAzOC4yaC0xNC4xYy01LjQtMTMuMy0xNi45LTIyLjYtMzAuMi0yMi42cy0yNC43IDkuMi0zMC4yIDIyLjZoLTE0LjFjNi4yLTIyLjEgMjMuNy0zOC4yIDQ0LjMtMzguMnptNzguNSAyMDEuNkg5NS40Yy0xNS4xLTEuMi0xOC43LTEwLjMtMTkuMy0xNS43IDIuNS00MiA1LjctODQgOS41LTEyNmgxODAuMmMzLjkgNDIgNy4xIDg0IDkuNSAxMjYtLjYgNS41LTQuMSAxNC41LTE5LjIgMTUuN3oiLz48L3N2Zz4=';

            // Main menu
            add_menu_page(
                $plugin_name,
                $plugin_name,
                'edit_pages',
                'shopwp',
                [$this, 'plugin_admin_page'],
                $icon_svg,
                null
            );

            add_submenu_page(
                'shopwp',
                __('Connect', 'shopwp'),
                __('Connect', 'shopwp'),
                'edit_pages',
                'wps-connect',
                [$this, 'plugin_admin_page']
            );

            add_submenu_page(
                'shopwp',
                __('Sync', 'shopwp'),
                __('Sync', 'shopwp'),
                'edit_pages',
                'wps-tools',
                [$this, 'plugin_admin_page']
            );

            add_submenu_page(
                'shopwp',
                __('Settings', 'shopwp'),
                __('Settings', 'shopwp'),
                'edit_pages',
                'wps-settings',
                [$this, 'plugin_admin_page']
            );

            add_submenu_page(
                'shopwp',
                __('Products', 'shopwp'),
                __('Products', 'shopwp'),
                'edit_pages',
                'edit.php?post_type=' . SHOPWP_PRODUCTS_POST_TYPE_SLUG,
                null
            );

            if (!empty($this->plugin_settings['general']['selective_sync_collections'])) {
               add_submenu_page(
                  'shopwp',
                  __('Collections', 'shopwp'),
                  __('Collections', 'shopwp'),
                  'edit_pages',
                  'edit.php?post_type=' . SHOPWP_COLLECTIONS_POST_TYPE_SLUG,
                  null
               );
            }

            add_submenu_page(
                'shopwp',
                __('License', 'shopwp'),
                __('License', 'shopwp'),
                'edit_pages',
                'wps-license',
                [$this, 'plugin_admin_page']
            );

            add_submenu_page(
                'shopwp',
                __('Extensions', 'shopwp'),
                __('Extensions', 'shopwp'),
                'edit_pages',
                'wps-extensions',
                [$this, 'plugin_admin_page']
            );

            add_submenu_page(
                'shopwp',
                __('Info', 'shopwp'),
                __('Info', 'shopwp'),
                'edit_pages',
                'wps-help',
                [$this, 'plugin_admin_page']
            );

            add_submenu_page(
                'shopwp',
                __('Visual Builder', 'shopwp'),
                __('Visual Builder', 'shopwp'),
                'edit_pages',
                'wps-visual-builder',
                [$this, 'plugin_admin_page']
            );

            remove_submenu_page('shopwp', 'shopwp');

            add_submenu_page(
                null,
                __('Wizard', 'shopwp'),
                __('Wizard', 'shopwp'),
                'edit_pages',
                'shopwp-wizard',
                function () {
                    echo '<div id="shopwp-wizard"></div>';
                }
            );
        }
    }

    public function add_action_links($links)
    {
        $settings_link = admin_url("/admin.php?page=wps-connect");
        $settings_html_link =
            '<a href="' . esc_url($settings_link) . '">Settings</a>';
        $settings_link = [$settings_html_link];

        $settings_link[] =
            '<a href="' .
            esc_url(
                'https://wpshop.io/purchase?utm_medium=plugin&utm_source=action-link&utm_campaign=upgrade'
            ) .
            '" target="_blank">' .
            __('Upgrade', 'shopwp') .
            '</a>';

        return array_merge($settings_link, $links);
    }

    public function plugin_admin_page()
    {
        include_once SHOPWP_PLUGIN_DIR_PATH .
            'admin/partials/display.php';
    }

    public function wps_admin_body_class($classes)
    {
        // If the settings aren't empty ...
        if (empty($this->plugin_settings['general'])) {
            return $classes;
        }

        $screen_id = $this->get_screen_id();

        if (
            $screen_id !== 'edit-wps_products' &&
            $screen_id !== 'edit-wps_collections'
        ) {
            return $classes;
        }

        if (!$this->plugin_settings['general']['is_syncing_posts']) {
            $classes .= ' wps-is-lite-sync ';
        }

        return $classes;
    }

    public function wps_posts_notice()
    {
        // If the settings aren't empty ...
        if (empty($this->plugin_settings['general'])) {
            return;
        }

        // If the right admin page ...
        $screen_id = $this->get_screen_id();

        if (
            $screen_id !== 'edit-wps_products' &&
            $screen_id !== 'edit-wps_collections'
        ) {
            return;
        }

        if (!$this->plugin_settings['general']['is_syncing_posts']) {
            echo '<div class="wps-posts-notice">
            <svg id="shopwp-almost" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1521.4 1044.84"><defs><style>#shopwp-almost .cls-1,#shopwp-almost .cls-10,#shopwp-almost .cls-11,#shopwp-almost .cls-14,#shopwp-almost .cls-15,#shopwp-almost .cls-2{fill:none;}.cls-2{stroke:#170c2c;}.cls-11,#shopwp-almost .cls-14,#shopwp-almost .cls-15,#shopwp-almost .cls-2{stroke-miterlimit:10;}.cls-15,#shopwp-almost .cls-2{stroke-width:3px;}.cls-3{fill:#030207;}.cls-4{fill:#0068a8;}.cls-5{fill:#c64f4b;}.cls-6{fill:#f5b4c4;}.cls-7{fill:#170c2c;}.cls-8{fill:#fff;}.cls-9{fill:#fcce44;}.cls-10,#shopwp-almost .cls-11,#shopwp-almost .cls-15{stroke:#fff;}.cls-10{stroke-linejoin:round;}.cls-10,#shopwp-almost .cls-11,#shopwp-almost .cls-14{stroke-width:2px;}.cls-12{clip-path:url(#clip-path);}.cls-13{fill:#dd6464;}.cls-14{stroke:#dd6464;}</style><clipPath id="clip-path" transform="translate(-245.08 -169.43)"><path class="cls-1" d="M982.78,811.75c-58.07,37.67,1.49,82.64-21.53,112.85s-44.31,70.28-9.85,102.11,94.32,44.08,137,41.45c87.62-5.39,162.08-67.27,172.54-135.15s-25.23-122.54-111.39-142.36S1021.55,786.59,982.78,811.75Z"/></clipPath></defs><title>1</title><path class="cls-2" d="M948.94,286.92s39.59,25.63,88.31,8.93c43.35-14.87,48-18.47,48-18.47l-15.69,48.93s-57.84,41.23-120.61,9.23" transform="translate(-245.08 -169.43)"/><path class="cls-3" d="M865.09,524.77l115.69,94.77,2,185.92,145.39-51.69s-19.85-16.69-62-4.92c0,0,28-154.61,13.21-184.15S913.25,377.08,844.32,405.38c-72.74,29.88-66.46,248.11-66.46,248.11L642.09,761.92l17.85,81.85,57.84,58s-3.69-41-18.46-69.79c0,0,151.39-65.78,173.54-126.08S919.4,474.31,919.4,474.31" transform="translate(-245.08 -169.43)"/><path class="cls-4" d="M828.42,317.18l44,63.77a26.31,26.31,0,0,0,45.9-4.74l26.85-63.77a26.31,26.31,0,0,0-24.25-36.52H850.07C828.86,275.92,816.37,299.73,828.42,317.18Z" transform="translate(-245.08 -169.43)"/><polygon class="cls-5" points="706.61 202.19 752.16 155.31 813.23 141.96 829.16 72.03 902.61 228.19 885.62 296.03 820.95 304.11 769.23 347.8 706.61 202.19"/><polygon class="cls-3" points="885.62 296.03 820.95 304.11 752.16 155.31 813.23 141.96 885.62 296.03"/><rect class="cls-6" x="577.62" y="943.75" width="173.21" height="242.91" transform="translate(265.19 -350.07) rotate(24.9)"/><rect class="cls-4" x="1495.69" y="692.97" width="105.88" height="265.61" transform="translate(312.21 -791.89) rotate(27.54)"/><path class="cls-7" d="M1125.17,1071.46v138.31h-73.54V1071.46h73.54m3-3h-79.54v144.31h79.54V1068.46Z" transform="translate(-245.08 -169.43)"/><rect class="cls-8" x="1109.67" y="465.77" width="357.62" height="46.85"/><path class="cls-7" d="M1710.86,636.69v43.85H1356.25V636.69h354.61m3-3H1353.25v49.85h360.61V633.69Z" transform="translate(-245.08 -169.43)"/><path class="cls-9" d="M982.78,811.75c-58.07,37.67,1.49,82.64-21.53,112.85s-44.31,70.28-9.85,102.11,94.32,44.08,137,41.45c87.62-5.39,162.08-67.27,172.54-135.15s-25.23-122.54-111.39-142.36S1021.55,786.59,982.78,811.75Z" transform="translate(-245.08 -169.43)"/><circle class="cls-9" cx="127.37" cy="697.51" r="127.37"/><path class="cls-8" d="M1532.64,352.2a32.21,32.21,0,1,1,32.2-32.21A32.25,32.25,0,0,1,1532.64,352.2Z" transform="translate(-245.08 -169.43)"/><path class="cls-7" d="M1532.64,289.29a30.71,30.71,0,1,1-30.71,30.7,30.73,30.73,0,0,1,30.71-30.7m0-3a33.71,33.71,0,1,0,33.7,33.7,33.7,33.7,0,0,0-33.7-33.7Z" transform="translate(-245.08 -169.43)"/><path class="cls-8" d="M372.45,738.06a62.13,62.13,0,0,1-62-60.56H434.49A62.13,62.13,0,0,1,372.45,738.06Z" transform="translate(-245.08 -169.43)"/><path class="cls-7" d="M432.94,679a60.56,60.56,0,0,1-121,0h121m3.07-3H308.89A63.56,63.56,0,0,0,436,676Z" transform="translate(-245.08 -169.43)"/><path class="cls-6" d="M1672.63,493.69A139.57,139.57,0,1,1,1662.35,441,140,140,0,0,1,1672.63,493.69Z" transform="translate(-245.08 -169.43)"/><polygon class="cls-4" points="229.22 1043.34 25.53 1043.34 127.38 824.88 229.22 1043.34"/><path class="cls-9" d="M860.17,281.54l22.46-51.69s-15.54-18.93-5.69-31.7,21.69,0,21.69,0,15.54-16.61,37.39-8.92,22.83,37.1,13.94,53.28c-13.12,23.9-30.41,30.26-52.87,13.8l-13.38,28.61" transform="translate(-245.08 -169.43)"/><path class="cls-3" d="M889.14,192.7s3.34-25.16,35.8-23.16,34.46,14.27,47.69,11.52S987.4,217.69,952,213.69s-33.7-5.84-33.7-5.84S914,223.23,905.4,220s-7.54-20-7.54-20" transform="translate(-245.08 -169.43)"/><path class="cls-2" d="M1089.25,277.38s-3.39,52,1.53,53.24,14.77-35.7,14.77-35.7-8.61,43.7-3.69,44.93,12.92-31.23,12.92-31.23-5.23,35.23,0,35.53,10.31-37.15,2-47.61a127.52,127.52,0,0,0-18.46-18.46" transform="translate(-245.08 -169.43)"/><path class="cls-2" d="M819.64,317.85s12.08,67.53,37,89.69S957.72,444.62,971.56,440s28-16.62,26.77-20-30.61,7.31-30.61,7.31,35.49-17.17,33.38-22.93c-2.72-7.41-37.38,9.77-37.38,9.77s29.54-17.32,27.92-22C989.56,386.15,960,398,960,398l-61.23-22.62-10-33.6" transform="translate(-245.08 -169.43)"/><rect class="cls-8" x="805.05" y="900.53" width="76.54" height="141.31"/><path class="cls-7" d="M1125.17,1071.46v138.31h-73.54V1071.46h73.54m3-3h-79.54v144.31h79.54V1068.46Z" transform="translate(-245.08 -169.43)"/><line class="cls-2" x1="696.18" y1="67.64" x2="695.39" y2="81.42"/><circle class="cls-7" cx="688.38" cy="61.81" r="3.75"/><circle class="cls-7" cx="703.86" cy="66.64" r="3.75"/><path class="cls-8" d="M1662.35,441c-32,12.18-60,13.29-65.31,1.67-5-10.8,11-29.24,37.45-45A139.86,139.86,0,0,1,1662.35,441Z" transform="translate(-245.08 -169.43)"/><ellipse class="cls-8" cx="1461.84" cy="440.84" rx="14.02" ry="16.11" transform="translate(-165.74 919.49) rotate(-41.9)"/><ellipse class="cls-8" cx="331.66" cy="940.24" rx="19.15" ry="16.11" transform="translate(-788.17 292.43) rotate(-41.9)"/><path class="cls-10" d="M1569.61,358.63c-18,19.52-73.59,83.64-54.3,110.21,16,22.06,70.66,30.09,70.66,30.09s-67.09,29.7-44.43,70c11,19.58,53.72,24.53,88.65,25.15" transform="translate(-245.08 -169.43)"/><path class="cls-10" d="M250.28,904.08c38-1,76.67-4.89,79.21-16.32,3.57-16-41.24-45-41.24-45s88.53,20.69,101.3-5.38c8.78-17.94-67.51-55.08-97.78-69" transform="translate(-245.08 -169.43)"/><path class="cls-11" d="M1432.92,592c30.48-12.77,68-32.76,64.27-52.64-4.76-25.34-66.58-41.35-104.55-48.86" transform="translate(-245.08 -169.43)"/><path class="cls-11" d="M489.29,917.73c-25.4-12.29-93.82-42.57-109.2-20.08-12.21,17.87,8.67,59.54,28.43,91.47" transform="translate(-245.08 -169.43)"/><path class="cls-6" d="M1742.78,1176.61a209.25,209.25,0,0,1-3.13,36.16H1624.5a97.85,97.85,0,1,0-181.89,0H1328.68a209.25,209.25,0,0,1-3.13-36.16c0-115.21,93.4-208.61,208.62-208.61S1742.78,1061.4,1742.78,1176.61Z" transform="translate(-245.08 -169.43)"/><path class="cls-10" d="M1672.63,487.93c-15.8,7.54-35.56,18.9-37.38,29.3-1.43,8.13,13.82,14.82,30.59,19.64" transform="translate(-245.08 -169.43)"/><path class="cls-10" d="M453.69,769c-15.47,7.52-33.89,18.44-35.64,28.46C415,815,493,825.77,493,825.77" transform="translate(-245.08 -169.43)"/><g class="cls-12"><ellipse class="cls-13" cx="1021.1" cy="981.26" rx="16.12" ry="27.75" transform="translate(-514.16 1395.73) rotate(-68.21)"/><path class="cls-14" d="M1252.18,1009.39s-73.58-130.91-110.55-117.52-22,147.41-33.45,148.82-28.18-31.11-39.6-27.7c-18.32,5.48-33.31,64.67-33.31,64.67" transform="translate(-245.08 -169.43)"/><path class="cls-14" d="M1088.36,723s26.32,133.31,5.31,141.27c-14.71,5.56-48.08-30.41-48.08-30.41s31.09,76.51,8,91.84c-19.76,13.13-97-102.91-97-102.91" transform="translate(-245.08 -169.43)"/><path class="cls-14" d="M945.86,913.74s29.68,65.87-12.55,119.9" transform="translate(-245.08 -169.43)"/><path class="cls-14" d="M1152.43,774.77s-10.1,42.55,10.25,68.1c46.87,58.85,137.44,28.72,137.44,28.72" transform="translate(-245.08 -169.43)"/></g><line class="cls-2" x1="7.2" y1="1043.34" x2="247.55" y2="1043.34"/><line class="cls-2" x1="325.66" y1="1043.34" x2="973.09" y2="1043.34"/><line class="cls-2" x1="1065.04" y1="1043.34" x2="1521.4" y2="1043.34"/><line class="cls-15" x1="670.96" y1="336.29" x2="658.66" y2="422.85"/><path class="cls-15" d="M852.23,403s17.27,24.28,95.86,36.58" transform="translate(-245.08 -169.43)"/></svg>
            
            <h1>' . __('You\'re almost ready!', 'shopwp') . ' </h1>' . sprintf(
            '<p>%s <br><br>%s <a href="/wp-admin/admin.php?page=wps-settings&activesubnav=wps-admin-section-syncing">%s</a> %s<br>%s <a href="/wp-admin/admin.php?page=wps-tools">%s</a> %s</p>',
            __( 'Before you can use the product pages, you need to do a couple more things:', 'shopwp' ),
            __( '1. Turn on', 'shopwp' ),
            __( 'Create Product Detail Pages', 'shopwp' ),
            __( 'from within the Syncing settings', 'shopwp' ),
            __( '2. Use the', 'shopwp' ),
            __( 'Sync Detail Pages', 'shopwp' ),
            __( 'button under the plugin Tools', 'shopwp' ),
        ) . '</div>';

        }
    }

    public function user_allowed_tracking()
    {
        return $this->plugin_settings['general']['allow_tracking'];
    }

    public function shopwp_usage_tracking_analytics_head()
    {
        if (
            is_admin() &&
            $this->is_plugin_specific_pages() &&
            $this->user_allowed_tracking()
        ) {
            echo "<script async src='https://www.googletagmanager.com/gtag/js?id=UA-101619037-3'></script><script>window.dataLayer = window.dataLayer || [];function gtag(){dataLayer.push(arguments);}gtag('js', new Date());gtag('config', 'UA-101619037-3');</script><script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start': new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src='https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);})(window,document,'script','dataLayer','GTM-NKB2G3Z');</script>";
        }
    }

    public function shopwp_usage_tracking_analytics_footer()
    {
        if (
            is_admin() &&
            $this->is_plugin_specific_pages() &&
            $this->user_allowed_tracking()
        ) {
            echo "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=GTM-NKB2G3Z' height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>";
        }
    }

    /*
      
      Blocks JS

      */
    public function shopwp_blocks_assets()
    {
        if (is_admin()) {

            $runtime_path = 'dist/runtime.130a29a2.js';
            $runtime_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $runtime_path);
            $runtime_url = SHOPWP_PLUGIN_URL . 'dist/runtime.130a29a2.js';

            $vendors_admin_path = 'dist/vendors-admin.130a29a2.js';
            $vendors_admin_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $vendors_admin_path);
            $vendors_admin_url = SHOPWP_PLUGIN_URL . $vendors_admin_path;
            
            $main_path = 'dist/blocks.130a29a2.js';
            $main_filetime = filemtime(SHOPWP_PLUGIN_DIR_PATH . $main_path);
            $main_url = SHOPWP_PLUGIN_URL . $main_path;

            wp_enqueue_script('shopwp-runtime', $runtime_url, [], $runtime_filetime, true);
            wp_enqueue_script('shopwp-vendors-admin', $vendors_admin_url, [], $vendors_admin_filetime, true);

            wp_enqueue_script(
                'shopwp-blocks', 
                $main_url, 
                [
                    'wp-blocks',
                    'wp-element',
                    'wp-editor',
                    'wp-components',
                    'wp-i18n',
                    'shopwp-runtime',
                    'shopwp-vendors-admin',
                ], 
                $main_filetime,
                true
            );

            wp_set_script_translations(
                'shopwp-blocks',
                'shopwp',
                SHOPWP_PLUGIN_DIR . SHOPWP_LANGUAGES_FOLDER
            );

            $this->Data_Bridge->add_settings_script('shopwp-blocks', true);

            wp_enqueue_style(
                'shopwp' . '-styles-frontend-all',
                SHOPWP_PLUGIN_URL . 'dist/public.min.css',
                [],
                filemtime(SHOPWP_PLUGIN_DIR_PATH . 'dist/public.min.css'),
                'all'
            );
        }
    }

    public function is_wizard_completed()
    {
        if (!isset($this->plugin_settings['general']['wizard_completed'])) {
            return false;
        }

        return $this->plugin_settings['general']['wizard_completed'];
    }

    public function shopwp_wizard_assets()
    {
        if ($this->is_wizard_page()) {

            $runtime_url = SHOPWP_PLUGIN_URL . 'dist/runtime.130a29a2.js';
            $vendors_admin_url =
                SHOPWP_PLUGIN_URL . 'dist/vendors-admin.130a29a2.js';
            $main_url = SHOPWP_PLUGIN_URL . 'dist/wizard.130a29a2.js';

            wp_enqueue_script('shopwp-runtime', $runtime_url, []);
            wp_enqueue_script(
                'shopwp-vendors-admin',
                $vendors_admin_url,
                []
            );
            wp_enqueue_script('shopwp-wizard', $main_url, [
                'wp-blocks',
                'wp-element',
                'wp-editor',
                'wp-components',
                'wp-i18n',
                'shopwp-runtime',
                'shopwp-vendors-admin',
            ]);

            wp_set_script_translations(
                'shopwp-wizard',
                'shopwp',
                SHOPWP_PLUGIN_DIR . SHOPWP_LANGUAGES_FOLDER
            );

            $this->Data_Bridge->add_settings_script('shopwp-wizard', true);
        }
    }

    public function shopwp_block_categories($categories, $post)
    {
        return array_merge($categories, [
            [
                'slug' => 'shopwp-products',
                'title' => __('ShopWP Products', 'shopwp'),
            ],
        ]);
    }

    public function shopwp_wizard_redirect()
    {

         $is_plugin_specific_pages = $this->is_plugin_specific_pages();
         $is_wizard_page = $this->is_wizard_page();
         $is_wizard_completed = $this->is_wizard_completed();
        
         if (!$is_plugin_specific_pages || empty($this->plugin_settings)) {
            return;
         }

         $has_finished_wizard_param = isset($_GET['shopwp-finished-wizard']);

        if ($has_finished_wizard_param) {
           
            $updated_col = $this->DB_Settings_General->update_col('wizard_completed', 1);

            wp_safe_redirect(
                esc_url(admin_url('/admin.php?page=wps-settings'))
            );
            exit();
        }

        if (
            $is_plugin_specific_pages &&
            !$is_wizard_page &&
            !$is_wizard_completed
        ) {
            wp_safe_redirect(
                esc_url(admin_url('/admin.php?page=shopwp-wizard'))
            );
            exit();
        }

        if ($is_wizard_page && $is_wizard_completed) {
            wp_safe_redirect(
                esc_url(admin_url('/admin.php?page=wps-settings'))
            );
            exit();
        }
    }

    

    public function create_edit_link_href($domain, $id, $type) {
      return 'https://' . $domain . '/admin/' . $type . '/' . $id;
    }

    public function create_edit_link_href_general($domain, $type) {
      return 'https://' . $domain . '/admin/' . $type;
    }

    public function create_edit_link_html($href) {
      return '<a href="' . $href . '" aria-label="Edit in Shopify" target="_blank">Edit in Shopify <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link-alt" style="width:10px;height:10px;" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM488,0h-128c-21.37,0-32.05,25.91-17,41l35.73,35.73L135,320.37a24,24,0,0,0,0,34L157.67,377a24,24,0,0,0,34,0L435.28,133.32,471,169c15,15,41,4.5,41-17V24A24,24,0,0,0,488,0Z"></path></svg></a>';
    }

    public function make_products_link($post_id) {

      $domain = $this->plugin_settings['connection']['domain'];

      $product_id_result = get_post_meta($post_id, 'product_id');

      if (empty($product_id_result)) {
         return $this->create_edit_link_href_general($domain, 'products');
      }

      $product_id = $product_id_result[0];
      return $this->create_edit_link_href($domain, $product_id, 'products');
    }

    public function make_collections_link($post_id) {

      $domain = $this->plugin_settings['connection']['domain'];
      $collection_id = get_post_meta($post_id, 'collection_id');

      if (empty($collection_id)) {
         return $this->create_edit_link_href_general($domain, 'collections');
      }

      return $this->create_edit_link_href($domain, $collection_id[0], 'collections');

    }

   public function custom_action_links($actions, $post) {

      $pt = $post->post_type;

      if ($pt !== "wps_products" && $pt !== "wps_collections") {
         return $actions;
      }

      if ($pt === "wps_products") {
         $link_href = $this->make_products_link($post->ID);
      }

      if ($pt === "wps_collections") {
         $link_href = $this->make_collections_link($post->ID);
      }

      $actions['edit_in_shopify'] = $this->create_edit_link_html($link_href);


      if (add_filter('shopwp_remove_quick_edit', true)) {
         unset($actions['inline hide-if-no-js']);
      }

      return $actions;
   }

   public function add_edit_in_shopify_button() {
       $screen = get_current_screen();

       if ( $screen->post_type !== 'wps_products' && $screen->post_type !== 'wps_collections' ) {
         return;
       }

       global $post;

       if ($screen->base !== 'post') {
          return;
       }

      if ($post->post_type === "wps_products") {
        $title = 'Product';
        $link_href = $this->make_products_link($post->ID);
      }

      if ($post->post_type === "wps_collections") {
        $title = 'Collection';
        $link_href = $this->make_collections_link($post->ID);
      }

       ?>
      <div class="wrap">
         <h1 class="wp-heading-inline show" style="display:inline-block;">Edit <?= $title; ?></h1>
         <a href="<?= esc_url_raw($link_href); ?>" target="_blank" class="page-title-action show">Edit in Shopify <svg aria-hidden="true" focusable="false" data-prefix="fas" data-icon="external-link-alt" style="width:10px;height:10px;" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path fill="currentColor" d="M432,320H400a16,16,0,0,0-16,16V448H64V128H208a16,16,0,0,0,16-16V80a16,16,0,0,0-16-16H48A48,48,0,0,0,0,112V464a48,48,0,0,0,48,48H400a48,48,0,0,0,48-48V336A16,16,0,0,0,432,320ZM488,0h-128c-21.37,0-32.05,25.91-17,41l35.73,35.73L135,320.37a24,24,0,0,0,0,34L157.67,377a24,24,0,0,0,34,0L435.28,133.32,471,169c15,15,41,4.5,41-17V24A24,24,0,0,0,488,0Z"></path></svg></a>
      </div>

      <style scoped>
      .wp-heading-inline:not(.show),
      .page-title-action:not(.show) { display:none !important;}
      </style>
      <?php
 }

    public function add_product_info_app_root($post) {

       if ($post->post_type === 'wps_products') {
            echo '<div id="shopwp-product-info-app" data-product-id="' . get_post_meta($post->ID, 'product_id', true) . '"></div>';
       } else if ($post->post_type === 'wps_collections') {
            echo '<div id="shopwp-collection-info-app" data-collection-title="' . $post->post_title . '"></div>';
       }

       if ($post->post_type === 'wps_products' || $post->post_type === 'wps_collections') {

           echo '<div id="shopwp-skeleton-wrapper">
                <style text="text/css">

                    @keyframes shimmer {
                        0% {
                            opacity: 0.5;
                        }
                
                        100% {
                            opacity: 1;
                        }
                    }

                    #shopwp-skeleton-wrapper {
                        margin-top: 40px;
                        
                    }
                
                    .shopwp-skeleton-component {
                        margin-bottom: 8px;
                        border-radius: 15px;
                        padding: 0px;
                        background: #e6e6e6;
                        animation: shimmer 0.4s ease-out 0s alternate infinite none running;
                    }
                
                    .shopwp-skeleton-row {
                        display: flex;
                        justify-content: space-between;
                        margin-bottom: 8px;
                    }
                
                    .shopwp-skeleton-product-buy-button-circle {
                        width: 25px;
                        height: 25px;
                        border-radius: 50%;
                    }

                    .shopwp-skeleton-product-buy-button-line {
                        flex: 1;
                        height: 25px;
                        margin-left: 10px;
                    }
                
                </style>
            
                <div class="shopwp-skeleton">
                    <div class="shopwp-skeleton-row">
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-circle"></div>
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-line"></div>
                    </div>
                    <div class="shopwp-skeleton-row">
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-circle"></div>
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-line"></div>
                    </div>
                    <div class="shopwp-skeleton-row">
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-circle"></div>
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-line"></div>
                    </div>
                    <div class="shopwp-skeleton-row">
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-circle"></div>
                        <div class="shopwp-skeleton-component shopwp-skeleton-product-buy-button-line"></div>
                    </div>
                </div>

            </div>';
       }
    }
 

    public function init()
    {

        if (is_admin()) {

            add_action('admin_menu', [$this, 'add_dashboard_menus']);
            add_action('admin_enqueue_scripts', [$this, 'admin_styles']);
            add_action('admin_enqueue_scripts', [$this, 'admin_scripts']);
            add_filter('plugin_action_links_' . SHOPWP_BASENAME, [
                $this,
                'add_action_links',
            ]);

            add_filter('admin_body_class', [$this, 'wps_admin_body_class']);
            add_action('in_admin_header', [$this, 'wps_posts_notice']);

            add_action('admin_head', [
                $this,
                'shopwp_usage_tracking_analytics_head',
            ]);
            add_action('admin_footer', [
                $this,
                'shopwp_usage_tracking_analytics_footer',
            ]);

            add_action('admin_enqueue_scripts', [$this, 'shopwp_wizard_assets']);



            add_filter('post_row_actions', [$this, 'custom_action_links'], 10, 2);
            add_action('admin_notices',[$this, 'add_edit_in_shopify_button']);

            add_action('edit_form_after_title',[$this, 'add_product_info_app_root'], 999);

      }
   }
}
