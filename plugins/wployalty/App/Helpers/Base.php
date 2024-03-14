<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die;

use Exception;
use Wlr\App\Models\EarnCampaignTransactions;
use Wlr\App\Models\Levels;
use Wlr\App\Models\Logs;
use Wlr\App\Models\PointsLedger;
use Wlr\App\Models\Rewards;
use Wlr\App\Models\RewardTransactions;
use Wlr\App\Models\UserRewards;
use Wlr\App\Models\Users;
use DateTimeZone;
use DateTime;

class Base
{
    public static $woocommerce_helper, $input, $user_by_email, $point_user, $user_model, $earn_campaign_transaction_model;
    public static $user_level = array();
    public static $next_level = array();
    public static $action_reward_by_id;
    public static $user_reward_by_coupon = array();

    public function __construct($config = array())
    {
        self::$input = empty(self::$input) ? new Input() : self::$input;
        self::$woocommerce_helper = empty(self::$woocommerce_helper) ? Woocommerce::getInstance() : self::$woocommerce_helper;
        self::$user_model = empty(self::$user_model) ? new Users() : self::$user_model;
        self::$earn_campaign_transaction_model = empty(self::$earn_campaign_transaction_model) ? new EarnCampaignTransactions() : self::$earn_campaign_transaction_model;
    }

    public static function readMoreLessContent($message, $read_key, $length, $read_more_text, $read_less_text, $id_prefix = 'read-more-less', $class = '')
    {
        $message_description = (string)(isset($message) && !empty($message)) ? __($message, 'wp-loyalty-rules') : '';
        $read_more_text = (string)(isset($read_more_text) && !empty($read_more_text)) ? __($read_more_text, 'wp-loyalty-rules') : __('Show more', 'wp-loyalty-rules');
        $read_less_text = (string)(isset($read_less_text) && !empty($read_less_text)) ? __($read_less_text, 'wp-loyalty-rules') : __('Show less', 'wp-loyalty-rules');
        $length = (int)(!empty($length) && $length > 0) ? $length : 40;
        $string_length = 0;
        $splitted_string = explode(' ', $message_description);
        $first_half = array();
        $sec_half = array();
        foreach ($splitted_string as $string) {
            $string_length += strlen($string);
            if ($string_length >= $length) {
                $sec_half[] = $string;
            } else {
                $first_half[] = $string;
            }
        }
        $first_set = isset($first_half) && !empty($first_half) && is_array($first_half) ? implode(' ', $first_half) : '';
        $sec_set = isset($sec_half) && !empty($sec_half) && is_array($sec_half) ? implode(' ', $sec_half) : '';
        $response_div = '<p class="' . esc_attr($class) . ' add-read-more show-less-content"
           id="' . esc_attr('wlr-' . $id_prefix . '-' . $read_key) . '">';
        $first_set = (!empty($first_set)) ? $first_set : $message_description;
        $response_div = $response_div . $first_set;
        $setting_option = self::$woocommerce_helper->getOptions('wlr_settings');
        $theme_color = isset($setting_option['theme_color']) && !empty($setting_option['theme_color']) ? 'color:' . $setting_option['theme_color'] . ';' : '';
        if (!empty($sec_set)) {
            $response_div .= '<span class="sec-half"> ' . $sec_set . '</span>';
            $response_div .= '&hellip; <span class="read-more"
            onclick="wlr_jquery( \'body\' ).trigger(\'readMoreLessContent\',[\'' . esc_js('#wlr-' . $id_prefix . '-' . $read_key) . '\']);"
                      ><small class="wlr-read-more-label" style="' . esc_attr($theme_color) . '"> ' . $read_more_text . '</small></span>';
            $response_div .= '<span class="read-less"
                      onclick="wlr_jquery( \'body\' ).trigger(\'readMoreLessContent\',[\'' . esc_js('#wlr-' . $id_prefix . '-' . $read_key) . '\']);"
                      ><small class="wlr-read-less-label" style="' . esc_attr($theme_color) . '">' . $read_less_text . '</small></span>';
        }
        $response_div .= '</p>';
        return $response_div;
    }

    function getTotalEarning($action_type = '', $ignore_condition = array(), $extra = array(), $is_product_level = false)
    {
        $earning = array();
        if (!$this->is_valid_action($action_type) || !$this->isEligibleForEarn($action_type, $extra) || self::$woocommerce_helper->isBannedUser()) {
            return $earning;
        }
        $campaign_helper = EarnCampaign::getInstance();
        $earn_campaign_table = new \Wlr\App\Models\EarnCampaign();
        $campaign_list = $earn_campaign_table->getCampaignByAction($action_type);

        if (!empty($campaign_list)) {
            $action_data = array(
                'action_type' => $action_type,
                'ignore_condition' => $ignore_condition,
                'is_product_level' => $is_product_level,
            );
            if (!empty($extra) && is_array($extra)) {
                foreach ($extra as $key => $value) {
                    $action_data[$key] = $value;
                }
            }
            $action_data = apply_filters('wlr_before_rule_data_process', $action_data, $campaign_list);
            $order_id = isset($action_data['order']) && !empty($action_data['order']) ? $action_data['order']->get_id() : 0;
            self::$woocommerce_helper->_log('getTotalEarning Action data:' . json_encode($action_data));
            $social_share = $this->getSocialActionList();
            foreach ($campaign_list as $campaign) {
                $processing_campaign = $campaign_helper->getCampaign($campaign);
                $campaign_id = isset($processing_campaign->earn_campaign->id) && $processing_campaign->earn_campaign->id > 0 ? $processing_campaign->earn_campaign->id : 0;
                if ($campaign_id && $order_id) {
                    self::$woocommerce_helper->_log('getTotalEarning Action:' . $action_type . ',Campaign id:' . $campaign_id . ', Before check user already earned');
                    if ($this->checkUserEarnedInCampaignFromOrder($order_id, $campaign_id)) {
                        continue;
                    }
                }
                $action_data['campaign_id'] = $campaign_id;
                $campaign_earning = array();
                if (isset($processing_campaign->earn_campaign->campaign_type) && 'point' === $processing_campaign->earn_campaign->campaign_type) {
                    //campaign_id and order_id
                    self::$woocommerce_helper->_log('getTotalEarning Action:' . $action_type . ',Campaign id:' . $campaign_id . ', Before earn point:' . json_encode($action_data));
                    $campaign_earning['point'] = $processing_campaign->getCampaignPoint($action_data);
                    $earning[$campaign->id]['point'] = $campaign_earning['point'];
                } elseif (isset($processing_campaign->earn_campaign->campaign_type) && 'coupon' === $processing_campaign->earn_campaign->campaign_type) {
                    self::$woocommerce_helper->_log('getTotalEarning Action:' . $action_type . ',Campaign id:' . $campaign_id . ', Before earn coupon:' . json_encode($action_data));
                    $earning[$campaign->id]['rewards'][] = $campaign_earning['rewards'][] = $processing_campaign->getCampaignReward($action_data);
                }
                $earning[$campaign->id]['messages'] = $this->processCampaignMessage($action_type, $processing_campaign, $campaign_earning);
                if (in_array($action_type, $social_share)) {
                    $earning[$campaign->id]['icon'] = isset($processing_campaign->earn_campaign->icon) && !empty($processing_campaign->earn_campaign->icon) ? $processing_campaign->earn_campaign->icon : '';
                }
            }
            self::$woocommerce_helper->_log('getTotalEarning Action:' . $action_type . ', Total earning:' . json_encode($earning));
        }
        return $earning;
    }

