<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaign;

defined('ABSPATH') or die();

class Apps extends Base
{
    function getApps()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_apps_nonce')) {
            $data = array(
                'success' => false,
                'data' => array(
                    'message' => __('You do not have access to the app page', 'wp-loyalty-rules')
                )
            );
            wp_send_json($data);
        }
        $plugins = array();
        $is_pro = \Wlr\App\Helpers\EarnCampaign::getInstance()->isPro();
        $install_plugins = get_plugins();
        foreach ($install_plugins as $plugin_path => $plugin_detail) {
            $plugin_detail = apply_filters('wlr_before_process_plugin_data', $plugin_detail, $plugin_path);
            if (isset($plugin_detail['WPLoyalty']) && !empty($plugin_detail['WPLoyalty']) && version_compare(WLR_PLUGIN_VERSION, $plugin_detail['WPLoyalty'], ">=")) {
                $page_link = isset($plugin_detail['WPLoyalty Page Link']) && !empty($plugin_detail['WPLoyalty Page Link']) ? admin_url('admin.php?' . http_build_query(array('page' => $plugin_detail['WPLoyalty Page Link']))) : '';
                if (!$is_pro && empty($page_link)) continue;
                $plugins[] = array(
                    'icon' => isset($plugin_detail['WPLoyalty Icon']) && !empty($plugin_detail['WPLoyalty Icon']) ? $plugin_detail['WPLoyalty Icon'] : '',
                    'title' => isset($plugin_detail['Title']) && !empty($plugin_detail['Title']) ? $plugin_detail['Title'] : '',
                    'version' => isset($plugin_detail['Version']) && !empty($plugin_detail['Version']) ? $plugin_detail['Version'] : '',
                    'author' => isset($plugin_detail['Author']) && !empty($plugin_detail['Author']) ? $plugin_detail['Author'] : '',
                    'description' => isset($plugin_detail['Description']) && !empty($plugin_detail['Description']) ? $plugin_detail['Description'] : '',
                    'document_link' => isset($plugin_detail['WPLoyalty Document Link']) && !empty($plugin_detail['WPLoyalty Document Link']) ? $plugin_detail['WPLoyalty Document Link'] : '',
                    'is_active' => $this->isPluginIsActive($plugin_path),
                    'plugin' => $plugin_path,
                    'page_url' => $page_link
                );
            }
        }
        $apps = apply_filters('wlr_loyalty_apps', $plugins);
        $search = (string)self::$input->post_get('search', '');
        if (!empty($search) && preg_match_all('/".*?("|$)|((?<=[\t ",+])|^)[^\t ",+]+/', strtolower($search), $matches)) {
            $base_model = new EarnCampaign();
            $search_terms = $base_model->getValidSearchWords($matches[0]);
            foreach ($apps as $key => $app) {
                if (!$this->checkSearchAvailable($search_terms, strtolower($app['title']))) unset($apps[$key]);
            }
        }
        $data['success'] = true;
        $data['data'] = array(
            'items' => $apps
        );
        wp_send_json($data);
    }

    function isPluginIsActive($plugin_path)
    {
        $active_plugins = apply_filters('active_plugins', get_option('active_plugins', array()));
        if (is_multisite()) {
            $active_plugins = array_merge($active_plugins, get_site_option('active_sitewide_plugins', array()));
        }
        return in_array($plugin_path, $active_plugins) || array_key_exists($plugin_path, $active_plugins);
    }

    function checkSearchAvailable($search_terms, $search_values)
    {
        if (!is_array($search_terms)) return false;
        if (is_string($search_values)) $search_values = array($search_values);
        if (is_object($search_values)) $search_values = (array)$search_values;
        $is_all_success = array();
        foreach ($search_terms as $search) {
            $status = false;
            foreach ($search_values as $search_value) {
                if (strpos($search_value, $search) !== false) {
                    $status = true;
                }
            }
            $is_all_success[] = $status;
        }
        return !in_array(false, $is_all_success);
    }

    function activateApp()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_apps_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $plugin = (string)self::$input->post_get('plugin', '');
        if ($plugin == 'wlr_app_launcher') {
            update_option('wlr_launcher_active', 'yes');
            $data['success'] = true;
            $data['data'] = array(
                'message' => __('App activated successfully', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        if ($plugin == 'wlr_app_point_expire') {
            update_option('wlr_expire_point_active', 'yes');
            $data['success'] = true;
            $data['data'] = array(
                'message' => __('App activated successfully', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        if (!current_user_can('activate_plugin', $plugin)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Sorry, you are not allowed to activate this plugin.', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }

        if (is_multisite() && !is_network_admin() && is_network_only_plugin($plugin)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Sorry, you are not allowed to activate this plugin.', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $result = activate_plugin($plugin, '', is_network_admin());
        if (is_wp_error($result)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('App activation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }

        if (!is_network_admin()) {
            $recent = (array)get_option('recently_activated');
            unset($recent[$plugin]);
            update_option('recently_activated', $recent);
        } else {
            $recent = (array)get_site_option('recently_activated');
            unset($recent[$plugin]);
            update_site_option('recently_activated', $recent);
        }
        $data['success'] = true;
        $data['data'] = array(
            'message' => __('App activated successfully', 'wp-loyalty-rules')
        );
        wp_send_json($data);
    }

    function deActivateApp()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_apps_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $plugin = (string)self::$input->post_get('plugin', '');
        if ($plugin == 'wlr_app_launcher') {
            update_option('wlr_launcher_active', 'no');
            $data['success'] = true;
            $data['data'] = array(
                'message' => __('App deactivated successfully', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        if ($plugin == 'wlr_app_point_expire') {
            update_option('wlr_expire_point_active', 'no');
            $data['success'] = true;
            $data['data'] = array(
                'message' => __('App deactivated successfully', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        if (!current_user_can('deactivate_plugin', $plugin)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Sorry, you are not allowed to deactivate this plugin.', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }

        if (!is_network_admin() && is_plugin_active_for_network($plugin)) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Sorry, you are not allowed to deactivate this plugin.', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }

        deactivate_plugins($plugin, false, is_network_admin());

        if (!is_network_admin()) {
            update_option('recently_activated', array($plugin => time()) + (array)get_option('recently_activated'));
        } else {
            update_site_option('recently_activated', array($plugin => time()) + (array)get_site_option('recently_activated'));
        }
        $data['success'] = true;
        $data['data'] = array(
            'message' => __('App deactivated successfully', 'wp-loyalty-rules')
        );
        wp_send_json($data);
    }

}