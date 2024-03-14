<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Controllers;

use Wll\V2\App\Helpers\Settings;
use Wlr\App\Models\EarnCampaign;
use Wlr\App\Models\Levels;

defined("ABSPATH") or die();

class Member extends Base
{
    /**
     * Getting member content data
     * @return array
     */
    public function getMemberContentData($is_admin_side = false)
    {
        $setting_option = (array)get_option('wlr_settings', array());
        $is_campaign_display = isset($setting_option['is_campaign_display']) && in_array($setting_option['is_campaign_display'], array('no', 'yes')) ? $setting_option['is_campaign_display'] : 'yes';
        $is_referral_action_available = false;
        if ($is_campaign_display === 'yes') {
            $earn_campaign_model = new EarnCampaign();
            $referral_campaign = $earn_campaign_model->getCampaignByAction('referral');
            $is_referral_action_available = !empty($referral_campaign);
        }
        $user = $this->getUserDetails();
        $level_modal = new Levels();
        $is_levels_available = $level_modal->checkLevelsAvailable();
        $level_data = (\Wlr\App\Helpers\EarnCampaign::getInstance()->isPro()) ? $this->userLevelData($user) : new \stdClass();
        $base_helper = new \Wlr\App\Helpers\Base();
        $referral_url = ($is_admin_side) ? $base_helper->getReferralUrl('dummy') : "";
        if (!$is_admin_side && $is_referral_action_available === true && !empty($user) && is_object($user) && isset($user->refer_code) && !empty($user->refer_code)) {
            $referral_url = $base_helper->getReferralUrl($user->refer_code);
        }
        $is_referral_action_available = $is_referral_action_available === true && !empty($referral_url) && !empty($user) && is_object($user) && isset($user->refer_code) && !empty($user->refer_code);
        $earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
        $short_code_data = array(
            'member' => array(
                'banner' => array(
                    'texts' => array(
                        'welcome' => self::$settings->opt('content.member.banner.texts.welcome', sprintf('Hello %s', '{wlr_user_name}'), 'content'),
                        'points' => self::$settings->opt('content.member.banner.texts.points', '{wlr_user_points}', 'content'),
                        'points_label' => self::$settings->opt('content.member.banner.texts.points_label', '{wlr_point_label}', 'content'),
                        'points_content' => self::$settings->opt('content.member.banner.texts.points_content', 'Your outstanding balance', 'content'),
                        'points_text' => self::$settings->opt('content.member.banner.texts.points_text', $earn_campaign_helper->getPointLabel(3, !$is_admin_side), 'content'),
                    ),
                ),
                'points' => array(
                    'earn' => array(
                        'title' => self::$settings->opt('content.member.points.earn.title', 'Earn', 'content'),
                    ),
                    'redeem' => array(
                        'title' => self::$settings->opt('content.member.points.redeem.title', 'Redeem', 'content'),
                    ),
                ),
                'referrals' => array(
                    'title' => self::$settings->opt('content.member.referrals.title', 'Refer and earn', 'content'),
                    'description' => self::$settings->opt('content.member.referrals.description', 'Refer your friends and earn rewards. Your friend can get a reward as well!', 'content'),
                ),
            ),
        );
        array_walk_recursive($short_code_data, function (&$value, $key) use ($is_admin_side) {
            $value = (!$is_admin_side) ? __($value, 'wp-loyalty-rules') : $value;
            $value = (!$is_admin_side) ? self::$settings->processShortCodes($value) : $value;
        });
        $data = array(
            'member' => array(
                'banner' => array(
                    'levels' => array(
                        'is_levels_available' => $is_levels_available,
                        'is_show' => self::$settings->opt('content.member.banner.levels.is_show', 'show', 'content'),
                        'level_data' => $level_data,
                    ),
                    'points' => array(
                        'is_show' => self::$settings->opt('content.member.banner.points.is_show', 'show', 'content'),
                    ),
                ),
                'points' => array(
                    'earn' => array(
                        'icon' => array(
                            'image' => self::$settings->opt('content.member.points.earn.icon.image', '', 'content'),
                        ),
                    ),
                    'redeem' => array(
                        'icon' => array(
                            'image' => self::$settings->opt('content.member.points.redeem.icon.image', '', 'content'),
                        ),
                    ),
                ),
                'referrals' => array(
                    'is_referral_action_available' => $is_referral_action_available,
                    'referral_url' => $referral_url,
                ),
            )
        );
        if (!$is_admin_side) {
            $social_share_list = !empty($referral_url) && !empty($user) && is_object($user) && (isset($user->user_email) && !empty($user->user_email)) ? $this->getSocialIconList($user->user_email, $referral_url) : self::$settings->getDummySocialShareList();
            $social_share_data = array(
                'member' => array(
                    'referrals' => array(
                        'social_share_list' => $social_share_list,
                    ),
                )
            );
            $data = array_merge_recursive($data, $social_share_data);
        }

        return apply_filters('wll_launcher_member_content_data', array_merge_recursive($short_code_data, $data));

    }