    function is_valid_action($action_type)
    {
        $status = false;
        $action_types = self::$woocommerce_helper->getActionTypes();
        if (!empty($action_type) && isset($action_types[$action_type]) && !empty($action_types[$action_type])) {
            $status = true;
        }
        return $status;
    }

    function isEligibleForEarn($action_type, $extra = array())
    {
        return apply_filters('wlr_is_eligible_for_earning', true, $action_type, $extra);
    }

    function getSocialActionList()
    {
        $social_action_list = array(
            'facebook_share', 'twitter_share', 'whatsapp_share', 'email_share'
        );
        return apply_filters('wlr_social_action_list', $social_action_list);
    }

    function checkUserEarnedInCampaignFromOrder($order_id, $campaign_id)
    {
        if ($order_id <= 0 || $campaign_id <= 0) {
            return false;
        }
        global $wpdb;
        $where = $wpdb->prepare('order_id = %s AND campaign_id = %s', array($order_id, $campaign_id));
        $result = (new EarnCampaignTransactions())->getWhere($where);
        return !empty($result);
    }


    function processCampaignMessage($action_type, $rule, $earning)
    {
        $messages = array();
        if (!empty($action_type) && $action_type === $rule->earn_campaign->action_type) {
            if (isset($rule->earn_campaign->point_rule) && !empty($rule->earn_campaign->point_rule)) {
                if (self::$woocommerce_helper->isJson($rule->earn_campaign->point_rule)) {
                    $point_rule = json_decode($rule->earn_campaign->point_rule);
                    $class_name = ucfirst($this->camelCaseAction($action_type));
                    $class_free_helper = '\\Wlr\\App\\Helpers\\' . $class_name;
                    $class_pro_helper = '\\Wlr\\App\\Premium\\Helpers\\' . $class_name;
                    if (class_exists($class_free_helper)) {
                        $helper = new $class_free_helper();
                    } elseif (class_exists($class_pro_helper)) {
                        $helper = new $class_pro_helper();
                    }
                    if (isset($helper) && method_exists($helper, 'processMessage')) {
                        $messages = $helper->processMessage($point_rule, $earning);
                    }
                }
            }
        }
        return $messages;
    }

    protected function camelCaseAction($action_type)
    {
        $action_type = trim($action_type);
        $action_type = lcfirst($action_type);
        $action_type = preg_replace('/^[-_]+/', '', $action_type);
        $action_type = preg_replace_callback(
            '/[-_\s]+(.)?/u',
            function ($match) {
                if (isset($match[1])) {
                    return strtoupper($match[1]);
                } else {
                    return '';
                }
            },
            $action_type
        );
        $action_type = preg_replace_callback(
            '/[\d]+(.)?/u',
            function ($match) {
                return strtoupper($match[0]);
            },
            $action_type
        );
        return $action_type;
    }

    function processShortCodes($short_codes, $message)
    {
        if (!is_array($short_codes)) {
            return $message;
        }
        foreach ($short_codes as $key => $value) {
            $message = str_replace($key, $value, $message);
        }
        return apply_filters('wlr_process_message_short_codes', $message, $short_codes);
    }

    function getUserRewardTransaction($code, $order_id)
    {
        if (empty($code) || empty($order_id)) {
            return '';
        }
        return (new RewardTransactions())->getQueryData(
            array(
                'discount_code' => array(
                    'operator' => '=',
                    'value' => $code,
                ),
                'order_id' => array(
                    'operator' => '=',
                    'value' => $order_id,
                ),
            ),
            '*',
            array(),
            false
        );
    }

    function is_loyalty_coupon($code)
    {
        if (empty($code)) {
            return false;
        }
        $user_reward = $this->getUserRewardByCoupon($code);
        if (!empty($user_reward)) {
            return true;
        }
        return false;
    }

    function getUserRewardByCoupon($code)
    {
        if (empty($code)) {
            return '';
        }
        $code = (is_object($code) && isset($code->code)) ? $code->get_code() : $code;
        if (!isset(self::$user_reward_by_coupon[$code])) {
            self::$user_reward_by_coupon[$code] = (new UserRewards())->getQueryData(
                array(
                    'discount_code' => array(
                        'operator' => '=',
                        'value' => $code,
                    ),
                ),
                '*',
                array(),
                false
            );
        }
        return isset(self::$user_reward_by_coupon[$code]) ? self::$user_reward_by_coupon[$code] : '';
    }

    public function roundPoints($points)
    {
        $setting_option = get_option('wlr_settings', '');
        $rounding_option = (isset($setting_option['wlr_point_rounding_type']) && !empty($setting_option['wlr_point_rounding_type'])) ? $setting_option['wlr_point_rounding_type'] : 'round';
        switch ($rounding_option) {
            case 'ceil':
                $point_earned = ceil($points);
                break;
            case 'floor':
                $point_earned = floor($points);
                break;
            default:
                $point_earned = round($points);
                break;
        }
        return $point_earned;
    }

    function getPointOrRewardText($point, $available_rewards, $with_label = false)
    {
        $with_label = apply_filters('wlr_earn_point_or_reward_label', $with_label);
        $text = '';
        if ($point > 0) {
            $text = $with_label ? $point . ' ' . $this->getPointLabel($point) : $point;
        }
        if (!empty($available_rewards)) {
            $reward_count = count(explode(',', $available_rewards));
            $available_rewards = $with_label ? $available_rewards . ' ' . $this->getRewardLabel($reward_count) : $available_rewards;
        }
        $is_reward_added = false;
        if (empty($text) && !empty($available_rewards)) {
            $is_reward_added = true;
            $text = $available_rewards;
        }
        if (!$is_reward_added && !empty($available_rewards)) {
            $text .= ' / ' . $available_rewards;
        }
        return $text;
    }

    public function getPointLabel($point, $label_translate = true)
    {
        $setting_option = get_option('wlr_settings', '');
        $singular = (isset($setting_option['wlr_point_singular_label']) && !empty($setting_option['wlr_point_singular_label'])) ? $setting_option['wlr_point_singular_label'] : 'point';
        if ($label_translate) {
            $singular = __($singular, 'wp-loyalty-rules');
        }
        $plural = (isset($setting_option['wlr_point_label']) && !empty($setting_option['wlr_point_label'])) ? $setting_option['wlr_point_label'] : 'points';
        if ($label_translate) {
            $plural = __($plural, 'wp-loyalty-rules');
        }
        $point_label = ($point == 0 || $point > 1) ? $plural : $singular;
        return apply_filters('wlr_get_point_label', $point_label, $point);
    }

