<?php

namespace FormInteg\IZCRMEF\Admin;

use FormInteg\IZCRMEF\Core\Util\DateTimeHelper;
use FormInteg\IZCRMEF\Core\Util\Capabilities;
use FormInteg\IZCRMEF\Core\Util\Hooks;

/**
 * The admin menu and page handler class
 */

class Admin_Bar
{
    public function register()
    {
        Hooks::add('in_admin_header', [$this, 'RemoveAdminNotices']);
        Hooks::add('admin_menu', [$this, 'AdminMenu'], 11);
        Hooks::add('admin_enqueue_scripts', [$this, 'AdminAssets'], 11);
    }

    /**
     * Register the admin menu
     *
     * @return void
     */
    public function AdminMenu()
    {
        $capability = Hooks::apply('manage_izcrmef', 'manage_options');
        if (Capabilities::Check($capability)) {
            $rootExists = !empty($GLOBALS['admin_page_hooks']['elementor-to-zoho-crm']);
            if ($rootExists) {
                remove_menu_page('elementor-to-zoho-crm');
            }
            add_menu_page(__('Integrations for Elementor Forms', 'elementor-to-zoho-crm'), 'Elementor Zoho CRM', $capability, 'elementor-to-zoho-crm', $rootExists ? '' : [$this, 'rootPage'], 'data:image/svg+xml;base64,' . base64_encode('<svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 256 256"><defs><style>.cls-1{fill:#fff;}</style></defs><rect width="256" height="256" rx="34.81"/><path class="cls-1" d="M71.77,111V80.72H97.94l0,0,0,0,.86-1.17.07-.09.25-.32.64-.82.1-.13,0,0,1-1.17,0,0,.19-.24.67-.78.17-.21.17-.19.84-.94.06-.07.14-.14.94-1v0l.1-.1,1-1,.06-.07L106.39,71l0,0,1-.91.15-.15h0l.16-.15,1-.87h0l0,0,.09-.09.89-.78.44-.36.08-.08.4-.33.4-.33.37-.3.81-.65.37-.29.06,0,.41-.31.41-.32.22-.17.19-.14.41-.3.42-.3.42-.3.42-.3.42-.3.84-.57h0l.44-.28.43-.29.43-.28.44-.28.06,0,.69-.43.12-.07.45-.27.89-.53.44-.26.9-.51.46-.25h-83v52ZM39.59,197h82.58l-.08,0-.14-.07-1.11-.64h0l-.15-.08-.3-.18-.88-.53-.2-.12-.24-.15-.86-.54-.44-.28-.43-.29-.43-.28-.32-.21-.1-.08-.42-.29-.43-.29-.37-.26-.05,0-.41-.29-.42-.3-.12-.09-.64-.48-.06,0-.41-.31-.41-.31-.41-.32-.4-.32-.4-.32-.32-.26-.07-.06-.4-.32-.39-.33h0l-.38-.33-.38-.33-.39-.34-.49-.42-.74-.67-.38-.36-.71-.66-.32-.31-.37-.36-.37-.36-.11-.11-.62-.62-.35-.36-.72-.75-.34-.37-.07-.07-1-1.07-.34-.39-.34-.38-.34-.4-.34-.39v0l-.32-.37-.33-.4-.32-.4-.32-.4-.32-.4-.33-.41-.62-.82-.31-.42,0,0-.58-.81h-26V143.79H39.59Z"/><path class="cls-1" d="M118.32,111c6.09-18.76,22.39-32.08,42.93-32.08,15.76,0,27.58,6.9,38.61,17.14l15.56-17.92c-13.2-12.61-28.56-21.47-54-21.47-35.54,0-62.09,23.25-69.22,54.33Zm42.14,88.29c25.81,0,41.57-9.45,55.95-24.43l-15.56-15.76c-11.82,11-22.66,17.74-39.6,17.74-21,0-37.37-13.83-43.2-33.07H91.89C98.53,175.81,125.65,199.31,160.46,199.31Z"/><path class="cls-1" d="M125.6,124.46a4,4,0,0,0-1.29-.9,3.75,3.75,0,0,0-1.61-.34,3.92,3.92,0,0,0-1.63.33,3.65,3.65,0,0,0-1.26.89,4.05,4.05,0,0,0-.81,1.32,4.31,4.31,0,0,0-.3,1.61v0A4.31,4.31,0,0,0,119,129a3.93,3.93,0,0,0,.83,1.34,4,4,0,0,0,1.28.9,3.91,3.91,0,0,0,1.62.33,4.07,4.07,0,0,0,1.62-.32,3.73,3.73,0,0,0,1.27-.89,4.4,4.4,0,0,0,.81-1.33,4.26,4.26,0,0,0,.3-1.6v0a4.4,4.4,0,0,0-.3-1.62A3.89,3.89,0,0,0,125.6,124.46Z"/><path class="cls-1" d="M89.26,124.46a4,4,0,0,0-1.28-.9,3.83,3.83,0,0,0-1.62-.34,3.92,3.92,0,0,0-1.63.33,3.61,3.61,0,0,0-1.25.89,4.26,4.26,0,0,0-.82,1.32,4.31,4.31,0,0,0-.3,1.61v0a4.31,4.31,0,0,0,.3,1.61,3.93,3.93,0,0,0,3.74,2.57,4.06,4.06,0,0,0,1.61-.32,3.73,3.73,0,0,0,1.27-.89,4.3,4.3,0,0,0,1.11-2.93v0a4.61,4.61,0,0,0-.29-1.62A4.07,4.07,0,0,0,89.26,124.46Z"/><path class="cls-1" d="M39.59,116.57v21.67H153.37V116.57ZM74.26,123l-7.32,8.52h7.32v2.57H63.17v-2.23l7.32-8.52H63.4v-2.57H74.26Zm19.2,4.43a6.64,6.64,0,0,1-.54,2.67,6.74,6.74,0,0,1-3.72,3.67,7.74,7.74,0,0,1-5.68,0,7.13,7.13,0,0,1-2.23-1.46,6.62,6.62,0,0,1-1.47-2.17,7,7,0,0,1-.52-2.67v0a6.85,6.85,0,0,1,4.25-6.35,7.38,7.38,0,0,1,2.85-.54,7.28,7.28,0,0,1,2.83.54,7,7,0,0,1,2.24,1.47,6.64,6.64,0,0,1,1.46,2.17,6.8,6.8,0,0,1,.53,2.67Zm16.72,6.66h-2.93v-5.35h-5.4v5.35H98.92V120.75h2.93V126h5.4v-5.27h2.93Zm19.61-6.66a6.8,6.8,0,0,1-.53,2.67,7,7,0,0,1-1.48,2.19,6.68,6.68,0,0,1-2.24,1.48,7.74,7.74,0,0,1-5.68,0,7,7,0,0,1-2.23-1.46,6.62,6.62,0,0,1-1.47-2.17,6.8,6.8,0,0,1-.52-2.67v0a6.63,6.63,0,0,1,.53-2.67,6.75,6.75,0,0,1,1.47-2.19,6.85,6.85,0,0,1,2.25-1.49,7.74,7.74,0,0,1,5.68,0,6.87,6.87,0,0,1,2.23,1.47,6.51,6.51,0,0,1,1.47,2.17,6.8,6.8,0,0,1,.52,2.67Z"/></svg>'), 30);
        }
    }

