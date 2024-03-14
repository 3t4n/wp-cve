<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Models\EarnCampaign;
use Wlr\App\Models\Levels;
use Wlr\App\Models\PointsLedger;
use Wlr\App\Models\Rewards;
use Wlr\App\Models\UserRewards;
use Wlr\App\Models\Users;

defined('ABSPATH') or die;

class Schedules extends Base
{
    function initSchedule()
    {
        //every 1 hour
        $hook = 'wlr_expire_email';
        $timestamp = wp_next_scheduled($hook);
        if (false === $timestamp) {
            $scheduled_time = strtotime('+1 hours', current_time('timestamp'));
            wp_schedule_event($scheduled_time, 'hourly', $hook);
        }
        $hook = 'wlr_change_expire_status';
        $timestamp = wp_next_scheduled($hook);
        if (false === $timestamp) {
            $scheduled_time = strtotime('+1 hours', current_time('timestamp'));
            wp_schedule_event($scheduled_time, 'hourly', $hook);
        }
        $is_point_transfer_complete = get_option('wlr_point_ledger_complete', 0);
        if (!$is_point_transfer_complete) {
            $user_model = new Users();
            $user_list = $user_model->getWhere('id > 0 LIMIT 5 OFFSET 0', '*', false);
            if (!empty($user_list)) {
                $hook = 'wlr_update_ledger_point';
                $timestamp = wp_next_scheduled($hook);
                if (false === $timestamp) {
                    $scheduled_time = strtotime('+1 hours', current_time('timestamp'));
                    wp_schedule_event($scheduled_time, 'hourly', $hook);
                }
            } else {
                update_option('wlr_point_ledger_complete', 1);
            }
        }
		//notification remind me later schedule
        $is_enabled = get_option('wlr_new_rewards_section_enabled', '');
        if ($is_enabled != 'yes') {
            $hook = 'wlr_notification_remind_me';
            $timestamp = wp_next_scheduled($hook);
            if (false == $timestamp) {
                $scheduled_time = strtotime('+10 days', current_time('timestamp'));
                wp_schedule_event($scheduled_time, 'daily', $hook);
            }
        }
        do_action('wlr_schedule_event_register');
    }

    function updatePointLedgerFromUser()
    {
        $off_set = get_option('wlr_update_ledger_offset', 0);
        global $wpdb;
        $user_model = new Users();
        $point_ledger_model = new PointsLedger();
        $where = $wpdb->prepare('id > 0 ORDER BY id ASC LIMIT 100 OFFSET %d', array($off_set));
        $user_list = $user_model->getWhere($where, '*', false);
        if (empty($user_list)) {
            update_option('wlr_point_ledger_complete', 1);
        } else {
            update_option('wlr_update_ledger_offset', (int)($off_set + 100));
        }
        if (!empty($user_list)) {
            foreach ($user_list as $user) {
                $ledger_where = $wpdb->prepare("user_email = %s", array($user->user_email));
                $ledger = $point_ledger_model->getWhere($ledger_where, '*', true);
                if (empty($ledger)) {
                    $base_helper = new \Wlr\App\Helpers\Base();
                    $data = array(
                        'user_email' => $user->user_email,
                        'points' => $user->points,
                        'action_type' => 'starting_point',
                        'note' => __('Starting point of customer', 'wp-loyalty-rules'),
                        'created_at' => strtotime(date("Y-m-d H:i:s"))
                    );
                    $base_helper->updatePointLedger($data, 'credit', $is_update = false);
                }
            }
        }
    }
	function enableNotificationSection(){
		$setting = self::$woocommerce->getOptions('wlr_new_rewards_section_enabled', '');
		if (!empty($setting) || $setting == 'no'){
			update_option('wlr_new_rewards_section_enabled', '');
		}
	}
    function sendExpireEmail()
    {
        $user_reward = new UserRewards();
        $user_reward_data = $user_reward->getExpireEmailList();
        \WC_Emails::instance();
        foreach ($user_reward_data as $single_user_reward) {
            do_action('wlr_notify_send_expire_email', $single_user_reward);
        }
    }

    function changeExpireStatus()
    {
        $user_reward = new UserRewards();
        $user_reward_data = $user_reward->getExpireStatusNeedToChangeList();
        $updateData = array(
            'status' => 'expired',
        );
        foreach ($user_reward_data as $single_user_reward) {
            $where = array('id' => $single_user_reward->id);
            $user_reward->updateRow($updateData, $where);
        }
    }