    public function getRewardLabel($reward_count = 0)
    {
        $setting_option = get_option('wlr_settings', '');
        $singular = (isset($setting_option['reward_singular_label']) && !empty($setting_option['reward_singular_label'])) ? __($setting_option['reward_singular_label'], 'wp-loyalty-rules') : __('reward', 'wp-loyalty-rules');
        $plural = (isset($setting_option['reward_plural_label']) && !empty($setting_option['reward_plural_label'])) ? __($setting_option['reward_plural_label'], 'wp-loyalty-rules') : __('rewards', 'wp-loyalty-rules');
        $reward_label = ($reward_count == 0 || $reward_count > 1) ? $plural : $singular;
        return apply_filters('wlr_get_reward_label', $reward_label, $reward_count);
    }

    function getUserPoint($email)
    {
        $point = 0;
        if (empty($email)) {
            return $point;
        }
        if (!isset(self::$point_user[$email])) {
            $email = sanitize_email($email);
            $point_user = $this->getPointUserByEmail($email);
            if (!empty($point_user) && $point_user->points) {
                $point = $point_user->points;
            }
            self::$point_user[$email] = $point;
        }
        return self::$point_user[$email];
    }

    function getPointUserByEmail($user_email)
    {
        if (empty($user_email)) {
            return '';
        }

        $user_email = sanitize_email($user_email);

        if (!isset(self::$user_by_email[$user_email])) {
            self::$user_by_email[$user_email] = self::$user_model->getQueryData(
                array(
                    'user_email' => array(
                        'operator' => '=',
                        'value' => $user_email,
                    ),
                ),
                '*',
                array(),
                false
            );
        }
        return self::$user_by_email[$user_email];
    }

    function getPointBalanceByEmail($user_email)
    {
        if (empty($user_email)) {
            return null;
        }
        $user = self::$user_model->getQueryData(
            array(
                'user_email' => array(
                    'operator' => '=',
                    'value' => $user_email,
                ),
            ),
            '*',
            array(),
            false
        );
        return is_object($user) && isset($user->points) && $user->points > 0 ? $user->points : 0;
    }

    function getCustomerEmail($user_email, $order = null)
    {
        if (empty($user_email)) {
            $user = $order->get_user();
            if (isset($user->user_email) && !empty($user->user_email)) {
                $user_email = $user->user_email;
            }
            if (empty($user_email)) {
                $user_email = $order->get_billing_email();
            }
        }
        return $user_email;
    }

    // Customer details page

    function getUserRewardCount($user_email, $status = '')
    {
        $total_reward_count = 0;
        if (empty($user_email)) {
            return $total_reward_count;
        }
        $user_reward_where = array(
            'email' => array(
                'operator' => '=',
                'value' => sanitize_email($user_email),
            ),
        );
        if (!empty($status)) {
            $user_reward_where['status'] = array(
                'operator' => '=',
                'value' => sanitize_text_field($status),
            );
        }
        $total = (new UserRewards())->getQueryData($user_reward_where, 'count(*) as total_reward', array(), false);
        if (isset($total->total_reward)) {
            $total_reward_count = $total->total_reward;
        }
        return $total_reward_count;
    }

    function getUserTotalTransactionAmount($user_email)
    {
        $total_amount_list = array();
        if (empty($user_email)) {
            return $total_amount_list;
        }
        $total_trans = (new RewardTransactions())->getUserTotalRewardTransactions($user_email);
        if (!empty($total_trans)) {
            $tax_including = wc_tax_enabled() && $this->isIncludingTax();
            foreach ($total_trans as $transaction) {
                $total_amount_list[$transaction->reward_currency] = array(
                    'reward_count' => $transaction->r_count,
                    'order_total' => $transaction->r_order_total,
                    'reward_amount' => $tax_including ? ($transaction->r_amount + $transaction->r_tax) : $transaction->r_amount,
                    'currency_symbol' => $transaction->reward_currency,
                );
                $total_amount_list[$transaction->reward_currency]['display_format'] = wc_price($total_amount_list[$transaction->reward_currency]['reward_amount'], array('currency' => $transaction->reward_currency));
            }
        }
        return $total_amount_list;
    }

    function getStartAndEnd()
    {
        $start = 0;
        $end = 0;
        $null_date = 0;
        try {
            $filter_type = (string)self::$input->post_get('fil_type', '90_days');
            $timezone = new DateTimeZone('UTC');
            if ($filter_type == '90_days') {
                $current_time = new DateTime('now', $timezone);
                $last_time = new DateTime('-90 days', $timezone);
                $start = $last_time->format('Y-m-d 00:00:00');
                $end = $current_time->format('Y-m-d 23:59:59');
            } elseif ($filter_type == 'this_month') {
                $current_time = new DateTime('now', $timezone);
                $start = $current_time->format('Y-m-01 00:00:00');
                $end = $current_time->format('Y-m-d 23:59:59');
            } elseif ($filter_type == 'last_month') {
                $current_time = new DateTime();
                $current_time->modify('last day of last month');
                //$current_time = new DateTime('-1 month', $timezone);
                $start = $current_time->format('Y-m-01 00:00:00');
                $end = $current_time->format('Y-m-t 23:59:59');
            } elseif ($filter_type == 'last_year') {
                $current_time = new DateTime('-1 year', $timezone);
                $start = $current_time->format('Y-01-01 00:00:00');
                $end = $current_time->format('Y-12-t 23:59:59');
            } elseif ($filter_type == 'custom') {
                $from_date = self::$input->post('from_date', $null_date);
                $to_date = self::$input->post('to_date', $null_date);
                if ($to_date != $null_date) {
                    $current_time = new DateTime($to_date);
                    $end = $current_time->format('Y-m-d 23:59:59');
                }
                if ($from_date != $null_date) {
                    $current_time = new DateTime($from_date);
                    $start = $current_time->format('Y-m-d 00:00:00');
                }
            }
        } catch (Exception $e) {
        }
        return array(
            'start' => $start,
            'end' => $end,
        );
    }

    function getRewardById($reward_id)
    {
        if (empty($reward_id)) {
            return '';
        }
        if (isset(self::$action_reward_by_id[$reward_id]) && !empty(self::$action_reward_by_id[$reward_id])) {
            return self::$action_reward_by_id[$reward_id];
        }

        return self::$action_reward_by_id[$reward_id] = (new Rewards())->getQueryData(
            array(
                'id' => array(
                    'operator' => '=',
                    'value' => (int)$reward_id,
                ),
                'active' => array(
                    'operator' => '=',
                    'value' => 1,
                ),
            ),
            '*',
            array(),
            false
        );
    }

