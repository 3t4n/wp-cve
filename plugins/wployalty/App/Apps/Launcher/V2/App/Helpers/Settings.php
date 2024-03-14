<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Helpers;

use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Woocommerce;

defined('ABSPATH') or die();

class Settings extends Base
{
    public static $active_referral_campaign, $short_code_list = array();
    public static $saved_design_settings = array();
    public static $saved_content_settings = array();
    public static $saved_launcher_common_settings = array();

    /**
     * Shortcode list with labels
     *
     * @return array
     */
    public static function shortCodesWithLabels()
    {
        $earn_campaign_helper = EarnCampaign::getInstance();
        return apply_filters('', array(
            'common' => array(
                array('value' => '{wlr_site_title}', 'label' => __('Displays site title', 'wp-loyalty-rules'))
            ),
            'guest' => array(
                array('value' => '{wlr_signup_url}', 'label' => __('Sign-up URL (Registration URL)', 'wp-loyalty-rules')),
                array('value' => '{wlr_signin_url}', 'label' => __('Sign-in URL (The Login URL in your site)', 'wp-loyalty-rules')),
            ),
            'member' => array(
                array('value' => '{wlr_user_name}', 'label' => __('Displays customer’s name', 'wp-loyalty-rules')),
                array('value' => '{wlr_user_points}', 'label' => sprintf(__('Displays customer’s %s', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))),
                array('value' => '{wlr_point_label}', 'label' => __('Displays the “points label” as configured in the settings', 'wp-loyalty-rules')),
            ),
            'referral' => array(
                array('value' => '{wlr_referral_advocate_point}', 'label' => sprintf(__('Displays %s reward for existing customers / advocates as configured in the referral campaign', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))),
                array('value' => '{wlr_referral_advocate_point_percentage}', 'label' => sprintf(__('Displays %s percentage for existing customers / advocates as configured in the referral campaign', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))),
                array('value' => '{wlr_referral_advocate_reward}', 'label' => __('Displays any direct coupon rewards for existing customers / advocates as configured in the referral campaign', 'wp-loyalty-rules')),
                array('value' => '{wlr_referral_friend_point}', 'label' => sprintf(__('Displays %s reward for friends as configured in the referral campaign', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))),
                array('value' => '{wlr_referral_friend_point_percentage}', 'label' => sprintf(__('Displays %s percentage for friends as configured in the referral campaign', 'wp-loyalty-rules'), $earn_campaign_helper->getPointLabel(3))),
                array('value' => '{wlr_referral_friend_reward}', 'label' => __('Displays any direct coupon reward for friends as configured in the referral campaign', 'wp-loyalty-rules')),
            ),
        ));
    }

    /**
     * Processing shortcodes to values
     * @param $message
     * @param bool $is_admin_page
     * @return mixed
     */
    public function processShortCodes($message, $is_admin_page = false)
    {
        if (empty($message)) {
            return $message;
        }
        $short_codes = $this->getShortCodeList($is_admin_page);
        if (!is_array($short_codes)) {
            return $message;
        }
        foreach ($short_codes as $key => $value) {
            $message = str_replace($key, $value, $message);
        }

        return apply_filters('wll_process_message_short_codes', $message, $short_codes);
    }