    /**
     * Getting user levels data
     * @param $user
     * @return array
     */
    function userLevelData($user)
    {
        $is_user_available = (isset($user) && is_object($user) && isset($user->id) && $user->id > 0);
        $level_check = $is_user_available && isset($user->level_data) && is_object($user->level_data) && isset($user->level_data->current_level_name) && !empty($user->level_data->current_level_name);
        $level_data = array(
            'user_has_level' => (isset($user) && isset($user->level_id) && $user->level_id > 0),
        );
        if ($is_user_available && isset($user->level_id) && $user->level_id > 0 && $level_check) {
            $level_data['current_level_image'] = isset($user->level_data->current_level_image) && !empty($user->level_data->current_level_image) ? $user->level_data->current_level_image : '';
            $level_data['current_level_name'] = !empty($user->level_data) && !empty($user->level_data->current_level_name) ? __($user->level_data->current_level_name, 'wp-loyalty-rules') : '';
            if (isset($user->level_data->current_level_start) && isset($user->level_data->next_level_start) && $user->level_data->next_level_start > 0) {
                $earn_campaign_helper = \Wlr\App\Helpers\EarnCampaign::getInstance();
                $level_data['level_range'] = round((($user->earn_total_point - $user->level_data->current_level_start) / ($user->level_data->next_level_start - $user->level_data->current_level_start)) * 100);
                $needed_point = $user->level_data->next_level_start - $user->earn_total_point;
                $level_data['progress_content'] = sprintf(__('%d %s more needed to unlock next level', 'wp-loyalty-rules'), (int)$needed_point, $earn_campaign_helper->getPointLabel($needed_point));
                $level_data['is_reached_final_level'] = false;
            } else {
                $level_data['is_reached_final_level'] = true;
                $level_data['progress_content'] = __('Congratulations! You have reached the final level', 'wp-loyalty-rules');
            }
        }
        return $level_data;
    }

    /**
     * Getting earn points page data for member
     * @return void
     */
    public function earnPointsMember()
    {
        $response = array();
        if (!$this->getRenderPageNonceCheck()) {
            $response = array(
                "success" => false,
                "data" => array(
                    "message" => __("Security check failed.", "wp-loyalty-rules")
                ),
            );
            wp_send_json($response);
        }
        $is_admin_page = $this->checkIsAdminSide();
        $response["success"] = true;
        if ($is_admin_page === false) {
            $response["data"] = $this->getCampaigns();
            wp_send_json($response);
        }
        $response["data"]["earn_points"] = $this->getDummyCampaigns();
        wp_send_json($response);

    }