    function checkSocialShare($data)
    {
        if (isset($data['is_message']) && $data['is_message']) {
            return true;
        }
        if (!is_array($data) || empty($data['user_email']) || empty($data['action_type'])) {
            return false;
        }
        if (empty($data['campaign_id']) || $data['campaign_id'] <= 0) {
            return false;
        }
        $user_email = sanitize_email($data['user_email']);
        $status = false;
        if (isset($data['earn_type']) &&
            in_array(
                $data['earn_type'],
                array(
                    'point',
                    'coupon',
                )
            )
        ) {
            $transaction_data = self::$earn_campaign_transaction_model->getQueryData(
                array(
                    'user_email' => array(
                        'operator' => '=',
                        'value' => sanitize_email($user_email),
                    ),
                    'action_type' => array(
                        'operator' => '=',
                        'value' => $data['action_type'],
                    ),
                    //'campaign_type' => array('operator' => '=', 'value' => $data['earn_type']),
                    'transaction_type' => array(
                        'operator' => '=',
                        'value' => 'credit',
                    ),
                    'campaign_id' => array(
                        'operator' => '=',
                        'value' => $data['campaign_id'],
                    ),
                ),
                '*',
                array(),
                false
            );
            if (empty($transaction_data)) {
                $status = true;
            }
        }
        return $status;
    }

    function getCartEarnMessageDesign($message = '')
    {
        $design_message = '<div class="wlr-message-info wlr_points_rewards_earn_points" style="display: none;"></div>';
        if (!empty($message)) {
            $setting_option = self::$woocommerce_helper->getOptions('wlr_settings');
            $cart_text_color = (isset($setting_option['earn_cart_text_color']) && !empty($setting_option['earn_cart_text_color'])) ? $setting_option['earn_cart_text_color'] : '#9CC21D';
            $cart_border_color = (isset($setting_option['earn_cart_border_color']) && !empty($setting_option['earn_cart_border_color'])) ? $setting_option['earn_cart_border_color'] : '#9CC21D';
            $cart_background_color = (isset($setting_option['earn_cart_background_color']) && !empty($setting_option['earn_cart_background_color'])) ? $setting_option['earn_cart_background_color'] : '#ffffff';
            $message_icon = isset($setting_option['earn_message_icon']) && !empty($setting_option['earn_message_icon']) ? $setting_option['earn_message_icon'] : '';
            $svg_file = self::setImageIcon(
                $message_icon,
                'point',
                array(
                    'alt' => __('Earn point message', 'wp-loyalty-rules'),
                    'style' => 'color: $cart_border_color;margin: 8px 20px 8px 0; font-size: 30px;border-radius:6px;',
                )
            );
            $design_message = '<div class="wlr-message-info wlr_points_rewards_earn_points" style="' . esc_attr('margin:5px 0;padding: 5px 28px;border:1px solid ' . $cart_border_color . '; border-radius: 6px; color:' . $cart_text_color . '; background-color: ' . $cart_background_color . '; font-size: 15px;font-weight: 600; display: flex; align-items: center;') . '">
' . $svg_file . '<p style="margin:0 0 0;">' . $message . '</p>' . '</div>';
        }
        return $design_message;
    }

    public static function setImageIcon($img, $icon, $attributes)
    {
        $html = '';
        $img_alt = isset($attributes['alt']) && !empty($attributes['alt']) ? $attributes['alt'] : $icon . '_image';
        $img_height = isset($attributes['height']) && !empty($attributes['height']) ? $attributes['height'] : '42';
        $img_width = isset($attributes['width']) && !empty($attributes['width']) ? $attributes['width'] : '42';
        $img_class = isset($attributes['class']) && !empty($attributes['class']) ? $attributes['class'] . ' wlr-upload-img-icon' : 'wlr-upload-img-icon';
        $img_style = isset($attributes['style']) && !empty($attributes['style']) ? $attributes['style'] : ' ';
        $icon_class = ($icon === 'point') ? 'wlr wlrf-point wlr-theme-color-apply' : "wlr wlrf-$icon wlr-icon wlr-theme-color-apply";
        $icon_style = ($icon === 'point') ? $img_style : 'height: 52px; width: 52px;';
        if ($img === 'social') {
            $icon_style = $img_style;
            $icon_class = "wlr-$icon wlr-social-icon wlr-theme-color-apply";
        }
        if (isset($img) && !in_array($img, array('', null, 'null')) && !empty($img) && $img !== 'social') {
            $html .= '<img src="' . esc_url($img) . '" alt="' . esc_attr($img_alt) . '" height="' . esc_attr($img_height) . '" width="' . esc_attr($img_width) . '" class="' . esc_attr($img_class) . '" style="' . esc_attr($img_style) . '">';
        } else {
            $html .= '<i class="' . esc_attr($icon_class) . '" style = "' . esc_attr($icon_style) . '" ></i >';
        }
        return $html;
    }

    function getCartRedeemMessageDesign($message = '')
    {
        $design_message = '<div class="wlr-message-info wlr_point_redeem_message"></div>';
        if (!empty($message)) {
            $setting_option = self::$woocommerce_helper->getOptions('wlr_settings');
            $cart_text_color = (isset($setting_option['redeem_cart_text_color']) && !empty($setting_option['redeem_cart_text_color'])) ? $setting_option['redeem_cart_text_color'] : '#9CC21D';
            $cart_border_color = (isset($setting_option['redeem_cart_border_color']) && !empty($setting_option['redeem_cart_border_color'])) ? $setting_option['redeem_cart_border_color'] : '#9CC21D';
            $cart_background_color = (isset($setting_option['redeem_cart_background_color']) && !empty($setting_option['redeem_cart_background_color'])) ? $setting_option['redeem_cart_background_color'] : '#ffffff';
            $message_icon = isset($setting_option['redeem_message_icon']) && !empty($setting_option['redeem_message_icon']) ? $setting_option['redeem_message_icon'] : '';
            $svg_file = self::setImageIcon(
                $message_icon,
                'point',
                array(
                    'alt' => __('Redeem point message', 'wp-loyalty-rules'),
                    'style' => 'color: $cart_border_color;margin: 8px 20px 8px 0; font-size: 30px;border-radius:6px;',
                )
            );
            $design_message = '<div class="wlr-message-info wlr_point_redeem_message" style="' . esc_attr('margin:5px 0;padding: 5px 28px;border:1px solid ' . $cart_border_color . '; border-radius: 6px; color:' . $cart_text_color . '; background-color: ' . $cart_background_color . '; font-size: 15px;font-weight: 600; display: flex; align-items: center;') . '">
' . $svg_file . '<p style="margin: 0 0 0;">' . $message . '</p></div>';
        }
        return $design_message;
    }

    function getThankfulPageDesign($message = '')
    {
        $design_message = '';
        if (!empty($message)) {
            $setting_option = self::$woocommerce_helper->getOptions('wlr_settings');
            $cart_text_color = (isset($setting_option['earn_cart_text_color']) && !empty($setting_option['earn_cart_text_color'])) ? $setting_option['earn_cart_text_color'] : '#9CC21D';
            $cart_border_color = (isset($setting_option['earn_cart_border_color']) && !empty($setting_option['earn_cart_border_color'])) ? $setting_option['earn_cart_border_color'] : '#9CC21D';
            $cart_background_color = (isset($setting_option['earn_cart_background_color']) && !empty($setting_option['earn_cart_background_color'])) ? $setting_option['earn_cart_background_color'] : '#ffffff';
            $message_icon = isset($setting_option['earn_message_icon']) && !empty($setting_option['earn_message_icon']) ? $setting_option['earn_message_icon'] : '';
            $svg_file = self::setImageIcon(
                $message_icon,
                'point',
                array(
                    'alt' => __('Thank you point message', 'wp-loyalty-rules'),
                    'style' => 'color:$cart_border_color;margin: 8px 20px 8px 0; font-size: 30px;border-radius:6px;',
                )
            );
            $design_message = '<div class="wlr-message-info wlr_thankyou_message" style="' . esc_attr('margin:5px 0;padding: 5px 28px;border:1px solid ' . $cart_border_color . '; border-radius: 6px; color:' . $cart_text_color . '; background-color: ' . $cart_background_color . '; font-size: 15px;font-weight: 600; display: flex; align-items: center;') . '">
' . $svg_file . '<p style="margin: 0 0 0;">' . $message . '</p></div>';
        }
        return $design_message;
    }

