<?php
/**
 * @author      Wployalty (Ilaiyaraja)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wll\V2\App\Controllers;

use Wll\V2\App\Helpers\Settings;
use Wlr\App\Helpers\Input;
use Wlr\App\Helpers\Template;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaign;
use Wlr\App\Models\EarnCampaignTransactions;

defined('ABSPATH') or die();

class Base
{
    /**
     * Declaring variables
     */
    public static $settings, $woocommerce, $input, $validation, $template;
    public static $current_user_details;
    public static $get_campaign_list = array();
    public static $redeem_rewards = array();
    public static $redeem_coupons = array();
    public static $user_rewards = array();
    public static $reward_opportunities = array();
    public static $social_share_list = array();

    /**
     * construct initiates the objects for required classes
     */
    public function __construct()
    {
        self::$woocommerce = empty(self::$woocommerce) ? Woocommerce::getInstance() : self::$woocommerce;
        self::$input = empty(self::$input) ? new Input() : self::$input;
        self::$template = empty(self::$template) ? new Template() : self::$template;
        self::$settings = empty(self::$settings) ? new \Wll\V2\App\Helpers\Settings() : self::$settings;
        self::$validation = empty(self::$validation) ? new \Wll\V2\App\Helpers\Validation() : self::$validation;
    }

    function isLauncherSecurityValid($nonce_name = '')
    {
        $wll_nonce = (string)self::$input->post_get('wll_nonce', '');
        if (!Woocommerce::hasAdminPrivilege() || !Woocommerce::verify_nonce($wll_nonce, $nonce_name)) return false;
        return true;
    }

    /**
     * Getting launcher default or saved settings
     * @return void
     */
    public function launcherWidgetData()
    {
        $response = array(
            'success' => false,
            'data' => array(),
        );
        if (!$this->getRenderPageNonceCheck()) {
            $response["data"]["message"] = __("Security check failed.", "wp-loyalty-rules");
            wp_send_json($response);
        }
        $is_admin_side = $this->checkIsAdminSide();
        //design
        $design_settings = $this->getDesignSettings($is_admin_side);
        //content admin side translated values fetch
        $guest_base = new Guest();
        $guest_content = $guest_base->getGuestContentData($is_admin_side);
        $member_base = new Member();
        $member_content = $member_base->getMemberContentData($is_admin_side);
        $content_settings = array('content' => array_merge($guest_content, $member_content));
        //popup button
        $popup_button_settings = $this->getLauncherButtonContentData($is_admin_side);
        $settings = array_merge($design_settings, $content_settings, $popup_button_settings);
        $settings['is_member'] = !empty(self::$woocommerce->get_login_user_email());
        $user = $this->getUserDetails();
        $settings['available_point'] = (isset($user) && isset($user->points) && !empty($user->points)) ? $user->points : 0;
        $settings['labels'] = array(
            'footer' => array(
                "powered_by" => __("Powered by", 'wp-loyalty-rules'),
                "title" => __("WPLoyalty", "wp-loyalty-rules"),
            ),
            'reward_text' => __("My Rewards", 'wp-loyalty-rules'),
            'coupon_text' => __("My Coupons", 'wp-loyalty-rules'),
            'loading_text' => __("Loading...", 'wp-loyalty-rules'),
            'loading_timer_text' => __("If loading takes a while, please refresh the screen...!", 'wp-loyalty-rules'),
        );
        $response["success"] = true;
        $response["data"] = $settings;
        wp_send_json($response);
    }

    function getRenderPageNonceCheck()
    {
        $wll_nonce = (string)self::$input->post("wll_nonce", '');
        if (Woocommerce::verify_nonce($wll_nonce, "render_page_nonce")) {
            return true;
        }
        return false;
    }

    function checkIsAdminSide()
    {
        $is_admin_page = (string)self::$input->post("is_admin_side");
        return $is_admin_page === "true";
    }

    function getDesignSettings()
    {
        $setting_option = self::$woocommerce->getOptions('wlr_settings');
        $theme_color = is_array($setting_option) && isset($setting_option["theme_color"]) && !empty($setting_option["theme_color"]) ? $setting_option["theme_color"] : "#6F38C5";
        return apply_filters('wll_launcher_design_content_data', array(
            'design' => array(
                'logo' => array(
                    'is_show' => self::$settings->opt('design.logo.is_show', 'show', 'design'),
                    'image' => self::$settings->opt('design.logo.image', '', 'design'),
                ),
                'colors' => array(
                    'theme' => array(
                        'primary' => self::$settings->opt('design.colors.theme.primary', $theme_color, 'design'),
                        'text' => self::$settings->opt('design.colors.theme.text', 'white', 'design'),
                    ),
                    'buttons' => array(
                        'background' => self::$settings->opt('design.colors.buttons.background', '#FF6B00', 'design'),
                        'text' => self::$settings->opt('design.colors.buttons.text', 'white', 'design'),
                    ),
                ),
                'branding' => array(
                    'is_show' => self::$settings->opt('design.branding.is_show', 'none', 'design'),
                )
            )
        ));
    }

    function getLauncherButtonContentData($is_admin_side = false)
    {
        $text_data = array(
            'launcher' => array(
                'appearance' => array(
                    'text' => self::$settings->opt('launcher.appearance.text', 'My Rewards', 'launcher_button'),
                    'icon' => array(
                        'selected' => self::$settings->opt('launcher.appearance.icon.selected', 'default', 'launcher_button'),
                    )
                ),
            ),
        );
        array_walk_recursive($text_data, function (&$value, $key) use ($is_admin_side) {
            /*$is_admin_side = isset($is_admin_side) && is_bool($is_admin_side) && $is_admin_side;*/
            $value = (!$is_admin_side) ? __($value, 'wp-loyalty-rules') : $value;
        });
        $data = array(
            'launcher' => array(
                'appearance' => array(
                    'selected' => self::$settings->opt('launcher.appearance.selected', 'icon_with_text', 'launcher_button'),
                    'icon' => array(
                        'image' => self::$settings->opt('launcher.appearance.icon.image', '', 'launcher_button'),
                        'icon' => self::$settings->opt('launcher.appearance.icon.icon', 'gift', 'launcher_button'),
                    ),
                ),
                'placement' => array(
                    'position' => self::$settings->opt('launcher.placement.position', 'right', 'launcher_button'),
                    'side_spacing' => self::$settings->opt('launcher.placement.side_spacing', 0, 'launcher_button'),
                    'bottom_spacing' => self::$settings->opt('launcher.placement.bottom_spacing', 0, 'launcher_button'),
                ),
                'view_option' => self::$settings->opt('launcher.view_option', 'mobile_and_desktop', 'launcher_button'),
                'font_family' => self::$settings->opt('launcher.font_family', 'inherit', 'launcher_button'),
                'show_conditions' => self::$settings->opt('launcher.show_conditions', [], 'launcher_button'),
                'condition_relationship' => self::$settings->opt('launcher.condition_relationship', "and", 'launcher_button')
            )
        );
        return apply_filters('wll_launcher_popup_button_content_data', array_merge_recursive($text_data, $data));
    }

    /**
     * Getting user details
     * @return mixed|\stdClass|null
     */
    public function getUserDetails()
    {
        if (!empty(self::$current_user_details)) return self::$current_user_details;
        $user_email = self::$woocommerce->get_login_user_email();
        if (empty($user_email)) return new \stdClass();
        $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
        return self::$current_user_details = $customer_page->getPageUserDetails($user_email, 'launcher');
    }

    /**
     * Getting earn points campaign data
     * @return array|array[]|null[]
     */
    public function getCampaigns()
    {
        if (!empty(self::$get_campaign_list)) return self::$get_campaign_list;
        $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
        $user_email = self::$woocommerce->get_login_user_email();
        $is_guest_user = empty($user_email);
        $campaign_list = $customer_page->getCampaignList($is_guest_user);
        if (empty($campaign_list) || !is_array($campaign_list)) {
            $message = __('Create a first campaign.', 'wp-loyalty-rules');
            return array("earn_points" => $campaign_list, 'message' => $message);
        }
        $message = "";
        $woocommerce_helper = new \Wlr\App\Helpers\Woocommerce();
        $is_show_campaign_list = array();
        $user = $this->getUserDetails();
        foreach ($campaign_list as &$active_campaigns) {
            $active_campaigns->name = isset($active_campaigns->name) && !empty($active_campaigns->name) ? __($active_campaigns->name, 'wp-loyalty-rules') : '';
            $active_campaigns->description = isset($active_campaigns->description) && !empty($active_campaigns->description) ? __($active_campaigns->description, 'wp-loyalty-rules') : '';
            $active_campaigns->campaign_title_discount = isset($active_campaigns->campaign_title_discount) && !empty($active_campaigns->campaign_title_discount) ? __($active_campaigns->campaign_title_discount, 'wp-loyalty-rules') : '';
            if (isset($active_campaigns->action_type) && !empty($active_campaigns->action_type)) {
                $this->getCampaignActions($active_campaigns, $user, $woocommerce_helper);
            }
            if (isset($active_campaigns->action_type) && $active_campaigns->action_type == "birthday") {
                $active_campaigns->birthday_date_format = apply_filters("wlr_my_account_birthday_date_format", array(
                    "format" => array("d", "m", "Y"),
                    "separator" => "-"
                ));
            }
            $is_show_campaign_list[] = $active_campaigns;
        }
        if (count($is_show_campaign_list) == 0) {
            $message = __('Create a first campaign.', 'wp-loyalty-rules');
        }
        $campaign_list = apply_filters('wll_before_launcher_earn_points_data', array_values($campaign_list), $user_email);
        $campaign_list = array("earn_points" => $campaign_list, 'message' => $message);
        return self::$get_campaign_list = $campaign_list;
    }

    /**
     * @param $active_campaigns
     * @param $user
     * @param $woocommerce_helper
     * @return void
     */
    function getCampaignActions(&$active_campaigns, $user, $woocommerce_helper)
    {
        if (empty($active_campaigns) || !is_object($active_campaigns)) return;
        $base_helper = new \Wlr\App\Helpers\Base();
        $point_rule = self::$woocommerce->isJson($active_campaigns->point_rule) ? json_decode($active_campaigns->point_rule) : new \stdClass();
        $user_email = isset($user) && is_object($user) && isset($user->user_email) && !empty($user->user_email) ? $user->user_email : '';
        $campaign_id = isset($active_campaigns->id) && !empty($active_campaigns->id) ? $active_campaigns->id : 0;
        $action_type = isset($active_campaigns->action_type) && !empty($active_campaigns->action_type) && is_string($active_campaigns->action_type) ? $active_campaigns->action_type : '';
        switch ($action_type) {
            case 'followup_share':
                $active_campaigns->share_url = isset($point_rule->share_url) && !empty($point_rule->share_url) ? $point_rule->share_url : '';
                $active_campaigns->button_text = __('Follow', 'wp-loyalty-rules');
                $active_campaigns->is_achieved = !empty($user_email) && $campaign_id > 0 && $this->isCampaignAchieved($user_email, $campaign_id);
                $active_campaigns->achieved_text = $active_campaigns->is_achieved ? __("Earned", "wp-loyalty-rules") : "";
                break;
            case 'birthday':
                $active_campaigns->is_allow_edit = self::$woocommerce->canShowBirthdateField();
                $active_campaigns->birth_date = isset($user->birthday_date) && !empty($user->birthday_date) && $user->birthday_date != '0000-00-00' ? self::$woocommerce->convertDateFormat($user->birthday_date, "Y-m-d") : (isset($user->birth_date) && !empty($user->birth_date) ? $woocommerce_helper->beforeDisplayDate($user->birth_date, "Y-m-d") : '');
                $active_campaigns->display_birth_date = isset($user->birthday_date) && !empty($user->birthday_date) && $user->birthday_date != '0000-00-00' ? self::$woocommerce->convertDateFormat($user->birthday_date) : (isset($user->birth_date) && !empty($user->birth_date) ? $woocommerce_helper->beforeDisplayDate($user->birth_date) : '');
                $active_campaigns->user_can_edit_birthdate = (isset($user) && isset($user->id) && $user->id > 0);
                $active_campaigns->show_edit_birthday = apply_filters("wlr_allow_my_account_edit_birth_date", true, $active_campaigns->user_can_edit_birthdate, isset($user) && !empty($user) ? $user : new \stdClass());
                $active_campaigns->edit_text = !empty($active_campaigns->birth_date) ? __('Edit', 'wp-loyalty-rules') : __('Set', 'wp-loyalty-rules');
                $active_campaigns->update_text = __('Update', 'wp-loyalty-rules');
                if (isset($point_rule->birthday_earn_type) && !empty($point_rule->birthday_earn_type) && $point_rule->birthday_earn_type == "update_birth_date") {
                    $active_campaigns->is_achieved = !empty($user_email) && $campaign_id > 0 && $this->isCampaignAchieved($user_email, $campaign_id);
                    $active_campaigns->achieved_text = $active_campaigns->is_achieved ? __("Earned", "wp-loyalty-rules") : "";
                }
                break;
            case 'referral':
                $referral_url_check = !empty($user) && is_object($user) && isset($user->refer_code) && !empty($user->refer_code);
                if ($referral_url_check) $active_campaigns->referral_url = $base_helper->getReferralUrl($user->refer_code);
                break;
            case 'facebook_share':
            case 'twitter_share':
            case 'whatsapp_share':
            case 'email_share':
                $referral_url_check = !empty($user) && is_object($user) && isset($user->refer_code) && !empty($user->refer_code);
                if ($referral_url_check) $referral_url = $base_helper->getReferralUrl($user->refer_code);
                $social_actions = (isset($referral_url) && !empty($referral_url) && isset($user->user_email) && !empty($user->user_email)) ? $this->getSocialIconList($user->user_email, $referral_url) : array();
                foreach ($social_actions as $social_action) {
                    if (isset($social_action['action_type']) && !empty($social_action['action_type']) && $social_action['action_type'] == $active_campaigns->action_type) {
                        $active_campaigns->action_url = isset($social_action['url']) && !empty($social_action['url']) ? $social_action['url'] : '';
                        $active_campaigns->button_text = __('Share', 'wp-loyalty-rules');
                    }
                }
                $active_campaigns->is_achieved = !empty($user_email) && $campaign_id > 0 && $this->isCampaignAchieved($user_email, $campaign_id);
                $active_campaigns->achieved_text = $active_campaigns->is_achieved ? __("Earned", "wp-loyalty-rules") : "";
                break;
            case 'signup':
                $is_member_user = (isset($user) && isset($user->id) && $user->id > 0);
                if (!$is_member_user) {
                    $active_campaigns->action_url = get_permalink(get_option('woocommerce_myaccount_page_id'));
                    $active_campaigns->button_text = __('Sign Up', 'wp-loyalty-rules');
                }
                $active_campaigns->is_achieved = !empty($user_email) && $campaign_id > 0 && $this->isCampaignAchieved($user_email, $campaign_id);
                $active_campaigns->achieved_text = $active_campaigns->is_achieved ? __("Earned", "wp-loyalty-rules") : "";
                break;
            default;
        }
    }

    function isCampaignAchieved($user_email, $campaign_id)
    {
        if (empty($user_email) || !is_string($user_email) || empty($campaign_id) || $campaign_id <= 0) return false;
        $campaign_transaction_model = new EarnCampaignTransactions();
        global $wpdb;
        $where = $wpdb->prepare('user_email = %s AND campaign_id = %s', array($user_email, $campaign_id));
        $result = $campaign_transaction_model->getWhere($where, 'COUNT(*) as count', true);
        return !empty($result) && isset($result->count) && ($result->count > 0);
    }

    /**
     * Getting social icon lists
     * @param $user_email
     * @param $url
     * @return array
     */
    public function getSocialIconList($user_email, $url)
    {
        if (!empty(self::$social_share_list)) return self::$social_share_list;
        if (empty($user_email) || empty($url)) return array();
        $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
        $social_share_lists = $customer_page->getSocialShareList($user_email, $url);
        foreach ($social_share_lists as $key => &$social_share_list) {
            self::$social_share_list[] = array_merge($social_share_list, array('action_type' => $key));
        }
        return self::$social_share_list;
    }

    function getDummyCampaigns()
    {
        return array(
            array(
                'id' => 1,
                'icon' => 'point_for_purchase',
                'title' => __('Point for purchase', 'wp-loyalty-rules'),
                'description' => __('+10 Points for every $1 spent', 'wp-loyalty-rules')
            ),
            array(
                'id' => 2,
                'icon' => 'birthday',
                'title' => __('Celebrate a birthday', 'wp-loyalty-rules'),
                'description' => __('+30 points', 'wp-loyalty-rules')
            ),
            array(
                'id' => 3,
                'icon' => 'twitter_share',
                'title' => __('Twitter Share', 'wp-loyalty-rules'),
                'description' => __('+70 points', 'wp-loyalty-rules')
            ),
            array(
                'id' => 4,
                'icon' => 'facebook_share',
                'title' => __('Follow on Facebook', 'wp-loyalty-rules'),
                'description' => __('+50 points', 'wp-loyalty-rules')
            ),
            array(
                'id' => 5,
                'icon' => 'product_review',
                'title' => __('Review a Product', 'wp-loyalty-rules'),
                'description' => __('+800 points', 'wp-loyalty-rules')
            ),
        );
    }

    function getDummyRewardList()
    {
        return array(
            array(
                'id' => 1,
                'icon' => 'points_conversion',
                'title' => __('Point conversion', 'wp-loyalty-rules'),
                'description' => __('Covert point into coupons', 'wp-loyalty-rules'),
                'action_text' => __('10 points = $15.00 Off', 'wp-loyalty-rules'),
            ),
            array(
                'id' => 2,
                'icon' => 'percent',
                'title' => __('Percentage discount', 'wp-loyalty-rules'),
                'description' => __('Redeem points and get percentage discount', 'wp-loyalty-rules'),
                'action_text' => __('100 points = 10% Off', 'wp-loyalty-rules'),
            ),
            array(
                'id' => 3,
                'icon' => 'free_shipping',
                'title' => __('Free shipping', 'wp-loyalty-rules'),
                'description' => __('Redeem points and get free shipping', 'wp-loyalty-rules'),
                'action_text' => __('free shipping', 'wp-loyalty-rules'),
            ),
            array(
                'id' => 4,
                'icon' => 'fixed_cart',
                'title' => __('Fixed discount', 'wp-loyalty-rules'),
                'description' => __('Redeem points and get fixed discount', 'wp-loyalty-rules'),
                'action_text' => __('100 points = $10.00 Off', 'wp-loyalty-rules'),
            ),
        );
    }

    function getDummyRewardOpportunities()
    {
        return array(
            array(
                'id' => 1,
                'icon' => 'fixed_cart',
                'title' => __('Fixed coupon discount', 'wp-loyalty-rules'),
                'description' => __('Coupon reward', 'wp-loyalty-rules'),
                'action_text' => '',
            ),
            array(
                'id' => 2,
                'icon' => 'percent',
                'title' => __('Percentage discount', 'wp-loyalty-rules'),
                'description' => __('Percentage discount', 'wp-loyalty-rules'),
                'action_text' => '',
            ),
            array(
                'id' => 3,
                'icon' => 'free_product',
                'title' => __('Free product', 'wp-loyalty-rules'),
                'description' => __('Free product', 'wp-loyalty-rules'),
                'action_text' => '',
            ),
            array(
                'id' => 4,
                'icon' => 'points_conversion',
                'title' => __('Points conversion', 'wp-loyalty-rules'),
                'description' => __('Points conversion', 'wp-loyalty-rules'),
                'action_text' => '',
            ),
        );
    }

    function getDummyCouponData()
    {
        return array(
            array(
                'id' => 1,
                'icon' => 'fixed_cart',
                'title' => __('Fixed discount', 'wp-loyalty-rules'),
                'description' => __('Fixed discount description', 'wp-loyalty-rules'),
                'expired_text' => __('Expires on 2024-04-12', 'wp-loyalty-rules'),
                'coupon_code' => "wlr-cd6-jrt-eaz",
                'action_text' => __('$10.00', 'wp-loyalty-rules')
            ),
            array(
                'id' => 2,
                'icon' => 'percent',
                'title' => __('Percentage discount', 'wp-loyalty-rules'),
                'description' => __('Percentage discount description', 'wp-loyalty-rules'),
                'expired_text' => __('Expires on 2023-05-12', 'wp-loyalty-rules'),
                'coupon_code' => "wlr-wrs-m5a-y5q",
                'action_text' => __('10%', 'wp-loyalty-rules')
            ),
            array(
                'id' => 3,
                'icon' => 'free_shipping',
                'title' => __('Free Shipping', 'wp-loyalty-rules'),
                'description' => __('Free shipping description', 'wp-loyalty-rules'),
                'expired_text' => "",
                'coupon_code' => "wlr-zhn-4z6-efz",
                'action_text' => __('Free shipping', 'wp-loyalty-rules')
            ),

        );
    }

    /**
     * Getting user rewards like coupons and reward
     * @return array
     */
    function getUserRewards()
    {
        if (!empty(self::$user_rewards)) return self::$user_rewards;
        $user_email = self::$woocommerce->get_login_user_email();
        $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
        $user_rewards = $customer_page->getPageUserRewards($user_email,array('is_launcher'=>true));
        if (empty($user_rewards) || !is_array($user_rewards)) return array();
        foreach ($user_rewards as $user_reward) {
            $user_reward->name = isset($user_reward->name) && !empty($user_reward->name) ? __($user_reward->name, 'wp-loyalty-rules') : '';
            $user_reward->description = isset($user_reward->description) && !empty($user_reward->description) ? __($user_reward->description, 'wp-loyalty-rules') : '';
            $user_reward->button_text = __('Redeem', 'wp-loyalty-rules');
            $user_reward->action_text = $this->getUserRewardText($user_reward);
            if (isset($user_reward->discount_code) && !empty($user_reward->discount_code)) {
                $user_reward->button_text = "";
                $user_reward->action_text = $this->getUserCouponText($user_reward);
            }
            $user_reward->is_point_convertion_reward = false;
            $user_reward->is_redirect_to_coupon = true;
            if (isset($user_reward->discount_type) && $user_reward->discount_type == 'points_conversion' && isset($user_reward->reward_table) && $user_reward->reward_table != 'user_reward') {
                $user_reward->discount_value = self::$woocommerce->getCustomPrice($user_reward->discount_value, false);
                $user_reward->is_point_convertion_reward = true;
                $user_reward->is_redirect_to_coupon = false;
            }
            $user_reward->expiry_date_text = "";
            if (isset($user_reward->expiry_date) && !empty($user_reward->expiry_date) && isset($user_reward->discount_code) && !empty($user_reward->discount_code)) {
                $user_reward->expiry_date_text = sprintf(__("Expires on %s", "wp-loyalty-rules"), $user_reward->expiry_date);
            }
        }
        $user_rewards = apply_filters('wll_before_launcher_user_rewards_data', $user_rewards, $user_email);
        return self::$user_rewards = $user_rewards;
    }

    function getUserRewardText($user_reward)
    {
        if (empty($user_reward) || !is_object($user_reward)) return "";
        $base_helper = new \Wlr\App\Helpers\Base();
        $text = "";
        $discount_type = isset($user_reward->discount_type) && !empty($user_reward->discount_type) ? $user_reward->discount_type : '';
        switch ($discount_type) {
            case 'fixed_cart':
            case 'points_conversion':
                $discount_value = self::$woocommerce->getCustomPrice($user_reward->discount_value);
                $text = ($user_reward->reward_type == "redeem_coupon") ? sprintf(__('%s Off', 'wp-loyalty-rules'), $discount_value)
                    : sprintf(__('%d %s = %s Off', 'wp-loyalty-rules'), $user_reward->require_point, $base_helper->getPointLabel($user_reward->require_point), $discount_value);
                break;
            case 'percent':
                $text = ($user_reward->reward_type == "redeem_coupon") ? sprintf(__('%d%s Off', 'wp-loyalty-rules'), $user_reward->discount_value, '%')
                    : sprintf(__('%d %s = %d%s Off', 'wp-loyalty-rules'), $user_reward->require_point, $base_helper->getPointLabel($user_reward->require_point), $user_reward->discount_value, '%');
                break;
            case 'free_product':
                $text = ($user_reward->reward_type == "redeem_coupon") ? __('Free product', 'wp-loyalty-rules') : __($user_reward->require_point . ' ' . $base_helper->getPointLabel($user_reward->require_point), 'wp-loyalty-rules');
                break;
            case 'free_shipping':
                $text = ($user_reward->reward_type == "redeem_coupon") ? __('Free shipping', 'wp-loyalty-rules') : __($user_reward->require_point . ' ' . $base_helper->getPointLabel($user_reward->require_point), 'wp-loyalty-rules');
                break;
        }
        return $text;
    }

    function getUserCouponText($user_reward)
    {
        if (empty($user_reward) || !is_object($user_reward)) return "";
        $text = "";
        $discount_value = isset($user_reward->discount_value) && !empty($user_reward->discount_value) && ($user_reward->discount_value != 0) ? ($user_reward->discount_value) : '';
        $discount_type = isset($user_reward->discount_type) && !empty($user_reward->discount_type) ? $user_reward->discount_type : '';
        switch ($discount_type) {
            case 'fixed_cart':
            case 'points_conversion':
                $text = self::$woocommerce->convertPrice($discount_value, true, $user_reward->reward_currency);
                break;
            case 'percent':
                $text = round($discount_value) . '%';
                break;
            case 'free_product':
                $text = __('Free product', 'wp-loyalty-rules');
                break;
            case 'free_shipping':
                $text = __('Free shipping', 'wp-loyalty-rules');
                break;
        }
        return $text;
    }

    function getRewardOpportunities()
    {
        if (!empty(self::$reward_opportunities)) return self::$reward_opportunities;
        $user_email = self::$woocommerce->get_login_user_email();
        $is_guest = empty($user_email);
        $customer_page = new \Wlr\App\Controllers\Site\CustomerPage();
        $rewards = $customer_page->getRewardList($is_guest);
		$earn_campaign = \Wlr\App\Helpers\EarnCampaign::getInstance();
        if (empty($rewards) || !is_array($rewards)) return array('reward_opportunity' => array(), 'message' => sprintf(__('No %s found!', 'wp-loyalty-rules'),$earn_campaign->getRewardLabel(3)));
        foreach ($rewards as $reward) {
            $reward->name = isset($reward->name) && !empty($reward->name) ? __($reward->name, 'wp-loyalty-rules') : '';
            $reward->description = isset($reward->description) && !empty($reward->description) ? __($reward->description, 'wp-loyalty-rules') : '';
        }
        $message = "";
        if (count($rewards) == 0) {
            $message = sprintf(__('No %s found!', 'wp-loyalty-rules'),$earn_campaign->getRewardLabel(3));
        }
        return self::$reward_opportunities = apply_filters('wll_before_launcher_reward_opportunities', array('reward_opportunity' => $rewards, 'message' => $message), $user_email, $is_guest);
    }

    /**
     * Add launcher dynamic strings
     *
     * @param $new_strings
     * @param $text_domain
     * @return array|mixed
     */
    public function launcherDynamicStrings($new_strings, $text_domain)
    {
        if (!is_array($new_strings) || !is_string($text_domain) || $text_domain != 'wp-loyalty-rules') {
            return $new_strings;
        }
        $this->getGuestDynamicStrings($new_strings);
        $this->getMemberDynamicStrings($new_strings);
        $this->getLauncherDynamicStrings($new_strings);
        return $new_strings;
    }

    /**
     * Add guest dynamic string
     *
     * @param $new_strings
     * @return void
     */
    function getGuestDynamicStrings(&$new_strings)
    {
        $content = self::$settings->getSavedSettings('content');//saved data
        $guest = new Guest();
        $d_guest = array("content" => $guest->getGuestContentData(true));
        $guest_input_data = array(
            'content.guest.welcome.texts.title', 'content.guest.welcome.texts.description', 'content.guest.welcome.texts.have_account',
            'content.guest.welcome.texts.sign_in', 'content.guest.welcome.button.text', 'content.guest.points.earn.title',
            'content.guest.points.redeem.title', 'content.guest.referrals.title', 'content.guest.referrals.description',
        );
        foreach ($guest_input_data as $g_key) {
            $string = $this->getString($g_key, $d_guest, $content);
            if (!empty($string)) $new_strings[] = $string;
        }
    }

    /**
     * Get key value of launcher data
     *
     * @param $key
     * @param $default
     * @param $saved
     * @return mixed|string
     */
    function getString($key, $default, $saved)
    {
        $base_helper = new \Wll\V2\App\Helpers\Base();
        if (strpos($key, ".") !== false) {
            $identifiers = explode(".", $key);//splitting keys for sub array values
            $value = $base_helper->getOptValue($saved, $identifiers);
            if (empty($value)) $value = $base_helper->getOptValue($default, $identifiers);
        }
        return (!empty($value)) ? $value : '';
    }

    /**
     * Add member dynamic string
     *
     * @param $new_strings
     * @return void
     */
    function getMemberDynamicStrings(&$new_strings)
    {
        $content = self::$settings->getSavedSettings('content');//saved data
        $member = new Member();
        $d_member = array('content' => $member->getMemberContentData(true));
        $member_input_data = array(
            'content.member.banner.texts.welcome', 'content.member.banner.texts.points',
            'content.member.banner.texts.points_label', 'content.member.banner.texts.points_content',
            'content.member.banner.texts.points_text', 'content.member.points.earn.title', 'content.member.points.redeem.title',
            'content.member.referrals.title', 'content.member.referrals.description',
        );
        foreach ($member_input_data as $m_key) {
            $string = $this->getString($m_key, $d_member, $content);
            if (!empty($string)) $new_strings[] = $string;
        }
    }

    /**
     * Add launcher icon dynamic strings
     *
     * @param $new_strings
     * @return void
     */
    function getLauncherDynamicStrings(&$new_strings)
    {
        $launcher = self::$settings->getSavedSettings('launcher_button');//saved data
        $d_launcher = $this->getLauncherButtonContentData(true);
        $launcher_input_data = array(
            'launcher.appearance.text',
        );
        foreach ($launcher_input_data as $l_key) {
            $string = $this->getString($l_key, $d_launcher, $launcher);
            if (!empty($string)) $new_strings[] = $string;
        }
    }

}