    /**
     * Getting shortcode list
     * @return array
     */
    public function getShortCodeList($is_admin_page = true)
    {
        if (!empty(self::$short_code_list)) return self::$short_code_list;
        $earn_campaign_helper = EarnCampaign::getInstance();
        $woocommerce = Woocommerce::getInstance();
        $short_code_list = array(
            '{wlr_site_title}' => get_bloginfo(),
            '{wlr_user_name}' => '',
            '{wlr_user_points}' => 0,
            '{wlr_point_label}' => $earn_campaign_helper->getPointLabel(3),//have to check this func object __const with alagesan
            '{wlr_referral_advocate_point}' => '',
            '{wlr_referral_advocate_point_percentage}' => '',
            '{wlr_referral_advocate_reward}' => '',
            '{wlr_referral_friend_point}' => '',
            '{wlr_referral_friend_point_percentage}' => '',
            '{wlr_referral_friend_reward}' => '',
        );
        if ($is_admin_page === true) {
            $short_code_list['{wlr_user_name}'] = 'Stark';
            $short_code_list['{wlr_user_points}'] = '4000';
            $short_code_list['{wlr_signup_url}'] = '#';
            $short_code_list['{wlr_signin_url}'] = '#';
            $short_code_list['{wlr_referral_advocate_point}'] = 5;
            $short_code_list['{wlr_referral_advocate_point_percentage}'] = '5 %';
            $short_code_list['{wlr_referral_advocate_reward}'] = 'REFERRED';
            $short_code_list['{wlr_referral_friend_point}'] = 10;
            $short_code_list['{wlr_referral_friend_point_percentage}'] = '10 %';
            $short_code_list['{wlr_referral_friend_reward}'] = 'NOOBIE';
        } elseif ($is_admin_page === false) {
            $user = wp_get_current_user();
            $user_email = $woocommerce->get_login_user_email();
            if (!empty($user_email)) {
                $referral_points = $this->getReferralPoint();
                $points = $earn_campaign_helper->getUserPoint($user_email);
                $short_code_list['{wlr_user_points}'] = $points;
                $short_code_list['{wlr_point_label}'] = $earn_campaign_helper->getPointLabel($points);
                $short_code_list['{wlr_user_name}'] = (isset($user->display_name) && !empty($user->display_name) ? $user->display_name : '');
                $short_code_list['{wlr_referral_advocate_point}'] = isset($referral_points['wlr_referral_advocate_point']) && !empty($referral_points['wlr_referral_advocate_point']) ? $referral_points['wlr_referral_advocate_point'] : 0;
                $short_code_list['{wlr_referral_advocate_point_percentage}'] = isset($referral_points['wlr_referral_advocate_point_percentage']) && !empty($referral_points['wlr_referral_advocate_point_percentage']) ? $referral_points['wlr_referral_advocate_point_percentage'] . ' %' : '0 %';
                $short_code_list['{wlr_referral_advocate_reward}'] = isset($referral_points['wlr_referral_advocate_reward']) && !empty($referral_points['wlr_referral_advocate_reward']) ? $referral_points['wlr_referral_advocate_reward'] : 0;
                $short_code_list['{wlr_referral_friend_point}'] = isset($referral_points['wlr_referral_friend_point']) && !empty($referral_points['wlr_referral_friend_point']) ? $referral_points['wlr_referral_friend_point'] : 0;
                $short_code_list['{wlr_referral_friend_point_percentage}'] = isset($referral_points['wlr_referral_friend_point_percentage']) && !empty($referral_points['wlr_referral_friend_point_percentage']) ? $referral_points['wlr_referral_friend_point_percentage'] . ' %' : '0 %';
                $short_code_list['{wlr_referral_friend_reward}'] = isset($referral_points['wlr_referral_friend_reward']) && !empty($referral_points['wlr_referral_friend_reward']) ? $referral_points['wlr_referral_friend_reward'] : 0;
            }
            $my_account_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
            $short_code_list['{wlr_signup_url}'] = $my_account_url;
            $short_code_list['{wlr_signin_url}'] = $my_account_url;
        }
        self::$short_code_list = $short_code_list;
        return apply_filters('wlr_launcher_short_code_list', $short_code_list);
    }

    //launcher v2