    /**
     * get level details
     * @param $id
     * @return object|null
     */
    function getLevel($id)
    {
        if ($id <= 0 || !$this->isPro()) {
            return null;
        }
        if (!isset(self::$user_level[$id]) || empty(self::$user_level[$id])) {
            self::$user_level[$id] = (new Levels())->getQueryData(
                array(
                    'id' => array(
                        'operator' => '=',
                        'value' => (int)$id,
                    ),
                    'active' => array(
                        'operator' => '=',
                        'value' => 1,
                    ),
                ),
                '*',
                array(),
                false
            );
        }
        return self::$user_level[$id];
    }

    function isPro()
    {
        return apply_filters('wlr_is_pro', false);
    }

    function getNextLevel($to_point = 0, $level_id = 0)
    {
        if ($to_point < 0 || !$this->isPro()) {
            return null;
        }
        if (isset(self::$next_level[$level_id]) && !empty(self::$next_level[$level_id])) {
            return self::$next_level[$level_id];
        }
        $level_where_data = array(
            'from_points' => array(
                'operator' => '>',
                'value' => (int)$to_point,
            ),
            'active' => array(
                'operator' => '=',
                'value' => 1,
            ),
            'filter_order' => 'from_points',
            'filter_order_dir' => 'ASC',
        );
        return self::$next_level[$level_id] = (new Levels())->getQueryData($level_where_data);
    }

    function addExtraTransaction($action, $user_email, $params = array())
    {
        if (empty($action) || !$this->isValidExtraAction($action) || empty($user_email) || empty($params)) {
            return false;
        }
        return self::$earn_campaign_transaction_model->saveExtraTransaction($action, $user_email, $params);
    }

    function isValidExtraAction($action_type)
    {
        $status = false;
        $action_types = $this->getExtraActionList();
        if (!empty($action_type) && isset($action_types[$action_type]) && !empty($action_types[$action_type])) {
            $status = true;
        }
        return $status;
    }

    function getExtraActionList()
    {
        $action_list = array(
            'admin_change' => __('Admin updated', 'wp-loyalty-rules'),
            'redeem_point' => sprintf(__('Convert %s to coupon', 'wp-loyalty-rules'), $this->getPointLabel(3)),
            'new_user_add' => __('New Customer', 'wp-loyalty-rules'),
            'import' => __('Import Customer', 'wp-loyalty-rules'),
            'revoke_coupon' => __('Revoke coupon', 'wp-loyalty-rules'),
            'expire_date_change' => __('Expiry date has been changed manually', 'wp-loyalty-rules'),
            'expire_email_date_change' => __('Expiry email date has been changed manually', 'wp-loyalty-rules'),
            'expire_point' => sprintf(__('%s Expired', 'wp-loyalty-rules'), $this->getPointLabel(3)),
            'new_level' => __('New Level', 'wp-loyalty-rules'),
            'rest_api' => __('REST API', 'wp-loyalty-rules')
        );
        return apply_filters("wlr_extra_action_list", $action_list);
    }

    function getProductActionList()
    {
        $cart_action_list = array(
            'point_for_purchase',
        );
        return apply_filters('wlr_product_action_list', $cart_action_list);
    }

    function getCartActionList()
    {
        $cart_action_list = array(
            'point_for_purchase',
        );
        return apply_filters('wlr_cart_action_list', $cart_action_list);
    }

    function getReferralUrl($code = '')
    {
        if (empty($code)) {
            $user_email = self::$woocommerce_helper->get_login_user_email();
            $user = $this->getPointUserByEmail($user_email);
            $code = !empty($user) && isset($user->refer_code) && !empty($user->refer_code) ? $user->refer_code : '';
        }
        $url = '';
        if (!empty($code)) {
            $url = site_url() . '?wlr_ref=' . $code;
        }
        return apply_filters('wlr_get_referral_url', $url, $code);
    }

    public function get_coupon_expiry_date($expiry_date, $as_timestamp = false)
    {
        if (!empty($expiry_date) && '' != $expiry_date) {
            if ($as_timestamp) {
                return strtotime($expiry_date);
            }
            return date('Y-m-d', strtotime($expiry_date));
        }
        return '';
    }