    function removeSchedule()
    {
        $next_scheduled = wp_next_scheduled('wlr_birth_day_points');
        wp_unschedule_event($next_scheduled, 'wlr_birth_day_points');
        $next_scheduled = wp_next_scheduled('wlr_expire_email');
        wp_unschedule_event($next_scheduled, 'wlr_expire_email');
        $next_scheduled = wp_next_scheduled('wlr_change_expire_status');
        wp_unschedule_event($next_scheduled, 'wlr_change_expire_status');
        $next_scheduled = wp_next_scheduled('wlr_update_ledger_point');
        wp_unschedule_event($next_scheduled, 'wlr_update_ledger_point');
    }

    function dynamicStrings($new_strings, $domain_text)
    {
        if (!is_array($new_strings) || !is_string($domain_text) || $domain_text != 'wp-loyalty-rules') {
            return $new_strings;
        }
        $this->getCampaignDynamicStrings($new_strings);
        $this->getRewardDynamicStrings($new_strings);
        $this->getLevelDynamicStrings($new_strings);
        $this->getSettingsDynamicStrings($new_strings);
        return $new_strings;
    }

    function getCampaignDynamicStrings(&$new_strings)
    {
        $common_strings = array('name', 'description');
        $campaign_model = new EarnCampaign();
        $campaign_list = $campaign_model->getAll('*');
        if (!empty($campaign_list)) {
            foreach ($campaign_list as $campaign) {
                foreach ($common_strings as $key) {
                    if (isset($campaign->$key) && !empty($campaign->$key)) {
                        $new_strings[] = $campaign->$key;
                    }
                }
                if (isset($campaign->action_type) && in_array($campaign->action_type, array('point_for_purchase', 'product_review', 'signup', 'facebook_share', 'twitter_share', 'whatsapp_share', 'email_share'))) {
                    $point_rule = new \stdClass();
                    if (isset($campaign->point_rule) && !empty($campaign->point_rule) && self::$woocommerce->isJson($campaign->point_rule)) {
                        $point_rule = json_decode($campaign->point_rule);
                    }
                    $this->getDynamicActionString($new_strings, $point_rule, $campaign->action_type);
                }
            }
        }
    }

    function getDynamicActionString(&$new_strings, $point_rule, $action_type)
    {
        if (empty($action_type) || !is_string($action_type) || !is_array($new_strings) || !is_object($point_rule)) {
            return;
        }
        $action_strings = array(
            'point_for_purchase' => array('variable_product_message', 'single_product_message'),
            'product_review' => array('review_message'),
            'signup' => array('signup_message'),
            'facebook_share' => array('share_message'),
            'twitter_share' => array('share_message'),
            'whatsapp_share' => array('share_message'),
            'email_share' => array('share_body', 'share_subject')
        );
        if (isset($action_strings[$action_type]) && !empty($action_strings[$action_type])) {
            foreach ($action_strings[$action_type] as $key) {
                if (isset($point_rule->$key) && !empty($point_rule->$key)) {
                    $new_strings[] = $point_rule->$key;
                }
            }
        }
    }

    function getRewardDynamicStrings(&$new_strings)
    {
        $common_strings = array('name', 'description', 'display_name');
        $reward_model = new Rewards();
        $reward_list = $reward_model->getAll('*');
        if (!empty($reward_list)) {
            foreach ($reward_list as $reward) {
                foreach ($common_strings as $key) {
                    if (isset($reward->$key) && !empty($reward->$key)) {
                        $new_strings[] = $reward->$key;
                    }
                }
            }
        }
    }

    function getLevelDynamicStrings(&$new_strings)
    {
        $common_strings = array('name', 'description');
        $level_model = new Levels();
        $level_list = $level_model->getAll('*');
        if (!empty($level_list)) {
            foreach ($level_list as $level) {
                foreach ($common_strings as $key) {
                    if (isset($level->$key) && !empty($level->$key)) {
                        $new_strings[] = $level->$key;
                    }
                }
            }
        }
    }

    function getSettingsDynamicStrings(&$new_strings)
    {
        $common_strings = array('wlr_point_label', 'wlr_point_singular_label', 'reward_plural_label', 'reward_singular_label',
            'wlr_cart_earn_points_message', 'wlr_cart_redeem_points_message', 'wlr_checkout_earn_points_message', 'wlr_checkout_redeem_points_message',
            'wlr_thank_you_message', 'redeem_button_text', 'apply_coupon_button_text');
        $options = self::$woocommerce->getOptions('wlr_settings');
        if (isset($options) && is_array($options)) {
            foreach ($common_strings as $key) {
                if (isset($options[$key]) && !empty($options[$key])) {
                    $new_strings[] = $options[$key];
                }
            }
        }
    }

    function dynamicDomain($domains)
    {
        if (!in_array('wp-loyalty-rules', $domains)) {
            $domains[] = 'wp-loyalty-rules';
        }
        return $domains;
    }
}