    public function getReferralPoint()
    {
        $campaigns = $this->getActiveReferralCampaigns();
        if (empty($campaigns) && is_array($campaigns)) return array();
        $referral_points = array(
            'advocate_points' => 0,
            'advocate_point_percentage' => 0,
            'advocate_reward' => array(),
            'friend_points' => 0,
            'friend_point_percentage' => 0,
            'friend_reward' => array()
        );
        $reward_table = new \Wlr\App\Models\Rewards();
        foreach ($campaigns as $campaign) {
            $point_rule = isset($campaign->point_rule) && !empty($campaign->point_rule) ? json_decode($campaign->point_rule, true) : array();
            /* advocate */
            $advocate_type = isset($point_rule['advocate']) && isset($point_rule['advocate']['campaign_type']) && !empty($point_rule['advocate']['campaign_type']) ? $point_rule['advocate']['campaign_type'] : '';
            if ($advocate_type == 'point') {
                $earn_type = isset($point_rule['advocate']['earn_type']) && !empty($point_rule['advocate']['earn_type']) ? $point_rule['advocate']['earn_type'] : '';
                if ($earn_type == 'fixed_point') {
                    $referral_points['advocate_points'] += (int)isset($point_rule['advocate']['earn_point']) && !empty($point_rule['advocate']['earn_point']) ? $point_rule['advocate']['earn_point'] : 0;
                } elseif ($earn_type == 'subtotal_percentage') {
                    $referral_points['advocate_point_percentage'] += (int)isset($point_rule['advocate']['earn_point']) && !empty($point_rule['advocate']['earn_point']) ? $point_rule['advocate']['earn_point'] : 0;
                }
            } elseif ($advocate_type == 'coupon') {
                $reward_id = (int)isset($point_rule['advocate']['earn_reward']) && !empty($point_rule['advocate']['earn_reward']) ? $point_rule['advocate']['earn_reward'] : 0;
                $reward = !empty($reward_id) ? $reward_table->findReward($reward_id) : new \stdClass();
                if (!empty($reward) && is_object($reward) && isset($reward->display_name)) {
                    $referral_points['advocate_reward'][] = !empty($reward->display_name) ? $reward->display_name : '';
                }
            }
            /* friend */
            $friend_type = isset($point_rule['friend']) && isset($point_rule['friend']['campaign_type']) && !empty($point_rule['friend']['campaign_type']) ? $point_rule['friend']['campaign_type'] : '';
            if ($friend_type == 'point') {
                $earn_type = isset($point_rule['friend']['earn_type']) && !empty($point_rule['friend']['earn_type']) ? $point_rule['friend']['earn_type'] : '';
                if ($earn_type == 'fixed_point') {
                    $referral_points['friend_points'] += (int)isset($point_rule['friend']['earn_point']) && !empty($point_rule['friend']['earn_point']) ? $point_rule['friend']['earn_point'] : 0;
                } elseif ($earn_type == 'subtotal_percentage') {
                    $referral_points['friend_point_percentage'] += (int)isset($point_rule['friend']['earn_point']) && !empty($point_rule['friend']['earn_point']) ? $point_rule['friend']['earn_point'] : 0;
                }
            } elseif ($friend_type == 'coupon') {
                $reward_id = (int)isset($point_rule['friend']['earn_reward']) && !empty($point_rule['friend']['earn_reward']) ? $point_rule['friend']['earn_reward'] : 0;
                $reward = !empty($reward_id) ? $reward_table->findReward($reward_id) : new \stdClass();
                if (!empty($reward) && is_object($reward) && isset($reward->display_name)) {
                    $referral_points['friend_reward'][] = !empty($reward->display_name) ? $reward->display_name : '';
                }
            }
        }
        return array(
            'wlr_referral_advocate_point' => isset($referral_points['advocate_points']) && !empty($referral_points['advocate_points']) ? $referral_points['advocate_points'] : 0,
            'wlr_referral_advocate_point_percentage' => isset($referral_points['advocate_point_percentage']) && !empty($referral_points['advocate_point_percentage']) ? $referral_points['advocate_point_percentage'] : 0,
            'wlr_referral_advocate_reward' => isset($referral_points['advocate_reward']) && !empty($referral_points['advocate_reward']) && is_array($referral_points['advocate_reward']) ? implode(',', $referral_points['advocate_reward']) : '',
            'wlr_referral_friend_point' => isset($referral_points['friend_points']) && !empty($referral_points['friend_points']) ? $referral_points['friend_points'] : 0,
            'wlr_referral_friend_point_percentage' => isset($referral_points['friend_point_percentage']) && !empty($referral_points['friend_point_percentage']) ? $referral_points['friend_point_percentage'] : 0,
            'wlr_referral_friend_reward' => isset($referral_points['friend_reward']) && !empty($referral_points['friend_reward']) && is_array($referral_points['friend_reward']) ? implode(',', $referral_points['friend_reward']) : '',
        );
    }

    protected function getActiveReferralCampaigns()
    {
        if (!empty(self::$active_referral_campaign)) return self::$active_referral_campaign;
        $campaign_table = new \Wlr\App\Models\EarnCampaign();
        return self::$active_referral_campaign = $campaign_table->getCampaignByAction('referral');
    }

    public function getDummySocialShareList()
    {
        return array(
            array(
                "action_type" => "facebook_share",
                "icon" => "wlr wlrf-facebook_share",
                "share_content" => "Hello",
                "url" => "https://www.facebook.com/sharer/sharer.php?quote=kadh&u=http%3A%2F%2Flocalhost%3A5000%3Fwlr_ref%3DREF-9D8-TK7-93O&display=page",
                "image_icon" => "",
                "name" => "Facebook"
            ),
            array(
                "action_type" => "twitter_share",
                "icon" => "wlr wlrf-twitter_share",
                "share_content" => "hey",
                "url" => "https://twitter.com/intent/tweet?text=hey",
                "image_icon" => "",
                "name" => "Twitter"
            ),
            array(
                "action_type" => "whatsapp_share",
                "icon" => "wlr wlrf-whatsapp_share",
                "share_content" => "oi",
                "url" => "https://api.whatsapp.com/send?text=oi",
                "image_icon" => "",
                "name" => "WhatsApp"
            ),
            array(
                "action_type" => "email_share",
                "icon" => "wlr wlrf-email_share",
                "share_content" => "",
                "url" => "mailto:?subject=Morning&amp;body=good%20morning",
                "image_icon" => "",
                "name" => "E-mail",
                "share_subject" => "Morning",
                "share_body" => "Good morning"
            )
        );
    }

    public function getSavedSettings($option_key)
    {
        if (empty($option_key)) return array();
        switch ($option_key) {
            case 'design':
                $saved_settings = (!empty(self::$saved_design_settings)) ? self::$saved_design_settings : (get_option('wll_launcher_design_settings'));
                break;
            case 'content':
                $saved_settings = (!empty(self::$saved_content_settings)) ? self::$saved_content_settings : (get_option('wll_launcher_content_settings'));
                break;
            case 'launcher_button':
                $saved_settings = (!empty(self::$saved_launcher_common_settings)) ? self::$saved_launcher_common_settings : (get_option('wll_launcher_icon_settings'));
                break;
        }
        return !empty($saved_settings) ? $saved_settings : array();
    }
}