    function addExtraRewardAction($action_type, $reward, $action_data)
    {
        if (!is_array($action_data) || !isset($reward->id) || $reward->id <= 0 || empty($action_data['user_email']) || empty($action_type) || !$this->isValidExtraAction($action_type)) {
            return false;
        }
        try {
            $reward = apply_filters('wlr_before_add_earn_reward', $reward, $action_type, $action_data);
            $reward = apply_filters('wlr_notify_before_add_earn_reward', $reward, $action_type, $action_data);
            $conditions = array('user_email' => array('operator' => '=', 'value' => sanitize_email($action_data['user_email'])));
            $user = self::$user_model->getQueryData($conditions, '*', array(), false, true);
            $created_at = strtotime(date("Y-m-d H:i:s"));
            if (empty($user)) {
                $uniqueReferCode = $this->get_unique_refer_code('', false, $action_data['user_email']);
                $_data = array(
                    'user_email' => $action_data['user_email'],
                    'refer_code' => $uniqueReferCode,
                    'points' => 0,
                    'used_total_points' => 0,
                    'earn_total_point' => 0,
                    'birth_date' => 0,
                    'created_date' => $created_at,
                );
                if (!self::$user_model->insertOrUpdate($_data)) return false;
            }
            $campaign_id = isset($action_data['campaign_id']) && $action_data['campaign_id'] > 0 ? $action_data['campaign_id'] : 0;
            $args = array(
                'user_email' => $action_data['user_email'],
                'points' => 0,
                'action_type' => $action_type,
                'campaign_type' => 'coupon',
                'transaction_type' => 'credit',
                'display_name' => $reward->display_name,
                'campaign_id' => $campaign_id,
                'reward_id' => $reward->id,
                'created_at' => $created_at,
                'modified_at' => 0,
                'product_id' => isset($action_data['product_id']) && !empty($action_data['product_id']) ? $action_data['product_id'] : 0,
                'order_id' => isset($action_data['order_id']) && !empty($action_data['order_id']) ? $action_data['order_id'] : 0,
                'admin_user_id' => null,
                'log_data' => '{}',
                'referral_type' => isset($action_data['referral_type']) && !empty($action_data['referral_type']) ? $action_data['referral_type'] : '',
                'order_currency' => isset($action_data['order_currency']) && !empty($action_data['order_currency']) ? $action_data['order_currency'] : '',
                'order_total' => isset($action_data['order_total']) && !empty($action_data['order_total']) ? $action_data['order_total'] : 0,
            );
            if (isset($action_data['order']) && !empty($action_data['order']) && (empty($args['order_currency']) || empty($args['order_total']))) {
                $args['order_currency'] = $action_data['order']->get_currency();
                $args['order_total'] = $action_data['order']->get_total();
            }
            if (isset($action_data['log_data'])) {
                $args['log_data'] = json_encode($action_data['log_data']);
            }
            if (is_admin()) {
                $admin_user = wp_get_current_user();
                $args['admin_user_id'] = $admin_user->ID;
            }
            $earn_trans_id = self::$earn_campaign_transaction_model->insertRow($args);
            $earn_trans_id = apply_filters('wlr_after_add_extra_earn_reward_transaction', $earn_trans_id, $args);
            if ($earn_trans_id == 0) {
                return false;
            }
            $user_reward_data = array(
                'name' => $reward->name,
                'description' => $reward->description,
                'email' => sanitize_email($action_data['user_email']),
                'reward_type' => $reward->reward_type,
                'display_name' => $reward->display_name,
                'discount_type' => $reward->discount_type,
                'discount_value' => $reward->discount_value,
                'reward_currency' => get_woocommerce_currency(),
                'discount_code' => '',
                'discount_id' => 0,
                'require_point' => $reward->require_point,
                'status' => 'open',
                'start_at' => 0,
                'end_at' => 0,
                'conditions' => $reward->conditions,
                'condition_relationship' => $reward->condition_relationship,
                'usage_limits' => $reward->usage_limits,
                'icon' => $reward->icon,
                'action_type' => $action_type,
                'reward_id' => $reward->id,
                'campaign_id' => $campaign_id,
                'free_product' => $reward->free_product,
                'expire_after' => $reward->expire_after,
                'expire_period' => $reward->expire_period,
                'enable_expiry_email' => $reward->enable_expiry_email,
                'expire_email' => $reward->expire_email,
                'expire_email_period' => $reward->expire_email_period,
                'created_at' => $created_at,
                'modified_at' => 0
            );
            $user_reward_model = new UserRewards();
            $user_reward_id = $user_reward_model->insertRow($user_reward_data);
            if ($user_reward_id <= 0) return false;
            $customer_note = sprintf(__('%s %s earned via %s', 'wp-loyalty-rules'), $reward->display_name, $this->getRewardLabel(1), $this->getActionName($action_type));
            $log_data = array(
                'user_email' => sanitize_email($action_data['user_email']),
                'action_type' => $action_type,
                'reward_id' => $reward->id,
                'user_reward_id' => $user_reward_id,
                'campaign_id' => $campaign_id,
                'note' => $customer_note,
                'customer_note' => $customer_note,
                'order_id' => isset($action_data['order_id']) && !empty($action_data['order_id']) ? $action_data['order_id'] : 0,
                'product_id' => isset($action_data['product_id']) && !empty($action_data['product_id']) ? $action_data['product_id'] : 0,
                'admin_id' => isset($action_data['admin_user_id']) && !empty($action_data['admin_user_id']) ? $action_data['admin_user_id'] : 0,
                'created_at' => $created_at,
                'modified_at' => 0,
                'action_process_type' => 'earn_reward',
                'reward_display_name' => $reward->display_name,
                'referral_type' => isset($action_data['referral_type']) && !empty($action_data['referral_type']) ? $action_data['referral_type'] : '',
            );
            $this->add_note($log_data);
            $options = self::$woocommerce_helper->getOptions('wlr_settings');
            $allow_auto_generate_coupon = !(is_array($options) && isset($options['allow_auto_generate_coupon']) && $options['allow_auto_generate_coupon'] == 'no');
            if ($allow_auto_generate_coupon) {
                $user_reward_table = $user_reward_model->getByKey($user_reward_id);
                if (!empty($user_reward_table)) {
                    $reward_helper = new \Wlr\App\Helpers\Rewards();
                    if (isset($user_reward_table->discount_code) && empty($user_reward_table->discount_code)) {
                        $update_data = array(
                            'start_at' => $created_at,
                        );
                        $user_reward_table->start_at = $update_data['start_at'];
                        if ($user_reward_table->expire_after > 0) {
                            $expire_period = isset($user_reward_table->expire_period) && !empty($user_reward_table->expire_period) ? $user_reward_table->expire_period : 'day';
                            $update_data['end_at'] = strtotime(date("Y-m-d H:i:s", strtotime("+" . $user_reward_table->expire_after . " " . $expire_period)));
                            $user_reward_table->end_at = $update_data['end_at'];

                            if (isset($user_reward_table->expire_email) && $user_reward_table->expire_email > 0
                                && isset($user_reward_table->enable_expiry_email) && $user_reward_table->enable_expiry_email > 0) {
                                $expire_email_period = isset($user_reward_table->expire_email_period) && !empty($user_reward_table->expire_email_period) ? $user_reward_table->expire_email_period : 'day';
                                $update_data['expire_email_date'] = $user_reward_table->expire_email_date = strtotime(date("Y-m-d H:i:s", strtotime("+" . $user_reward_table->expire_email . " " . $expire_email_period)));
                            }
                        }
                        $update_where = array('id' => $user_reward_table->id);
                        $user_reward_model->updateRow($update_data, $update_where);
                    }
                    $reward_helper->createCartUserReward($user_reward_table, $log_data['user_email']);
                }
            }
        } catch (\Exception $e) {
            return false;
        }
        \WC_Emails::instance();
        $action_data['campaign_id'] = $campaign_id;
        do_action('wlr_after_add_extra_earn_reward', $action_data['user_email'], $reward, $action_type, $action_data);
        do_action('wlr_notify_after_add_extra_earn_reward', $action_data['user_email'], $reward, $action_type, $action_data);
        return true;
    }

    function get_unique_refer_code($ref_code = '', $recursive = false, $email = '')
    {
        $referral_settings = get_option('wlr_settings');
        $prefix = (isset($referral_settings['wlr_referral_prefix']) && !empty($referral_settings['wlr_referral_prefix'])) ? $referral_settings['wlr_referral_prefix'] : 'REF-';
        $ref_code = !empty($ref_code) ? $ref_code : $prefix . $this->get_random_code();
        if (!empty($ref_code)) {
            if ($recursive) {
                $ref_code = $prefix . $this->get_random_code();
            }
            $ref_code = sanitize_text_field($ref_code);
            $user = self::$user_model->getQueryData(array('refer_code' => array('operator' => '=', 'value' => $ref_code)), '*', array(), false);
            if (!empty($user)) {
                return $this->get_unique_refer_code($ref_code, true, $email);
            }
        }
        return apply_filters('wlr_generate_referral_code', $ref_code, $prefix, $email);
    }

