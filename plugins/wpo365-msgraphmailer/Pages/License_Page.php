<?php

namespace Wpo\Pages;

use \Wpo\Core\Extensions_Helpers;
use \Wpo\Core\WordPress_Helpers;
use \Wpo\Core\Wpmu_Helpers;
use \Wpo\Services\Options_Service;
use \Wpo\Services\Log_Service;

// Prevent public access to this script
defined('ABSPATH') or die();

if (!class_exists('\Wpo\Pages\License_Page')) {

    class License_Page
    {

        private static $extensions = array();

        public function __construct()
        {

            /**
             * Load custom updater.
             */

            // Multisite frontend
            if (is_multisite() && !is_network_admin()) {
                return;
            }

            // Single site frontend
            if (!is_multisite() && !is_admin()) {
                return;
            }

            // Collect information about all activated extensions
            self::$extensions = Extensions_Helpers::get_extensions();

            // No extensions so no need to add the license page
            if (empty(self::$extensions)) {
                return;
            }

            /**
             * Add admin page.
             */
            add_action('admin_menu', '\Wpo\Pages\License_Page::license_menu');
            add_action('network_admin_menu', '\Wpo\Pages\License_Page::license_menu');

            /**
             * Activate license.
             */
            add_action('admin_init', '\Wpo\Pages\License_Page::activate_license');

            /**
             * Deactivate license.
             */
            add_action('admin_init', '\Wpo\Pages\License_Page::deactivate_license');

            /**
             * Show activation result.
             */
            add_action('admin_notices', '\Wpo\Pages\License_Page::activation_notice');
            add_action('network_admin_notices', '\Wpo\Pages\License_Page::activation_notice');
        }

        /**
         * Adds a "Licenses" submenu page to the main WPO365 admin menu.
         */
        public static function license_menu()
        {
            add_submenu_page('wpo365-wizard', 'Licenses', 'Licenses', 'delete_users', 'wpo365-manage-licenses', '\Wpo\Pages\License_Page::license_page');
        }

        public static function activation_notice()
        {

            if (isset($_GET['sl_activation']) && !empty($_GET['message']) && isset($_GET['page']) && $_GET['page'] == 'wpo365-manage-licenses') {

                $message = sanitize_text_field(urldecode($_GET['message']));

                switch ($_GET['sl_activation']) {

                    case 'false': ?>
                        <div class="notice notice-error">
                            <p><?php echo esc_html($message); ?></p>
                        </div>
                    <?php
                        break;

                    case 'true':
                    default:
                    ?>
                        <div class="notice notice-success"><?php echo esc_html($message) ?></div>
            <?php
                        break;
                }
            }
        }

        public static function activate_license()
        {

            // listen for our activate button to be clicked
            if (isset($_POST['activate_license']) && isset($_POST['store_item_id'])) {

                foreach (self::$extensions as $slug => $data) {

                    if ($data['store_item_id'] === intval(trim($_POST['store_item_id']))) {
                        $extension = $data;
                        break;
                    }
                }

                if (empty($extension)) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Could not find extension for store item id ' . WordPress_Helpers::trim(sanitize_text_field($_POST['store_item_id'])));
                    return;
                }

                // run a quick security check
                if (!check_admin_referer('wpo365-manage-licenses', 'wpo365_license_nonce')) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Could not successfully verify nonce [check admin referrer failed]');
                    return;
                }

                // retrieve the license from the POSTed data
                $license_key_name = 'license_' . $extension['store_item_id'];
                $posted_license_key = !empty($_POST[$license_key_name]) ? $_POST[$license_key_name] : '';
                $license_key = sanitize_text_field(trim($posted_license_key));

                // Call the custom API.
                $url = is_multisite() ? network_home_url() : home_url();
                $response = wp_remote_get(\sprintf("https://www.wpo365.com/?edd_action=activate_license&license=%s&item_id=%s&url=%s", $license_key, $extension['store_item_id'], $url), array('timeout' => 15, 'sslverify' => false));

                // make sure the response came back okay
                if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                    if (is_wp_error($response)) {
                        $message = $response->get_error_message();
                    } else {
                        Log_Service::write_log('ERROR', __METHOD__ . ' -> Error occurred when activating license. Check the next line for the raw response message received.');
                        Log_Service::write_log('ERROR', $response);

                        $message = __('An error occurred, please try again.');
                    }
                } else {

                    $license_data = json_decode(wp_remote_retrieve_body($response));

                    if ($license_data->license == 'invalid') {

                        switch ($license_data->error) {

                            case 'expired':
                                $message = sprintf(
                                    __('Your license key for <strong>%s</strong> expired on %s.'),
                                    $extension['store_item'],
                                    date_i18n(get_option('date_format'), strtotime($license_data->expires, current_time('timestamp')))
                                );
                                break;

                            case 'disabled':
                                $message = sprintf(
                                    __('Your license key for <strong>%s</strong> has been disabled / revoked.'),
                                    $extension['store_item']
                                );
                                break;

                            case 'missing':
                                $message = sprintf(
                                    __('The license <strong>%s</strong> you entered does not exist.'),
                                    $license_key
                                );
                                break;

                            case 'missing_url':
                                $message = __('URL not provided.');
                                break;

                            case 'key_mismatch':
                                $message = sprintf(
                                    __('The license <strong>%s</strong> appears to be an invalid license key for %s.'),
                                    $license_key,
                                    $extension['store_item']
                                );
                                break;

                            case 'item_name_mismatch':
                                $message = sprintf(
                                    __('The license <strong>%s</strong> appears to be invalid for %s.'),
                                    $license_key,
                                    $extension['store_item']
                                );
                                break;

                            case 'invalid_item_id':
                                $message = sprintf(
                                    __('The item ID <strong>%s</strong> appears to be invalid.'),
                                    $extension['store_item_id']
                                );
                                break;

                            case 'no_activations_left':
                                $message = sprintf(
                                    __('Your license key <strong>%s</strong> has reached its activation limit.'),
                                    $license_key
                                );
                                break;

                            case 'license_not_activable':
                                $message = sprintf(
                                    __('Cannot activate the parent license <strong>%s</strong> of a bundle.'),
                                    $license_key
                                );
                                break;

                            default:
                                Log_Service::write_log('ERROR', __METHOD__ . ' -> Error occurred when activating license. Check the next line for the license data received.');
                                Log_Service::write_log('ERROR', $license_data);

                                $message = __('An error occurred, please try again.');
                                break;
                        }
                    }
                }

                $option_name = 'license_' . $extension['store_item_id'];
                $base_url = is_multisite()
                    ? network_admin_url('admin.php?page=wpo365-manage-licenses')
                    : admin_url('admin.php?page=wpo365-manage-licenses');

                if (!empty($message)) {
                    Options_Service::add_update_option($option_name, ''); // WPMU > Will update site option because this page is only availabe in the network-admin
                    $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);
                } else {
                    Options_Service::add_update_option($option_name, sprintf($license_key, $url)); // WPMU > Will update site option because this page is only availabe in the network-admin
                    $redirect = add_query_arg(array('sl_activation' => 'true', 'message' => urlencode('License for ' . $extension['store_item'] . ' has been successfully activated.')), $base_url);
                }

                \Wpo\Core\Plugin_Updater::check_licenses();

                wp_redirect($redirect);
                exit();
            }
        }

        public static function deactivate_license()
        {

            if (isset($_POST['deactivate_license']) && isset($_POST['store_item_id'])) {

                foreach (self::$extensions as $slug => $data) {

                    if ($data['store_item_id'] === intval(trim($_POST['store_item_id']))) {
                        $extension = $data;
                        break;
                    }
                }

                if (empty($extension)) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Could not find extension for store item id ' . sanitize_text_field(trim($_POST['store_item_id'])));
                    return;
                }

                // run a quick security check
                if (!check_admin_referer('wpo365-manage-licenses', 'wpo365_license_nonce')) {
                    Log_Service::write_log('WARN', __METHOD__ . ' -> Could not successfully verify nonce [check admin referrer failed]');
                    return;
                }

                // retrieve the license from the POSTed data
                $license_key_name = 'license_' . $extension['store_item_id'];
                $posted_license_key = !empty($_POST[$license_key_name]) ? $_POST[$license_key_name] : '';
                $license_key = sanitize_text_field(trim($posted_license_key));

                // Call the custom API.
                $url = is_multisite() ? network_home_url() : home_url();
                $response = wp_remote_get(\sprintf("https://www.wpo365.com/?edd_action=deactivate_license&license=%s&item_id=%s&url=%s", $license_key, $extension['store_item_id'], $url), array('timeout' => 15, 'sslverify' => false));

                // make sure the response came back okay
                if (is_wp_error($response) || 200 !== wp_remote_retrieve_response_code($response)) {

                    if (is_wp_error($response)) {
                        $message = $response->get_error_message();
                    } else {
                        $message = __('An error occurred, please try again.');
                    }
                }

                $option_name = 'license_' . $extension['store_item_id'];
                $base_url = is_multisite()
                    ? network_admin_url('admin.php?page=wpo365-manage-licenses')
                    : admin_url('admin.php?page=wpo365-manage-licenses');

                if (!empty($message)) {
                    $redirect = add_query_arg(array('sl_activation' => 'false', 'message' => urlencode($message)), $base_url);
                } else {
                    Options_Service::add_update_option($option_name, '');
                    $license_data = json_decode(wp_remote_retrieve_body($response));

                    if ($license_data->license == 'deactivated') {
                        $redirect = add_query_arg(array('sl_activation' => 'true', 'message' => urlencode('License for ' . $extension['store_item'] . ' has been successfully deactivated.')), $base_url);
                    } else {
                        $redirect = add_query_arg(array('sl_activation' => 'true', 'message' => urlencode('License for ' . $extension['store_item'] . ' could not be deactivated. Please try again.')), $base_url);
                    }
                }

                \Wpo\Core\Plugin_Updater::check_licenses();

                wp_redirect($redirect);
                exit();
            }
        }

        public static function license_page()
        {
            ?>
            <style>
                .wpo365-license-table {
                    background: #ffffff;
                    border: 1px solid #cccccc;
                    box-sizing: border-box;
                    float: left;
                    margin: 0 15px 15px 0;
                    max-width: 350px;
                    min-height: 220px;
                    padding: 14px;
                    position: relative;
                    position: relative;
                    width: 30.5%;
                }

                .wpo365-license-table TH {
                    background-color: #f9f9f9;
                    border-bottom: 1px solid #cccccc;
                    display: block;
                    margin: -14px -14px 20px;
                    padding: 14px;
                    width: 100%;
                }

                .wpo365-license-table TD {
                    display: block;
                    padding: 0;

                }

                .wpo365-license-table TD input[type=text] {
                    margin: 0 0 8px;
                    width: 100%;
                }

                .wpo365-license-table TD DIV {
                    background: #fafafa;
                    border-top: 1px solid #eeeeee;
                    bottom: 14px;
                    box-sizing: border-box;
                    margin: 20px -14px -14px;
                    min-height: 67px;
                    padding: 14px;
                    position: absolute;
                    width: 100%;
                }
            </style>
            <div class="wrap">
                <h2><?php _e('WPO365 | Licenses'); ?></h2>
                <form method="post">
                    <input type="hidden" id="store_item_id" name="store_item_id">
                    <table class="form-table">
                        <tbody>

                            <?php foreach (self::$extensions as $slug => $data) :
                                $license_key_name = 'license_' . $data['store_item_id'];
                                $license_key = '';
                                $network_options = get_site_option('wpo365_options');

                                if (!empty($network_options[$license_key_name])) {
                                    $license_option = $network_options[$license_key_name];

                                    if (WordPress_Helpers::stripos($license_option, '|') > -1) {
                                        $exploded = explode('|', $license_option);
                                        $license_key = $exploded[0];
                                    } else {
                                        $license_key = $license_option;
                                    }
                                }
                            ?>
                                <tr valign="top" class="wpo365-license-table">
                                    <th scope="row" valign="top">
                                        <?php echo esc_html($data['store_item']) ?>
                                    </th>
                                    <td>
                                        <?php echo wp_nonce_field('wpo365-manage-licenses', 'wpo365_license_nonce'); ?>
                                        <input type="text" class="regular-text" id="<?php echo esc_attr($license_key_name) ?>" name="<?php echo esc_attr($license_key_name) ?>" value="<?php echo esc_attr($license_key) ?>">

                                        <?php if (!empty($license_key)) : ?>
                                            <input type="submit" class="button-secondary" name="deactivate_license" value="<?php _e('Deactivate License'); ?>" onclick="document.getElementById('store_item_id').value = <?php echo esc_attr($data['store_item_id']) ?>" />
                                        <?php else : ?>
                                            <input type="submit" class="button-secondary" name="activate_license" value="<?php _e('Activate License'); ?>" onclick="document.getElementById('store_item_id').value = <?php echo esc_attr($data['store_item_id']) ?>" />
                                        <?php endif ?>

                                        <div>
                                            <p><a href="https://www.wpo365.com/your-account/" target="_blank">Manage Sites</a></p>
                                        </div>
                                    </td>
                                </tr>

                            <?php endforeach ?>
                        </tbody>
                    </table>
                </form>
    <?php
        }
    }
}