    /**
     * Load the asset libraries
     *
     * @return void
     */
    public function AdminAssets($current_screen)
    {
        if (strpos($current_screen, 'elementor-to-zoho-crm') === false) {
            return;
        }

        $parsed_url = parse_url(get_admin_url());
        $site_url = $parsed_url['scheme'] . '://' . $parsed_url['host'];
        $site_url .= empty($parsed_url['port']) ? null : ':' . $parsed_url['port'];
        $base_path_admin = str_replace($site_url, '', get_admin_url());

        foreach (['izcrmef-vendors', 'izcrmef-runtime', 'izcrmef-admin-script'] as $script) {
            if (wp_script_is($script, 'registered')) {
                wp_deregister_script($script);
            } else {
                wp_dequeue_script($script);
            }
        }
        wp_dequeue_style('izcrmef-styles');

        wp_enqueue_script(
            'izcrmef-vendors',
            IZCRMEF_ASSET_JS_URI . '/vendors-main.js',
            null,
            IZCRMEF_VERSION,
            true
        );

        wp_enqueue_script(
            'izcrmef-runtime',
            IZCRMEF_ASSET_JS_URI . '/runtime.js',
            null,
            IZCRMEF_VERSION,
            true
        );

        if (wp_script_is('wp-i18n')) {
            $deps = ['izcrmef-vendors', 'izcrmef-runtime', 'wp-i18n'];
        } else {
            $deps = ['izcrmef-vendors', 'izcrmef-runtime', ];
        }

        wp_enqueue_script(
            'izcrmef-admin-script',
            IZCRMEF_ASSET_JS_URI . '/index.js',
            $deps,
            IZCRMEF_VERSION,
            true
        );

        wp_enqueue_style(
            'izcrmef-styles',
            IZCRMEF_ASSET_URI . '/css/izcrmef.css',
            null,
            IZCRMEF_VERSION,
            'screen'
        );

        global $wp_rewrite;
        $api = [
            'base'      => get_rest_url() . 'elementor-to-zoho-crm/v1',
            'separator' => $wp_rewrite->permalink_structure ? '?' : '&'
        ];
        $users = get_users(['fields' => ['ID', 'user_nicename', 'user_email', 'display_name']]);
        $userMail = [];
        // $userNames = [];
        foreach ($users as $key => $value) {
            $userMail[$key]['label'] = !empty($value->display_name) ? $value->display_name : '';
            $userMail[$key]['value'] = !empty($value->user_email) ? $value->user_email : '';
            $userMail[$key]['id'] = $value->ID;
            // $userNames[$value->ID] = ['name' => $value->display_name, 'url' => get_edit_user_link($value->ID)];
        }

        $izcrmef = apply_filters(
            'izcrmef_localized_script',
            [
                'nonce'      => wp_create_nonce('izcrmef_nonce'),
                'assetsURL'  => IZCRMEF_ASSET_URI,
                'baseURL'    => $base_path_admin . 'admin.php?page=elementor-zohoCRM#',
                'siteURL'    => site_url(),
                'ajaxURL'    => admin_url('admin-ajax.php'),
                'api'        => $api,
                'dateFormat' => get_option('date_format'),
                'timeFormat' => get_option('time_format'),
                'timeZone'   => DateTimeHelper::wp_timezone_string(),
                'userMail'   => $userMail
            ]
        );
        if (get_locale() !== 'en_US' && file_exists(IZCRMEF_PLUGIN_BASEDIR . '/languages/generatedString.php')) {
            include_once IZCRMEF_PLUGIN_BASEDIR . '/languages/generatedString.php';
            $izcrmef['translations'] = $elementor_to_zoho_crm_i18n_strings;
        }
        wp_localize_script('izcrmef-admin-script', 'izcrmef', $izcrmef);
    }

    /**
     * elementor-to-zoho-crm  apps-root id provider
     *
     * @return void
     */
    public function rootPage()
    {
        include IZCRMEF_PLUGIN_BASEDIR . '/views/view-root.php';
    }

    public function RemoveAdminNotices()
    {
        global $plugin_page;
        if (empty($plugin_page) || strpos($plugin_page, 'elementor-to-zoho-crm') === false) {
            return;
        }

        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
    }
}