    /**
     * Getting redeem reward page data for member
     * @return void
     */
    public function redeemRewardMember()
    {
        $response = array();
        if (!$this->getRenderPageNonceCheck()) {
            $response = array(
                "success" => false,
                "data" => array(
                    "message" => __("Security check failed.", "wp-loyalty-rules")
                ),
            );
            wp_send_json($response);
        }
        $is_admin_page = $this->checkIsAdminSide();
        $response["success"] = true;
        if ($is_admin_page === false) {
            $response["data"] = $this->getRedeemRewards();
            wp_send_json($response);
        }
        $response["data"]["redeem_data"] = $this->getDummyRewardList();
        wp_send_json($response);
    }

    /**
     * Getting redeem rewards
     * @return array
     */
    function getRedeemRewards()
    {
        if (!empty(self::$redeem_rewards)) return self::$redeem_rewards;
        $user_rewards = $this->getUserRewards();
	    $earn_campaign = \Wlr\App\Helpers\EarnCampaign::getInstance();
        if (empty($user_rewards) || !is_array($user_rewards)) return array('redeem_data' => array(), 'message' => sprintf(__('No %s found!', 'wp-loyalty-rules'),$earn_campaign->getRewardLabel(3)));
        $redeem_rewards = array();
        $message = "";
        foreach ($user_rewards as $user_reward) {
            if (empty($user_reward->discount_code)) {
                $user_reward->is_show_reward = 1;
                $redeem_rewards[] = $user_reward;
            }
        }
        if (count($redeem_rewards) == 0) {
            $message = sprintf(__('No %s found!', 'wp-loyalty-rules'),$earn_campaign->getRewardLabel(3));
        }
        return self::$redeem_rewards = array('redeem_data' => $redeem_rewards, 'message' => $message);
    }

    /**
     * Getting redeem coupon page data for member
     * @return void
     */
    public function redeemCouponMember()
    {
        $response = array();
        if (!$this->getRenderPageNonceCheck()) {
            $response = array(
                "success" => false,
                "data" => array(
                    "message" => __("Security check failed.", "wp-loyalty-rules")
                ),
            );
            wp_send_json($response);
        }
        $is_admin_page = $this->checkIsAdminSide();
        $response["success"] = true;
        if ($is_admin_page === false) {
            $response["data"] = $this->getRedeemCoupons();
            wp_send_json($response);
        }
        $response["data"]["redeem_coupons"] = $this->getDummyCouponData();
        wp_send_json($response);
    }

    public function rewardOpportunities()
    {
        $response = array();
        if (!$this->getRenderPageNonceCheck()) {
            $response = array(
                "success" => false,
                "data" => array(
                    "message" => __("Security check failed.", "wp-loyalty-rules")
                ),
            );
            wp_send_json($response);
        }
        $is_admin_page = $this->checkIsAdminSide();
        $response["success"] = true;
        if ($is_admin_page === false) {
            $response["data"] = $this->getRewardOpportunities();
            wp_send_json($response);
        }
        $response["data"]["reward_opportunity"] = $this->getDummyRewardOpportunities();
        wp_send_json($response);
    }

    /**
     * Getting coupon reward data
     * @return array
     */
    function getRedeemCoupons()
    {
        if (!empty(self::$redeem_coupons)) return self::$redeem_coupons;
        $user_rewards = $this->getUserRewards();
        $coupon_rewards = array();
        $message = "";
        if (!empty($user_rewards) && is_array($user_rewards)) {
            foreach ($user_rewards as $user_reward) {
                if (isset($user_reward->discount_code) && !empty($user_reward->discount_code)) {
                    $coupon_rewards[] = $user_reward;
                }
            }
            if (count($coupon_rewards) == 0) {
                $message = __('No coupons found!', 'wp-loyalty-rules');
            }
        } else {
            $message = __('No coupons found!', 'wp-loyalty-rules');
        }
        $coupons_data = array('redeem_coupons' => $coupon_rewards, 'message' => $message);
        return self::$redeem_coupons = $coupons_data;
    }

}