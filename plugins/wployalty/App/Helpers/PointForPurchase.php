<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die();

class PointForPurchase extends Order
{
    public static $instance = null;

    public function __construct($config = array())
    {
        parent::__construct($config);
    }

    public static function getInstance(array $config = array())
    {
        if (!self::$instance) {
            self::$instance = new self($config);
        }
        return self::$instance;
    }

    function getTotalEarnPoint($point, $rule, $data)
    {
        if (isset($rule->earn_campaign->point_rule) && !empty($rule->earn_campaign->point_rule)) {
            $point_rule = $rule->earn_campaign->point_rule;
            if (self::$woocommerce_helper->isJson($rule->earn_campaign->point_rule)) {
                $point_rule = json_decode($rule->earn_campaign->point_rule);
            }
            $point = $this->checkPointForPurchaseData($rule, $data, $point_rule);
        }
        return $point;
    }

    protected function checkPointForPurchaseData($rule, $data, $point_rule)
    {
        $can_earn_point = $this->getPointForPurchaseEligiblePoint($rule, $data, $point_rule);
        if (isset($data['is_message']) && $data['is_message']) {
            return $can_earn_point;
        }
        $min_status = false;
        if (isset($point_rule->minimum_point) && ($point_rule->minimum_point <= $can_earn_point || $point_rule->minimum_point == 0)) {
            $min_status = true;
        }
        if ($min_status && isset($point_rule->maximum_point) && (float)$point_rule->maximum_point >= 0) {
            if ($point_rule->maximum_point > 0 && $point_rule->maximum_point < $can_earn_point) {
                $can_earn_point = $point_rule->maximum_point;
            }
        } else {
            $can_earn_point = 0;
        }
        return $can_earn_point;
    }

    protected function getPointForPurchaseEligiblePoint($rule, $data, $point_rule)
    {
        $point = 0;
        $eligible_total_order_price = $this->get_eligible_order_price($rule, $data);
        if ($eligible_total_order_price > 0) {
            if (isset($point_rule->wlr_point_earn_price) && !empty($point_rule->wlr_point_earn_price)
                && isset($point_rule->earn_point) && !empty($point_rule->earn_point)) {
                $point = $eligible_total_order_price * ($point_rule->earn_point / $point_rule->wlr_point_earn_price);
            }
        }
        return $this->roundPoints($point);
    }

    function getTotalEarnReward($reward, $rule, $data)
    {
        return array();
    }

    function processMessage($point_rule, $earning)
    {
        $messages = array(
            'single' => '',
            'variable' => '',
        );
        $category_page = (is_shop() || is_product_category());
        $category_page = apply_filters('wlr_is_product_category_page', $category_page);
        $product_page = is_product();
        $display_page = isset($point_rule->display_product_message_page) && !empty($point_rule->display_product_message_page) ? $point_rule->display_product_message_page : 'all';
        $msg_background_color = isset($point_rule->product_message_background) && !empty($point_rule->product_message_background) ? $point_rule->product_message_background : '';
        $msg_text_color = isset($point_rule->product_message_text_color) && !empty($point_rule->product_message_text_color) ? $point_rule->product_message_text_color : '';
        $msg_border_color = isset($point_rule->product_message_border_color) && !empty($point_rule->product_message_border_color) ? $point_rule->product_message_border_color : '';
        $is_rounded_edge = isset($point_rule->is_rounded_edge) && $point_rule->is_rounded_edge == 'yes';

        $point = isset($earning['point']) && !empty($earning['point']) ? (int)$earning['point'] : 0;
        $rewards = isset($earning['rewards']) && !empty($earning['rewards']) ? (array)$earning['rewards'] : array();
        if (empty($point) && empty($rewards)) {
            return $messages;
        }
        $msg_style = 'display: block;padding: 10px;line-height: 25px;';
        if ($is_rounded_edge) {
            $msg_style .= 'border-radius: 7px;';
        }
        if (!empty($msg_background_color)) {
            $msg_style .= 'background:' . $msg_background_color . ';';
        }
        if (!empty($msg_text_color)) {
            $msg_style .= 'color:' . $msg_text_color . ';';
        }
        if (!empty($msg_border_color)) {
            $msg_style .= 'border:1px solid;border-color:' . $msg_border_color . ';';
        }
        $single_product_message = isset($point_rule->single_product_message) && !empty($point_rule->single_product_message) ? __($point_rule->single_product_message, 'wp-loyalty-rules') : '';
        $variable_product_message = isset($point_rule->variable_product_message) && !empty($point_rule->variable_product_message) ? __($point_rule->variable_product_message, 'wp-loyalty-rules') : '';
        if ((in_array($display_page, array('all', 'single')) && $product_page) || (in_array($display_page, array('all', 'list')) && $category_page)) {
            if (!empty($single_product_message)) {
                $single_product_message = Woocommerce::getCleanHtml($single_product_message);
                $messages['single'] = '<span class="wlr-product-message" style="' . esc_attr($msg_style) . '">' . $single_product_message . '</span>';
            }
            if (!empty($variable_product_message)) {
                $variable_product_message = Woocommerce::getCleanHtml($variable_product_message);
                $messages['variable'] = '<span class="wlr-product-message" style="' . esc_attr($msg_style) . '">' . $variable_product_message . '</span>';
            }
        }
        $available_rewards = '';
        foreach ($rewards as $single_reward) {
            if (is_object($single_reward) && isset($single_reward->display_name)) {
                $available_rewards .= __($single_reward->display_name, 'wp-loyalty-rules') . ',';
            }
        }
        $available_rewards = trim($available_rewards, ',');
        $point = $this->roundPoints($point);
        $reward_count = 0;
        if (!empty($available_rewards)) {
            $reward_count = count(explode(',', $available_rewards));
        }
        $short_code_list = array(
            '{wlr_points}' => $point > 0 ? self::$woocommerce_helper->numberFormatI18n($point) : '',
            '{wlr_product_points}' => $point > 0 ? self::$woocommerce_helper->numberFormatI18n($point) : '',
            '{wlr_points_label}' => $this->getPointLabel($point),
            '{wlr_reward_label}' => $this->getRewardLabel($reward_count),
            '{wlr_rewards}' => $available_rewards
        );
        foreach ($messages as $key => $message) {
            if ($point > 0 || !empty($available_rewards)) {
                $messages[$key] = $this->processShortCodes($short_code_list, $message);
            } else {
                $messages[$key] = '';
            }
        }
        return $messages;
    }
}