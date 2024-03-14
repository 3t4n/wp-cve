<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaignTransactions;
use Wlr\App\Models\Levels;
use Wlr\App\Models\Logs;
use Wlr\App\Models\Rewards;
use Wlr\App\Models\Users;

defined('ABSPATH') or die;

class MyAccount extends Base
{
    function includes()
    {
        if (self::$woocommerce->isBannedUser()) {
            return;
        }
        add_action('woocommerce_account_menu_items', array($this, 'addMenuItems'));
        $options = self::$woocommerce->getOptions('wlr_settings');
        $my_account_icon_enable = (isset($options['my_account_icon_enable']) && !empty($options['my_account_icon_enable']) ? $options['my_account_icon_enable'] : 'no');
        if ($my_account_icon_enable == 'yes') {
            add_filter('woocommerce_account_menu_item_classes', array($this, 'addMyAccountPointClass'), 10, 2);
        }
        add_action('woocommerce_account_loyalty_reward_endpoint', array($this, 'myAccountRewardPage'));
    }

    public function addMenuItems($menu_items)
    {
        $logout = $menu_items['customer-logout'];
        unset($menu_items['customer-logout']);
        $base_helper = new \Wlr\App\Helpers\Base();
        $menu_items['loyalty_reward'] = sprintf(__('%s & %s', 'wp-loyalty-rules'), ucfirst($base_helper->getPointLabel(3)), ucfirst($base_helper->getRewardLabel(3)));
        $menu_items['customer-logout'] = $logout;
        return apply_filters('wlr_myaccount_loyalty_menu_label', $menu_items);
    }

    function addMyAccountPointClass($classes, $endpoint)
    {
        if ('loyalty_reward' == $endpoint) {
            $classes[] = 'wlr';
            $classes[] = 'wlr-trophy';
        }
        return $classes;
    }

    function myAccountRewardPage($current_page)
    {
        echo $this->rewardPage('myaccount');
    }

    function rewardPage($page_type = '')
    {
        if (empty($page_type)) {
            return '';
        }
        $template_name = 'cart_page_rewards.php';
        if ($page_type != 'cart') {
            $template_name = 'my_account_reward.php';
        }
        if (file_exists(TEMPLATEPATH . '/' . $template_name)) {
            $main_page_params = $this->rewardPageData($page_type);
        } else {
            $template_name = "cart_page.php";
            if ($page_type != 'cart') {
                $template_name = "customer_page.php";
            }
			if (self::$woocommerce->getOptions('wlr_new_rewards_section_enabled') == 'yes') {
		        $template_name = 'cart_reward_page.php';
		        if ($page_type != 'cart') {
			        $template_name = 'customer_reward_page.php';
		        }
	        }
	        $customer_page = new CustomerPage();
	        $main_page_params = $customer_page->rewardPageData($page_type);
        }
        $my_account_content = wc_get_template_html(
            $template_name,
            $main_page_params,
            '',
            WLR_PLUGIN_PATH . 'App/Views/Site/'
        );
        return apply_filters('wlr_my_account_point_and_reward_page', $my_account_content, $main_page_params);
    }

