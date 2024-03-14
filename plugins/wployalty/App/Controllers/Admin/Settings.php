<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Admin;
defined('ABSPATH') or die;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\CompatibleCheck;
use Wlr\App\Helpers\Validation;
use Wlr\App\Helpers\Woocommerce;

class Settings extends Base
{
    /* Settings*/
    function getSettings()
    {
        $data = array(
            'success' => false
        );
        if (!$this->isBasicSecurityValid('wlr_setting_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __('Basic validation failed', 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        $data['success'] = true;
        $setting_data = get_option('wlr_settings', array());
        if (!is_array($setting_data)) {
            $setting_data = array();
        }
        $setting_data = apply_filters('wlr_get_setting_data', $setting_data);
        $data['data'] = $setting_data;
        $data['data']['email_content'] = array();
        \WC_Emails::instance();
        $data = apply_filters('wlr_notify_email_content_data', $data);
        wp_send_json($data);
    }

    function saveSettings()
    {
        $option_key = (string)self::$input->post_get('option_key', '');
        $option_key = Validation::validateInputAlpha($option_key);
        $response = array();
        if (empty($option_key) || !$this->isBasicSecurityValid('wlr_setting_nonce')) {
            $response['success'] = false;
            $response['data']['message'] = __('Basic validation failed', 'wp-loyalty-rules');
            wp_send_json($response);
        }

        $data = self::$input->post();
        $is_valid = apply_filters('wlr_is_license_valid', true, $data);
        $unset_array = array('option_key', 'action', 'wlr_nonce', 'license_key');
        foreach ($unset_array as $unset_key) {
            if (isset($data[$unset_key])) {
                unset($data[$unset_key]);
            }
        }
        $data = apply_filters('wlr_before_save_settings', $data, $option_key);
        $validate_data = Validation::validateSettingsTab($data);
        if (is_array($validate_data)) {
            foreach ($validate_data as $field => $messages) {
                $validate_data[$field] = explode(',', str_replace('Wlr', '', implode(',', $messages)));
            }
            $response['success'] = false;
            $response['data']['field_error'] = $validate_data;
            $response['data']['message'] = __('Settings not saved!', 'wp-loyalty-rules');
            wp_send_json($response);
        }
        foreach ($data as $d_key => $d_value) {
            if (in_array($d_key, array(
                'wlr_cart_earn_points_message',
                'wlr_checkout_earn_points_message',
                'wlr_cart_redeem_points_message',
                'wlr_checkout_redeem_points_message',
                'wlr_thank_you_message',
                'wlr_earn_point_order_summary_text',
                'wlr_point_label',
                'wlr_point_singular_label',
                'reward_plural_label',
                'reward_singular_label'
            ))) {
                $d_value = stripslashes($d_value);
                $data[$d_key] = Woocommerce::getCleanHtml($d_value);
            }
        }
        if (isset($data['generate_api_key']) && $data['generate_api_key'] == 1) {
            $consumer_key = 'ck_' . wc_rand_hash();
            $consumer_secret = 'cs_' . wc_rand_hash();
            $data['wlr_client_id'] = $consumer_key;
            $data['wlr_client_secret'] = $consumer_secret;
            $response['redirect'] = admin_url('admin.php?' . http_build_query(array('page' => WLR_PLUGIN_SLUG, 'view' => 'settings')));
        }
        update_option($option_key, $data, true);
        do_action('wlr_after_save_settings', $data, $option_key);
        $response['success'] = true;
        $response['message'] = esc_html__('Settings saved successfully!', 'wp-loyalty-rules');
        if (!$is_valid) {
            $response['success'] = false;
            $response['data']['field_error']['license_key'] = array(__('License key invalid', 'wp-loyalty-rules'));
            //$response['data']['message'] = __('License key invalid', 'wp-loyalty-rules');
        }
        wp_send_json($response);
    }

    function createBlock()
    {
        $data = array();
        if (!$this->isBasicSecurityValid('wlr_common_user_nonce')) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Basic validation failed", 'wp-loyalty-rules')
            );
            wp_send_json($data);
        }
        try {
            $post_information = array(
                'post_title' => 'Loyalty Reward Page',
                'post_content' => '<!-- wp:shortcode -->
[wlr_page_content]
<!-- /wp:shortcode -->',
                'post_type' => 'page',
                'post_status' => 'pending'
            );
            $post_id = wp_insert_post($post_information);
            if (!empty($post_id)) {
                $data['success'] = true;
                $data['data'] = array(
                    'post_id' => $post_id,
                    'message' => __("Page created successfully", 'wp-loyalty-rules')
                );
                wp_send_json($data);
            }
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Page creation has failed", 'wp-loyalty-rules')
            );
        } catch (\Exception $e) {
            $data['success'] = false;
            $data['data'] = array(
                'message' => __("Page creation has failed", 'wp-loyalty-rules')
            );
        }
        wp_send_json($data);
    }

    /*End Settings*/

    function updateEmailTemplate()
    {
        $data = array(
            'success' => false,
            'data' => array(
                'message' => __("Email template update failed.", "wp-loyalty-rules"),
            ),
        );
        $email_type = (string)self::$input->post('email_type', '');
        $email_type = Validation::validateInputAlpha($email_type);
        $template_body = (string)self::$input->post('template_body', '', false);
        if (!$this->isBasicSecurityValid('wlr_setting_nonce') || empty($email_type) || empty($template_body)
            || !$this->isValidEmailType($email_type)) {
            $data['data']['message'] = __("Security check failed.", "wp-loyalty-rules");
            wp_send_json($data);
        }
        $status = apply_filters('wlr_save_email_template', false, $template_body, $email_type);
        if ($status) {
            $data['success'] = true;
            $data['data']['message'] = __("Email template updated successfully.", "wp-loyalty-rules");
        }
        wp_send_json($data);
    }

    function isValidEmailType($email_type)
    {
        if (empty($email_type) || !is_string($email_type)) return false;
        $email_types = apply_filters('wlr_is_valid_email_types', array('earn_point_email', 'earn_reward_email', 'expire_email', 'expire_point_email', 'birthday_email', 'new_level_email'));
        return in_array($email_type, $email_types);
    }

    function isAnyNotifications()
    {
        $data = array();
        $data['success'] = true;
        $data['data'] = array();
        $check = new CompatibleCheck();
        $content = $check->getCompatibleContent();
        if (!empty($content)) {
            $data['data']['title'] = __('Plugin Compatible', "wp-loyalty-rules");
            $data['data']['content'] = $content;
        }
        $data = apply_filters('wlr_is_any_dynamic_notification', $data);
        wp_send_json($data);
    }
}