    function get_random_code()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz';
        $ref_code_random = '';
        for ($i = 0; $i < 2; $i++) {
            $ref_code_random .= substr(str_shuffle($permitted_chars), 0, 3) . '-';
        }
        return strtoupper(trim($ref_code_random, '-'));
    }

    function getActionName($action_type)
    {
        $action_name = '';
        if (empty($action_type)) {
            return $action_name;
        }
        $action_types = self::$woocommerce_helper->getActionTypes();
        if (isset($action_types[$action_type])) {
            $action_name = $action_types[$action_type];
        }
        if (empty($action_name)) {
            $extra_action_types = $this->getExtraActionList();
            if (isset($extra_action_types[$action_type])) {
                $action_name = $extra_action_types[$action_type];
            }
        }
        return empty($action_name) ? __("-", 'wp-loyalty-rules') : $action_name;
    }

    function add_note($data)
    {
        return (new Logs())->saveLog($data);
    }

    function getAchievementName($achievement_key)
    {
        if (empty($achievement_key)) return '';
        $achievement_names = array(
            'level_update' => __('Level Update', 'wp-loyalty-rules'),
            'daily_login' => __('Daily Login', 'wp-loyalty-rules'),
            'custom_action' => __('Custom Action', 'wp-loyalty-rules'),
        );
        $achievement_names = apply_filters('wlr_achievement_names', $achievement_names, $achievement_key);
        return isset($achievement_names[$achievement_key]) && !empty($achievement_names[$achievement_key]) ? $achievement_names[$achievement_key] : '';
    }

    function addExtraPointAction($action_type, $point, $action_data, $trans_type = 'credit', $is_update_used_point = false, $force_update_earn_campaign = false, $update_earn_total_point = true)
    {
        self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Trans:' . $trans_type);
        if (!is_array($action_data) || $point < 0 || empty($action_data['user_email']) || empty($action_type) || !$this->isValidExtraAction($action_type)) {
            return false;
        }
        $action_data = apply_filters('wlr_before_extra_point_data', $action_data, $point, $action_type);
        $status = true;
        $point = apply_filters('wlr_before_add_earn_point', $point, $action_type, $action_data);
        $point = apply_filters('wlr_notify_before_add_earn_point', $point, $action_type, $action_data);
        $conditions = array(
            'user_email' => array(
                'operator' => '=',
                'value' => sanitize_email($action_data['user_email']),
            ),
        );
        $user = self::$user_model->getQueryData($conditions, '*', array(), false);
        $created_at = strtotime(date('Y-m-d H:i:s'));
        $id = 0;
        if (!empty($user) && $user->id > 0) {
            $id = $user->id;
            if ($trans_type == 'credit') {
                $user->points += $point;
                if ($update_earn_total_point) {
                    $user->earn_total_point = $user->earn_total_point + $point;
                }
                if ($is_update_used_point) {
                    $user->used_total_points -= $point;
                }
            } else {
                if ($user->points < $point) {
                    $point = $user->points;
                    $user->points = 0;
                } else {
                    $user->points -= $point;
                }

                if ($is_update_used_point) {
                    $user->used_total_points += $point;
                }
                if ($user->points <= 0) {
                    $user->points = 0;
                }
            }

            $birthday_date = isset($action_data['birthday_date']) && !empty($action_data['birthday_date']) ? $action_data['birthday_date'] : $user->birthday_date;
            $birth_date = empty($birthday_date) || $birthday_date == '0000-00-00' ? $user->birth_date : strtotime($birthday_date);
            $_data = array(
                'points' => (int)$user->points,
                'earn_total_point' => (int)$user->earn_total_point,
                'birth_date' => $birth_date,
                'birthday_date' => $birthday_date,
                'used_total_points' => (int)$user->used_total_points,
            );
        } else {
            if ($trans_type == 'debit') {
                $point = 0;
            }
            $ref_code = isset($action_data['referral_code']) && !empty($action_data['referral_code']) ? $action_data['referral_code'] : '';
            $uniqueReferCode = $this->get_unique_refer_code($ref_code, false, $action_data['user_email']);
            $_data = array(
                'user_email' => sanitize_email($action_data['user_email']),
                'refer_code' => $uniqueReferCode,
                'used_total_points' => 0,
                'points' => (int)$point,
                'earn_total_point' => (int)$point,
                'birth_date' => 0,
                'birthday_date' => null,
                'created_date' => $created_at,
            );
        }
        $ledger_data = array(
            'user_email' => $action_data['user_email'],
            'points' => (int)$point,
            'action_type' => $action_type,
            'action_process_type' => isset($action_data['action_process_type']) && !empty($action_data['action_process_type']) ? $action_data['action_process_type'] : $action_type,
            'note' => isset($action_data['note']) && !empty($action_data['note']) ? $action_data['note'] : '',
            'created_at' => $created_at,
        );
        self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Ledger data:' . json_encode($ledger_data));
        $ledger_status = $this->updatePointLedger($ledger_data, $trans_type);
        self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', User data:' . json_encode($_data));
        if ($ledger_status && self::$user_model->insertOrUpdate($_data, $id)) {
            $args = array(
                'user_email' => $action_data['user_email'],
                'action_type' => $action_type,
                'campaign_type' => 'point',
                'points' => (int)$point,
                'transaction_type' => $trans_type,
                'campaign_id' => (int)isset($action_data['campaign_id']) && !empty($action_data['campaign_id']) ? $action_data['campaign_id'] : 0,
                'created_at' => $created_at,
                'modified_at' => 0,
                'product_id' => (int)isset($action_data['product_id']) && !empty($action_data['product_id']) ? $action_data['product_id'] : 0,
                'order_id' => (int)isset($action_data['order_id']) && !empty($action_data['order_id']) ? $action_data['order_id'] : 0,
                'order_currency' => isset($action_data['order_currency']) && !empty($action_data['order_currency']) ? $action_data['order_currency'] : '',
                'order_total' => isset($action_data['order_total']) && !empty($action_data['order_total']) ? $action_data['order_total'] : '',
                'referral_type' => isset($action_data['referral_type']) && !empty($action_data['referral_type']) ? $action_data['referral_type'] : '',
                'display_name' => isset($action_data['reward_display_name']) && !empty($action_data['reward_display_name']) ? $action_data['reward_display_name'] : null,
                'reward_id' => (int)isset($action_data['reward_id']) && !empty($action_data['reward_id']) ? $action_data['reward_id'] : 0,
                'admin_user_id' => null,
                'log_data' => '{}',
                'customer_command' => isset($action_data['customer_command']) && !empty($action_data['customer_command']) ? $action_data['customer_command'] : '',
                'action_sub_type' => isset($action_data['action_sub_type']) && !empty($action_data['action_sub_type']) ? $action_data['action_sub_type'] : '',
                'action_sub_value' => isset($action_data['action_sub_value']) && !empty($action_data['action_sub_value']) ? $action_data['action_sub_value'] : '',
            );
            if (is_admin()) {
                $admin_user = wp_get_current_user();
                $args['admin_user_id'] = $admin_user->ID;
            }
            try {
                $earn_trans_id = 0;
                if ($point > 0 || $force_update_earn_campaign) {
                    self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Earn Trans data:' . json_encode($args));
                    $earn_trans_id = self::$earn_campaign_transaction_model->insertRow($args);
                    self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Earn Trans id:' . $earn_trans_id);
                    $earn_trans_id = apply_filters('wlr_after_add_extra_earn_point_transaction', $earn_trans_id, $args);
                    if ($earn_trans_id == 0) {
                        $status = false;
                    }
                }
                if ($status) {
                    $log_data = array(
                        'user_email' => sanitize_email($action_data['user_email']),
                        'action_type' => $action_type,
                        'earn_campaign_id' => (int)$earn_trans_id > 0 ? $earn_trans_id : 0,
                        'campaign_id' => $args['campaign_id'],
                        'note' => $ledger_data['note'],
                        'customer_note' => isset($action_data['customer_note']) && !empty($action_data['customer_note']) ? $action_data['customer_note'] : '',
                        'order_id' => $args['order_id'],
                        'product_id' => $args['product_id'],
                        'admin_id' => $args['admin_user_id'],
                        'created_at' => $created_at,
                        'modified_at' => 0,
                        'points' => (int)$point,
                        'action_process_type' => $ledger_data['action_process_type'],
                        'referral_type' => isset($action_data['referral_type']) && !empty($action_data['referral_type']) ? $action_data['referral_type'] : '',
                        'reward_id' => (int)isset($action_data['reward_id']) && !empty($action_data['reward_id']) ? $action_data['reward_id'] : 0,
                        'user_reward_id' => (int)isset($action_data['user_reward_id']) && !empty($action_data['user_reward_id']) ? $action_data['user_reward_id'] : 0,
                        'expire_email_date' => isset($action_data['expire_email_date']) && !empty($action_data['expire_email_date']) ? $action_data['expire_email_date'] : 0,
                        'expire_date' => isset($action_data['expire_date']) && !empty($action_data['expire_date']) ? $action_data['expire_date'] : 0,
                        'reward_display_name' => isset($action_data['reward_display_name']) && !empty($action_data['reward_display_name']) ? $action_data['reward_display_name'] : null,
                        'required_points' => (int)isset($action_data['required_points']) && !empty($action_data['required_points']) ? $action_data['required_points'] : 0,
                        'discount_code' => isset($action_data['discount_code']) && !empty($action_data['discount_code']) ? $action_data['discount_code'] : null,
                    );
                    self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Log data:' . json_encode($log_data));
                    $this->add_note($log_data);
                }
            } catch (Exception $e) {
                self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Trans/Log Exception:' . $e->getMessage());
                $status = false;
            }
        } else {
            self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', User save failed');
            $status = false;
        }
        self::$woocommerce_helper->_log('Extra Action :' . $action_type . ',Point:' . $point . ', Extra Action status:' . $status);
        if ($status) {
            \WC_Emails::instance();
            do_action('wlr_after_add_extra_earn_point', $action_data['user_email'], $point, $action_type, $action_data);
            do_action('wlr_notify_after_add_extra_earn_point', $action_data['user_email'], $point, $action_type, $action_data);
        }
        return $status;
    }

    function isValidPointLedgerExtraAction($action_type)
    {
        $action_types = apply_filters('wlr_extra_point_ledger_action_list', array('new_user_add', 'admin_change', 'import'));
        return !empty($action_type) && in_array($action_type, $action_types);
    }

    function updatePointLedger($data = array(), $point_action = 'credit', $is_update = true)
    {
        if (!is_array($data) || empty($data['user_email']) || ($data['points'] <= 0 && !$this->isValidPointLedgerExtraAction($data['action_type'])) || empty($data['action_type'])) {
            return false;
        }
        $conditions = array(
            'user_email' => array(
                'operator' => '=',
                'value' => sanitize_email($data['user_email']),
            ),
        );
        $point_ledger = new PointsLedger();
        $user_ledger = $point_ledger->getQueryData($conditions, '*', array(), false);
        $point_ledger_is_starting = false;
        if (empty($user_ledger)) {
            /*$user = self::$user_model->getQueryData($conditions, '*', array(), false);
            $credit_points = isset($user->points) && !empty($user->points) ? $user->points : 0;
            if ($this->isValidExtraAction($data['action_type']) && empty($credit_points)) {
                $credit_points = (isset($data['points']) && $data['points'] > 0 ? $data['points'] : 0);
            }*/
            $point_data = array(
                'user_email' => $data['user_email'],
                'credit_points' => (int)isset($data['points']) && $data['points'] > 0 ? $data['points'] : 0,
                'action_type' => 'starting_point',
                'debit_points' => 0,
                'action_process_type' => 'starting_point',
                'note' => __('Starting point of customer', 'wp-loyalty-rules'),
                'created_at' => strtotime(
                    date('Y-m-d H:i:s')
                ),
            );
            $point_ledger->insertRow($point_data);
            $point_ledger_is_starting = true;
        }
        if ($is_update && !$point_ledger_is_starting) {
            $point_data = array(
                'user_email' => $data['user_email'],
                'credit_points' => $point_action == 'credit' ? $data['points'] : 0,
                'action_type' => $data['action_type'],
                'debit_points' => $point_action == 'debit' ? $data['points'] : 0,
                'action_process_type' => isset($data['action_process_type']) && !empty($data['action_process_type']) ? $data['action_process_type'] : $data['action_type'],
                'note' => isset($data['note']) && !empty($data['note']) ? $data['note'] : '',
                'created_at' => strtotime(date('Y-m-d H:i:s')),
            );
            $point_ledger->insertRow($point_data);
        }
        return true;
    }

    function addCustomerToLoyalty($email, $action = 'signin')
    {
        $setting_option = self::$woocommerce_helper->getOptions('wlr_settings', '');
        $user_action_list = (isset($setting_option['user_action_list']) && !empty($setting_option['user_action_list'])) ? explode(',', $setting_option['user_action_list']) : array('signin');
        if (empty($email) || !in_array($action, $user_action_list)) {
            return;
        }
        $user = self::$user_model->getQueryData(
            array(
                'user_email' => array(
                    'operator' => '=',
                    'value' => $email,
                ),
            ),
            '*',
            array(),
            false
        );
        $point = 0;
        if (empty($user)) {
            $uniqueReferCode = $this->get_unique_refer_code('', false, $email);
            $_data = array(
                'user_email' => sanitize_email($email),
                'refer_code' => $uniqueReferCode,
                'used_total_points' => 0,
                'points' => $point,
                'earn_total_point' => $point,
                'birth_date' => null,
                'level_id' => 0,
                'created_date' => strtotime(date('Y-m-d H:i:s')),
            );
            self::$user_model->insertOrUpdate($_data);
        }
    }

    function isIncludingTax()
    {
        $setting_option = self::$woocommerce_helper->getOptions('wlr_settings', array());
        $tax_calculation_type = (isset($setting_option['tax_calculation_type']) && !empty($setting_option['tax_calculation_type'])) ? $setting_option['tax_calculation_type'] : 'inherit';
        $is_including_tax = false;
        if ($tax_calculation_type == 'inherit') {
            $is_including_tax = ('yes' === get_option('woocommerce_prices_include_tax'));
        } elseif ($tax_calculation_type === 'including') {
            $is_including_tax = true;
        }
        return $is_including_tax;
    }
}