    function rewardPageData($page_type = 'myaccount')
    {
        $user_email = self::$woocommerce->get_login_user_email();
        $user_email = sanitize_email($user_email);
        $earn_campaign = EarnCampaign::getInstance();
        $user_point_table = new Users();
        $main_page_params = array();
        $main_page_params['user'] = $earn_campaign->getPointUserByEmail($user_email);//$user_point_table->getQueryData(array('user_email' => array('operator' => '=', 'value' => $user_email)), '*', array(), false, true);
        if (!empty($main_page_params['user']) && is_object($main_page_params['user']) && isset($main_page_params['user']->id) && $main_page_params['user']->id > 0 && isset($main_page_params['user']->level_id)) {
            $user_point_table->insertOrUpdate(array('level_id' => $main_page_params['user']->level_id), $main_page_params['user']->id);
            $main_page_params['user'] = $user_point_table->getByKey($main_page_params['user']->id);
        }
        $site_custom_settings = self::$woocommerce->getOptions('wlr_settings');
        $main_page_params['theme_color'] = isset($site_custom_settings['theme_color']) && !empty($site_custom_settings['theme_color']) ? $site_custom_settings['theme_color'] : "";
        $main_page_params['heading_color'] = isset($site_custom_settings['heading_color']) && !empty($site_custom_settings['heading_color']) ? $site_custom_settings['heading_color'] : "";
        if (!empty($user_email)) {
            $main_page_params['level_data'] = new \stdClass();
            if (isset($main_page_params['user']) && isset($main_page_params['user']->level_id) && $main_page_params['user']->level_id > 0) {
                $current_level = $earn_campaign->getLevel($main_page_params['user']->level_id);
                $main_page_params['level_data']->current_level_name = isset($current_level->name) && !empty($current_level->name) ? $current_level->name : '';
                $main_page_params['level_data']->current_level_image = isset($current_level->badge) && !empty($current_level->badge) ? $current_level->badge : WLR_PLUGIN_URL . 'Assets/Site/image/default-level.png';
                $main_page_params['level_data']->current_level_start = isset($current_level->from_points) && !empty($current_level->from_points) ? $current_level->from_points : 0;
                if (isset($current_level->to_points) && $current_level->to_points > 0) {
                    $next_level_data = $earn_campaign->getNextLevel($current_level->to_points);
                    if (!empty($next_level_data) && isset($next_level_data->from_points) && $next_level_data->from_points > 0) {
                        $main_page_params['level_data']->next_level_name = isset($next_level_data->name) && !empty($next_level_data->name) ? $next_level_data->name : 0;
                        $main_page_params['level_data']->next_level_start = !empty($next_level_data->from_points) ? $next_level_data->from_points : 0;
                    }
                }
            }
            $main_page_params['level_data']->redeem_point_icon = isset($site_custom_settings['redeem_point_icon']) && !empty($site_custom_settings['redeem_point_icon']) ? $site_custom_settings['redeem_point_icon'] : WLR_PLUGIN_URL . 'Assets/Site/svg/redeem_point.svg';
            $main_page_params['level_data']->available_point_icon = isset($site_custom_settings['available_point_icon']) && !empty($site_custom_settings['available_point_icon']) ? $site_custom_settings['available_point_icon'] : WLR_PLUGIN_URL . 'Assets/Site/svg/available_point.svg';

            $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
            $extra = array(
                'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'allowed_condition' => array('user_role', 'customer', 'user_point', 'currency', 'language')
            );
            $user_reward = $reward_helper->getUserRewards($user_email, $extra);
            $point_rewards = $reward_helper->getPointRewards($user_email, $extra);
            $user_reward_datas = array_merge($user_reward, $point_rewards);
            $reward_types = self::$woocommerce->getRewardDiscountTypes();
            foreach ($user_reward_datas as $user_reward_data) {
                if (is_object($user_reward_data)) {
                    $user_reward_data->reward_type_name = isset($user_reward_data->discount_type) && !empty($user_reward_data->discount_type) && isset($reward_types[$user_reward_data->discount_type]) && $reward_types[$user_reward_data->discount_type] ? $reward_types[$user_reward_data->discount_type] : '';
                    $user_reward_data->expiry_date = (isset($user_reward_data->end_at) && !empty($user_reward_data->end_at) && $user_reward_data->end_at >= 0) ? self::$woocommerce->beforeDisplayDate($user_reward_data->end_at) : '';
                    $user_reward_data->redeem_button_text = (isset($site_custom_settings['redeem_button_text']) && !empty($site_custom_settings['redeem_button_text'])) ? $site_custom_settings['redeem_button_text'] : "";
                    $user_reward_data->redeem_button_color = (isset($site_custom_settings['redeem_button_color']) && !empty($site_custom_settings['redeem_button_color'])) ? $site_custom_settings['redeem_button_color'] : "";
                    $user_reward_data->redeem_button_text_color = (isset($site_custom_settings['redeem_button_text_color']) && !empty($site_custom_settings['redeem_button_text_color'])) ? $site_custom_settings['redeem_button_text_color'] : "";
                    $user_reward_data->apply_coupon_border_color = (isset($site_custom_settings['apply_coupon_border_color']) && !empty($site_custom_settings['apply_coupon_border_color'])) ? $site_custom_settings['apply_coupon_border_color'] : "";
                    $user_reward_data->apply_coupon_button_text_color = (isset($site_custom_settings['apply_coupon_button_text_color']) && !empty($site_custom_settings['apply_coupon_button_text_color'])) ? $site_custom_settings['apply_coupon_button_text_color'] : "";
                    $user_reward_data->apply_coupon_button_color = (isset($site_custom_settings['apply_coupon_button_color']) && !empty($site_custom_settings['apply_coupon_button_color'])) ? $site_custom_settings['apply_coupon_button_color'] : "";
                    $user_reward_data->apply_coupon_button_text = (isset($site_custom_settings['apply_coupon_button_text']) && !empty($site_custom_settings['apply_coupon_button_text'])) ? $site_custom_settings['apply_coupon_button_text'] : "";
                    $user_reward_data->apply_coupon_background = (isset($site_custom_settings['apply_coupon_background']) && !empty($site_custom_settings['apply_coupon_background'])) ? $site_custom_settings['apply_coupon_background'] : "";
                }
            }
            $main_page_params['user_reward'] = array_merge($user_reward, $point_rewards);
        }
        $setting_option = get_option('wlr_settings', '');
        $is_campaign_display = isset($setting_option['is_campaign_display']) && in_array($setting_option['is_campaign_display'], array('no', 'yes')) ? $setting_option['is_campaign_display'] : 'yes';
        $is_reward_display = isset($setting_option['is_reward_display']) && in_array($setting_option['is_reward_display'], array('no', 'yes')) ? $setting_option['is_reward_display'] : 'no';
        $main_page_params['campaign_point_display'] = isset($setting_option['is_campaign_point_display']) && in_array($setting_option['is_campaign_point_display'], array('no', 'yes')) ? $setting_option['is_campaign_point_display'] : 'yes';
        if ($is_reward_display === 'yes') {
            $reward_model = new Rewards();
            $main_page_params['reward_list'] = $reward_model->getQueryData(array('active' => array('operator' => '=', 'value' => 1), 'is_show_reward' => array('operator' => '=', 'value' => 1), 'filter_order' => 'ordering', 'filter_order_dir' => 'ASC'), '*', array(), true, false);
            $main_page_params['is_reward_list_available'] = 'no';
            foreach ($main_page_params['reward_list'] as $reward_opportunity) {
                if (isset($reward_opportunity->is_show_reward) && $reward_opportunity->is_show_reward == 1) {
                    $main_page_params['is_reward_list_available'] = 'yes';
                    break;
                }
            }
        }
        $main_page_params['is_referral_action_available'] = 'no';
        if ($is_campaign_display === 'yes') {
            $campaign_reward = new \Wlr\App\Models\EarnCampaign();
            $main_page_params['campaign_list'] = $campaign_reward->getCurrentCampaignList();
            $earn_campaign_helper = new \Wlr\App\Helpers\EarnCampaign();
            $main_page_params['is_campaign_list_available'] = 'no';
            foreach ($main_page_params['campaign_list'] as $active_campaigns) {
                $active_campaigns = $earn_campaign_helper->getCampaignPointReward($active_campaigns);
                if ($active_campaigns->action_type == 'referral') {
                    $main_page_params['is_referral_action_available'] = 'yes';
                }
                if (isset($active_campaigns->is_show_way_to_earn) && $active_campaigns->is_show_way_to_earn == 1) {
                    $main_page_params['is_campaign_list_available'] = 'yes';
                }
                if ($active_campaigns->action_type == 'followup_share') {
                    $point_rule = self::$woocommerce->isJson($active_campaigns->point_rule) ? json_decode($active_campaigns->point_rule) : new \stdClass();
                    $active_campaigns->share_url = isset($point_rule->share_url) && !empty($point_rule->share_url) ? $point_rule->share_url : '';
                }
            }
        }
        $url = '';
        if (isset($main_page_params['user']->refer_code) && !empty($main_page_params['user']->refer_code)) {
            $url = $earn_campaign->getReferralUrl($main_page_params['user']->refer_code);
        }
        $main_page_params['referral_url'] = $url;
        $social_share_list = array();
        if (!empty($url)) {
            $social_extra = array(
                'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'is_message' => true
            );
            $cart_action_list = $earn_campaign->getSocialActionList();
            $reward_list = $earn_campaign->getActionEarning($cart_action_list, $social_extra);

            foreach ($reward_list as $key => $social_share) {
                if (in_array($key, array('twitter_share', 'facebook_share', 'whatsapp_share'))) {
                    $social_share_message = '';
                    foreach ($social_share as $campaign_id => $share_list) {
                        if (!empty($share_list['messages'])) {
                            $social_share_message .= $share_list['messages'] . ' ';
                        }
                    }
                    $social_share_message = trim($social_share_message, ' ');
                    if (!empty($social_share_message)) {
                        if (isset($social_share_list[$key]['share_content']) && !empty($social_share_list[$key]['share_content'])) {
                            $new_share_content = $social_share_list[$key]['share_content'] . $social_share_message;
                        } else {
                            $new_share_content = $social_share_message;
                        }
                        $share_url = '';
                        if ($key === 'twitter_share') {
                            $social_share_list[$key] = array(
                                'icon' => 'wlr wlrf-twitter_share',
                                'name' => __('Twitter', 'wp-loyalty-rules'),
                                'share_content' => $new_share_content
                            );
                            $share_url = 'https://twitter.com/intent/tweet?text=' . urlencode($new_share_content);
                        }
                        if ($key === 'facebook_share') {
                            $social_share_list[$key] = array(
                                'icon' => 'wlr wlrf-facebook_share',
                                'name' => __('Facebook', 'wp-loyalty-rules'),
                                'share_content' => $new_share_content
                            );
                            $share_url = "https://www.facebook.com/sharer/sharer.php?quote=" . urlencode($new_share_content) . "&u=" . urlencode($url) . "&display=page";
                        }
                        if ($key === 'whatsapp_share') {
                            $social_share_list[$key] = array(
                                'icon' => 'wlr wlrf-whatsapp_share',
                                'name' => __('WhatsApp', 'wp-loyalty-rules'),
                                'share_content' => $new_share_content
                            );
                            $share_url = 'https://api.whatsapp.com/send?text=' . urlencode($new_share_content);
                        }
                        $social_share_list[$key]['url'] = $share_url;
                    }
                } elseif (in_array($key, array('email_share'))) {
                    $share_subject = '';
                    $share_body = '';
                    foreach ($social_share as $share_list) {
                        if (!empty($share_list['messages'])) {
                            if (isset($share_list['messages']['subject']) && !empty($share_list['messages']['subject'])) {
                                $share_subject .= $share_list['messages']['subject'] . ' ';
                            }
                            if (isset($share_list['messages']['body']) && !empty($share_list['messages']['body'])) {
                                $share_body .= $share_list['messages']['body'] . ' ';
                            }
                        }
                    }
                    $share_subject = trim($share_subject, ' ');
                    $share_body = trim($share_body, ' ');
                    if (!empty($share_subject) && !empty($share_body)) {
                        if (isset($social_share_list[$key]['share_subject']) && !empty($social_share_list[$key]['share_subject'])) {
                            $new_share_subject = $social_share_list[$key]['share_subject'] . $share_subject;
                        } else {
                            $new_share_subject = $share_subject;
                        }
                        if (isset($social_share_list[$key]['share_body']) && !empty($social_share_list[$key]['share_body'])) {
                            $new_share_body = $social_share_list[$key]['share_body'] . $share_body;
                        } else {
                            $new_share_body = $share_body;
                        }
                        $share_url = '';
                        if ($key === 'email_share') {
                            $social_share_list[$key] = array(
                                'icon' => 'wlr wlrf-email_share',
                                'name' => __('E-mail', 'wp-loyalty-rules'),
                                'share_subject' => $new_share_subject,
                                'share_body' => $new_share_body,
                            );
                            //$share_url = "mailto:?subject=" . $new_share_subject . "&amp;body=" . $new_share_body;
                            $share_url = "mailto:?subject=" . rawurlencode($new_share_subject) . "&amp;body=" . rawurlencode($new_share_body);
                        }
                        $social_share_list[$key]['url'] = $share_url;
                    }
                }
            }
        }
        $main_page_params['is_social_share_available'] = !empty($social_share_list) ? 'yes' : 'no';
        $main_page_params['social_share_list'] = $social_share_list;
        $main_page_params['page_type'] = $page_type;
        //$transaction_page_number = (int)self::$input->get('transaction_page_number', 1);
        //$per_page = apply_filters('wlr_my_account_transaction_per_page', 5, $user_email);
        //$transaction_offset = $per_page * ($transaction_page_number - 1);
        $main_page_params = apply_filters('wlr_myaccount_page_data', $main_page_params);
        $main_page_params['point_revert_icon'] = WLR_PLUGIN_URL . 'Assets/Site/svg/revert.svg';
        if ($page_type != 'cart') {
            $is_transaction_display = isset($setting_option['is_transaction_display']) && in_array($setting_option['is_transaction_display'], array('no', 'yes')) ? $setting_option['is_transaction_display'] : 'yes';
            if (!empty($user_email) && $is_transaction_display == 'yes') {
                global $wp;
                $logs = new Logs();
                //$earn_campaign_transaction = new EarnCampaignTransactions();
                $offset = (int)isset($wp->query_vars) && isset($wp->query_vars['loyalty_reward']) && !empty($wp->query_vars['loyalty_reward']) ? $wp->query_vars['loyalty_reward'] : 1;
                $limit = 5;
                $start = ($offset - 1) * $limit;
                $main_page_params['transactions'] = $logs->getUserLogTransactions($user_email, $limit, $start);
                $main_page_params['transaction_total'] = (int)$logs->getUserLogTransactionsCount($user_email);
                $main_page_params['offset'] = $offset;
                $main_page_params['current_trans_count'] = (int)($offset * $limit);
                $main_page_params['point_revert_icon'] = WLR_PLUGIN_URL . 'Assets/Site/svg/revert.svg';
            }
        }
        return $main_page_params;
    }

    public function addEndPoints()
    {
        if (self::$woocommerce->isBannedUser()) return;
        $status = true;
        $status = apply_filters('wlr_flush_rewrite_rules', $status);
        if ($status) {
            flush_rewrite_rules();
        }
        add_rewrite_endpoint('loyalty_reward', EP_ROOT | EP_PAGES);
    }

    function showRewardList()
    {
        $json = array(
            'html' => ''
        );
        $wlr_nonce = (string)self::$input->post_get('wlr_nonce', '');
        if (!Woocommerce::verify_nonce($wlr_nonce, 'wlr_redeem_nonce')) {
            wp_send_json_success($json);
        }
        $json['html'] = $this->rewardPage('cart');
        wp_send_json_success($json);
    }

    function processShortCode($attr, $content)
    {
        if (self::$woocommerce->isBannedUser()) return '';
        return $this->rewardPage('page');
    }

    function getLoyaltyPageData($params, $page_type = 'page')
    {
        return $this->rewardPageData($page_type);
    }
}