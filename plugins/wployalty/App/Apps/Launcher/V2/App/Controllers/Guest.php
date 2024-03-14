<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Controllers;

use Wll\V2\App\Helpers\Settings;
use Wlr\App\Models\EarnCampaign;

defined("ABSPATH") or die();

class Guest extends Base
{
    /**
     * Getting guest content data
     * @return array
     */
    public function getGuestContentData($is_admin_side = false)
    {
        $short_code_data = array(
            'guest' => array(
                'welcome' => array(
                    'texts' => array(
                        'title' => self::$settings->opt('content.guest.welcome.texts.title', 'Join and Earn Rewards', 'content'),
                        'description' => self::$settings->opt('content.guest.welcome.texts.description', 'Get exclusive perks by becoming a member of our rewards program.', 'content'),
                        'have_account' => self::$settings->opt('content.guest.welcome.texts.have_account', 'Already have an account?', 'content'),
                        'sign_in' => self::$settings->opt('content.guest.welcome.texts.sign_in', 'Sign in', 'content'),
                        'sign_in_url' => self::$settings->opt('content.guest.welcome.texts.sign_in_url', '{wlr_signin_url}', 'content'),
                    ),
                    'button' => array(
                        'text' => self::$settings->opt('content.guest.welcome.button.text', 'Join Now!', 'content'),
                        'url' => self::$settings->opt('content.guest.welcome.button.url', '{wlr_signup_url}', 'content'),
                    ),
                ),
                'points' => array(
                    'earn' => array(
                        'title' => self::$settings->opt('content.guest.points.earn.title', 'Earn', 'content'),
                    ),
                    'redeem' => array(
                        'title' => self::$settings->opt('content.guest.points.redeem.title', 'Redeem', 'content'),
                    ),
                ),
                'referrals' => array(
                    'title' => self::$settings->opt('content.guest.referrals.title', 'Refer and earn', 'content'),
                    'description' => self::$settings->opt('content.guest.referrals.description', 'Refer your friends and earn rewards. Your friend can get a reward as well!', 'content'),
                ),
            ),
        );
        array_walk_recursive($short_code_data, function (&$value, $key) use ($is_admin_side) {
            $value = (!$is_admin_side) ? __($value, 'wp-loyalty-rules') : $value;
            $value = (!$is_admin_side) ? self::$settings->processShortCodes($value) : $value;
        });

        $setting_option = get_option('wlr_settings', '');
        $is_campaign_display = isset($setting_option['is_campaign_display']) && in_array($setting_option['is_campaign_display'], array('no', 'yes')) ? $setting_option['is_campaign_display'] : 'yes';
        $is_referral_action_available = false;
        if ($is_campaign_display === 'yes') {
            $earn_campaign_model = new EarnCampaign();
            $referral_campaign = $earn_campaign_model->getCampaignByAction('referral');
            $is_referral_action_available = !empty($referral_campaign);
        }
        $data = array(
            'guest' => array(
                'welcome' => array(
                    'icon' => array(
                        'image' => self::$settings->opt('content.guest.welcome.icon.image', '', 'content'),
                    ),
                ),
                'points' => array(
                    'earn' => array(
                        'icon' => array(
                            'image' => self::$settings->opt('content.guest.points.earn.icon.image', '', 'content'),
                        ),
                    ),
                    'redeem' => array(
                        'icon' => array(
                            'image' => self::$settings->opt('content.guest.points.redeem.icon.image', '', 'content'),
                        ),
                    ),
                ),
                'referrals' => array(
                    'is_referral_action_available' => $is_referral_action_available,
                )
            )
        );
        return apply_filters('wll_launcher_guest_content_data', array_merge_recursive($short_code_data, $data));

    }

    /**
     * Getting redeem data for guest user
     * @return void
     */
    public function redeemGuest()
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
            $user_email = self::$woocommerce->get_login_user_email();
            $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
            $is_guest_user = empty($user_email);
            $rewards = $customer_page->getRewardList($is_guest_user);
			$earn_campaign = \Wlr\App\Helpers\EarnCampaign::getInstance();
            if (!empty($rewards) && is_array($rewards)) {
                foreach ($rewards as $reward) {
                    $reward->name = isset($reward->name) && !empty($reward->name) ? __($reward->name, 'wp-loyalty-rules') : '';
                    $reward->description = isset($reward->description) && !empty($reward->description) ? __($reward->description, 'wp-loyalty-rules') : '';
                    $reward->action_text = $this->getUserRewardText($reward);
                }
            }
            $message = empty($rewards) ? sprintf(__("No %s found!", "wp-loyalty-rules"),$earn_campaign->getRewardLabel(3)) : "";
            $reward_list = apply_filters('wll_before_launcher_rewards_data', $rewards, $user_email);
            $response["data"] = array("redeem_data" => $reward_list, "message" => $message);
            wp_send_json($response);
        }
        $response["data"]["redeem_data"] = $this->getDummyRewardList();
        wp_send_json($response);
    }

    /**
     * Getting  earn points page data for guest user
     * @return void
     */
    public function earnPointsGuest()
    {
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
        $response = array();
        $response["success"] = true;
        if ($is_admin_page === false) {
            $response["data"] = $this->getCampaigns();
            wp_send_json($response);
        }
        $response["data"]["earn_points"] = $this->getDummyCampaigns();
        wp_send_json($response);
    }

}