<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Controllers\Site;

use Wlr\App\Controllers\Base;
use Wlr\App\Helpers\EarnCampaign;
use Wlr\App\Helpers\Order;
use Wlr\App\Helpers\Woocommerce;
use Wlr\App\Models\EarnCampaignTransactions;

defined('ABSPATH') or die;

class DisplayMessage extends Base
{

    function init()
    {
        if (self::$woocommerce->isBannedUser()) {
            return;
        }
        $this->triggerProductDisplayMessage();
        /* Cart earn point message*/
        add_shortcode('wlr_cart_earn_message', array($this, 'processCartEarnMessageShortCode'));
        if ($this->isCartEarnMessageEnabled()) {
            $this->triggerCartEarnMessage();
        }
        if ($this->isCheckoutEarnMessageEnabled()) {
            $this->triggerCheckoutEarnMessage();
        }
        $is_cart_message_fragment_needed = apply_filters('wlr_is_cart_message_fragment_needed', true);
        if ($is_cart_message_fragment_needed) {
            $this->triggerCartFragmentDisplayMessage();
        }
        /* Cart redeem message */
        add_shortcode('wlr_cart_redeem_message', array($this, 'processCartRedeemMessageShortCode'));
        if ($this->isCartRedeemMessageEnabled()) {
            $this->triggerCartRedeemMessage();
        }
        /* Checkout redeem message */
        if ($this->isCheckoutRedeemMessageEnabled()) {
            $this->triggerCheckoutRedeemMessage();
        }
        /*Thank you page message*/
        if ($this->isThankYouMessageEnabled()) {
            $this->triggerThankYouMessage();
        }
    }

    public function triggerProductDisplayMessage()
    {
        do_action('wlr_before_trigger_product_display_message');
        $position = $this->getProductDisplayMessageOption();
        switch ($position) {
            case 'before_price':
            case 'after_price':
                add_filter('woocommerce_get_price_html', array($this, 'renderProductMessage'), PHP_INT_MAX, 2);
                break;
            case 'before_add_to_cart':
                add_action('woocommerce_before_add_to_cart_form', array($this, 'renderProductMessageCart'), PHP_INT_MAX);
                add_filter('woocommerce_loop_add_to_cart_link', array($this, 'renderProductMessageCartLink'), PHP_INT_MAX, 2);
                break;
            case 'after_add_to_cart':
                add_action('woocommerce_after_add_to_cart_button', array($this, 'renderProductMessageCart'), PHP_INT_MAX);
                add_filter('woocommerce_loop_add_to_cart_link', array($this, 'renderProductMessageCartLink'), PHP_INT_MAX, 2);
                break;
            case 'before_title':
                add_action('woocommerce_before_shop_loop_item_title', array($this, 'renderProductMessageCart'), PHP_INT_MAX);
                add_action('woocommerce_single_product_summary', array($this, 'renderProductMessageCart'), 4);
                break;
            case 'after_title':
                add_action('woocommerce_after_shop_loop_item_title', array($this, 'renderProductMessageCart'), 9);
                add_action('woocommerce_single_product_summary', array($this, 'renderProductMessageCart'), 6);
                break;
        }

    }

    protected function getProductDisplayMessageOption()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $display_product_display_position = (isset($options['product_message_display_position']) && !empty($options['product_message_display_position']) ? $options['product_message_display_position'] : 'before_add_to_cart');
        return apply_filters('wlr_get_product_display_message_position', $display_product_display_position);
    }

    protected function isCartEarnMessageEnabled()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_earn_point_display = (isset($options['wlr_is_cart_earn_message_enable']) && !empty($options['wlr_is_cart_earn_message_enable']) ? $options['wlr_is_cart_earn_message_enable'] : 'yes') == 'yes';
        return apply_filters('wlr_is_cart_earn_message_enabled', $cart_earn_point_display);
    }

    function triggerCartEarnMessage()
    {
        do_action('wlr_before_trigger_cart_earn_message');
        $position = $this->getCartEarnMessageOption();

        switch ($position) {
            case 'before':
                add_action('woocommerce_before_cart', array($this, 'displayEarnPointsMessage'), 13);
                break;
            case 'after':
                add_action('woocommerce_after_cart_table', array($this, 'displayEarnPointsMessage'), 13);
                break;
            /*case 'content':
                add_action('woocommerce_cart_contents', array($this, 'displayEarnPointsMessage'), 555);
                break;
            case 'summary':
                add_action('woocommerce_cart_totals_before_order_total', array($this, 'checkoutEarnPoints'), 555);
                add_action('woocommerce_review_order_before_order_total', array($this, 'checkoutEarnPoints'));
                break;*/
            case 'hide':
            default:
                break;
        }

    }

    protected function getCartEarnMessageOption()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_earn_point_display = (isset($options['wlr_cart_earn_point_display']) && !empty($options['wlr_cart_earn_point_display']) ? $options['wlr_cart_earn_point_display'] : 'before');
        return apply_filters('wlr_get_cart_earn_message_position', $cart_earn_point_display);
    }

    protected function isCheckoutEarnMessageEnabled()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_earn_point_display = (isset($options['wlr_is_checkout_earn_message_enable']) && !empty($options['wlr_is_checkout_earn_message_enable']) ? $options['wlr_is_checkout_earn_message_enable'] : 'yes') == 'yes';
        return apply_filters('wlr_is_checkout_earn_message_enabled', $cart_earn_point_display);
    }

    function triggerCheckoutEarnMessage()
    {
        do_action('wlr_before_trigger_checkout_earn_message');
        add_action('woocommerce_before_checkout_form', array($this, 'displayEarnPointsMessage'), 5);
        do_action('wlr_after_trigger_checkout_earn_message');
    }

    function triggerCartFragmentDisplayMessage()
    {
        add_filter('woocommerce_add_to_cart_fragments', array($this, 'displayCartMessageFragment'));
        add_filter('woocommerce_update_order_review_fragments', array($this, 'displayCheckoutMessageFragment'));
    }

    protected function isCartRedeemMessageEnabled()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_redeem_point_display = (isset($options['wlr_is_cart_redeem_message_enable']) && !empty($options['wlr_is_cart_redeem_message_enable']) ? $options['wlr_is_cart_redeem_message_enable'] : 'yes') == 'yes';
        return apply_filters('wlr_is_cart_redeem_message_enabled', $cart_redeem_point_display);
    }

    function triggerCartRedeemMessage()
    {
        do_action('wlr_before_trigger_cart_redeem_message');

        $position = $this->getCartRedeemMessageOption();
        switch ($position) {
            case 'before':
                add_action('woocommerce_before_cart', array($this, 'displayRedeemPointsMessage'), 14);
                break;
            case 'after':
                add_action('woocommerce_after_cart_table', array($this, 'displayRedeemPointsMessage'), 14);
                break;
            /* case 'content':
                 add_action('woocommerce_cart_contents', array($this, 'displayRedeemPointsMessage'), 14);
                 break;*/
        }
    }

    protected function getCartRedeemMessageOption()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_redeem_point_display = (isset($options['wlr_cart_redeem_point_display']) && !empty($options['wlr_cart_redeem_point_display']) ? $options['wlr_cart_redeem_point_display'] : 'before');
        return apply_filters('wlr_get_cart_redeem_message_position', $cart_redeem_point_display);
    }

    protected function isCheckoutRedeemMessageEnabled()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_redeem_point_display = (isset($options['wlr_is_checkout_redeem_message_enable']) && !empty($options['wlr_is_checkout_redeem_message_enable']) ? $options['wlr_is_checkout_redeem_message_enable'] : 'yes') == 'yes';
        return apply_filters('wlr_is_checkout_redeem_message_enabled', $cart_redeem_point_display);
    }

    function triggerCheckoutRedeemMessage()
    {
        do_action('wlr_before_trigger_checkout_redeem_message');
        add_action('woocommerce_before_checkout_form', array($this, 'displayRedeemPointsMessage'), 6);
        do_action('wlr_after_trigger_checkout_redeem_message');
    }

    protected function isThankYouMessageEnabled()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $cart_redeem_point_display = (isset($options['wlr_is_thank_you_message_enable']) && !empty($options['wlr_is_thank_you_message_enable']) ? $options['wlr_is_thank_you_message_enable'] : 'yes') == 'yes';
        return apply_filters('wlr_thank_you_message_enabled', $cart_redeem_point_display);
    }

    function triggerThankYouMessage()
    {
        do_action('wlr_before_trigger_thankyou_message');
        $position = $this->getThankYouMessageOption();
        switch ($position) {
            case 'before':
                add_action('woocommerce_before_thankyou', array($this, 'renderThankYouMessage'));
                break;
            case 'after':
                add_action('woocommerce_thankyou', array($this, 'renderThankYouMessage'));
                break;
        }
    }

    protected function getThankYouMessageOption()
    {
        $options = self::$woocommerce->getOptions('wlr_settings');
        $thank_you_msg_position = (isset($options['wlr_thank_you_position']) && !empty($options['wlr_thank_you_position']) ? $options['wlr_thank_you_position'] : 'before');
        return apply_filters('wlr_get_thank_you_message_position', $thank_you_msg_position);
    }

    function renderProductMessage($price, $product)
    {
        if ($this->commonProductMessageCheck()) {
            return $price;
        }
        $low_stock = (bool)self::$input->post_get('low_in_stock');
        if (is_admin() || ($low_stock == true)) {
            return $price;
        }
        $point_setting = self::$woocommerce->getOptions('wlr_settings');
        $message = $this->commonProductMessage($product);
        $display_product_display_position = (isset($point_setting['product_message_display_position']) && !empty($point_setting['product_message_display_position']) ? $point_setting['product_message_display_position'] : 'before_add_to_cart');
        if ($display_product_display_position == 'before_price') {
            $message = $message . $price;
        } elseif ($display_product_display_position == 'after_price') {
            $message = $price . $message;
        } else {
            $message = $price;
        }
        return apply_filters('wlr_single_product_message', $message, $this);
    }

    protected function commonProductMessageCheck()
    {
        if ((isset($_REQUEST['app_name']) && isset($_REQUEST['scope']) && isset($_REQUEST['oauth_consumer_key']))
            || (isset($_REQUEST['consumer_key']) && isset($_REQUEST['consumer_secret']))) {
            return true;
        }
        return false;
    }

    function commonProductMessage($product)
    {
        if ($this->canStopCommonProductMessage() || !is_object($product) || self::$woocommerce->isBannedUser()) {
            return '';
        }

        $email = self::$woocommerce->get_login_user_email();
        $earn_campaign = new EarnCampaign();
        $cart_action_list = $earn_campaign->getProductActionList();
        $display = 'single';
        if (self::$woocommerce->isMethodExists($product, 'get_variation_prices')) {
            $prices = $product->get_variation_prices(true);
            $highest = array();
            $high_point = 0;
            if (!empty($prices) && is_array($prices)) {
                //$max_price = end($prices['price']);
                $last_variant_product_id = self::$woocommerce->arrayKeyLast($prices['price']);//array_key_last($prices['price']);
                $product = self::$woocommerce->getProduct($last_variant_product_id);
                if (is_object($product)) {
                    $extra = array('product' => $product, 'is_calculate_based' => 'product', 'user_email' => $email, 'is_message' => true);
                    $variant_rewards = $earn_campaign->getActionEarning($cart_action_list, $extra);
                    foreach ($variant_rewards as $variant_reward) {
                        foreach ($variant_reward as $variant_point_data) {
                            if (isset($variant_point_data['point']) && $variant_point_data['point'] > $high_point) {
                                $highest = $variant_rewards;
                                $high_point = $variant_point_data['point'];
                            }
                        }
                    }
                }
            }
            $reward_list = $highest;
            $display = 'variable';
        } else {
            $extra = array('product' => $product, 'is_calculate_based' => 'product', 'user_email' => $email, 'is_message' => true);
            $reward_list = $earn_campaign->getActionEarning($cart_action_list, $extra);
        }
        $message = '';
        foreach ($reward_list as $rewards) {
            foreach ($rewards as $reward) {
                if (isset($reward['messages']) && !empty($reward['messages'])) {
                    foreach ($reward['messages'] as $key => $single_message) {
                        if ($key == $display) {
                            $message .= $single_message;
                        }
                    }
                }
            }
        }
        return $message;
    }

    function canStopCommonProductMessage()
    {
        if ($this->shouldStopProcessing() || $this->isProductLowInStock()) {
            return true;
        }
        return false;
    }

    function shouldStopProcessing()
    {
        $status = ((isset($_REQUEST['app_name']) && isset($_REQUEST['scope']) && isset($_REQUEST['oauth_consumer_key']))
            || (isset($_REQUEST['consumer_key']) && isset($_REQUEST['consumer_secret'])));

        return apply_filters('wlr_should_stop_processing_messages', $status);

    }

    function isProductLowInStock()
    {
        $low_stock = (bool)self::$input->post_get('low_in_stock');
        if (is_admin() || ($low_stock == true)) {
            return true;
        }
        return false;
    }

    function renderProductMessageCart()
    {
        global $product;
        $message = $this->commonProductMessage($product);
        echo apply_filters('wlr_single_product_message', $message, $this);
    }

    function renderProductMessageCartLink($cart_link, $product)
    {
        if ($this->canStopCommonProductMessage()) {
            return $cart_link;
        }

        $point_setting = get_option('wlr_settings', '');
        $message = $this->commonProductMessage($product);
        $display_product_display_position = (isset($point_setting['product_message_display_position']) && !empty($point_setting['product_message_display_position']) ? $point_setting['product_message_display_position'] : 'before_add_to_cart');
        if ($display_product_display_position == 'before_add_to_cart') {
            $message = $message . $cart_link;
        } elseif ($display_product_display_position == 'after_add_to_cart') {
            $message = $cart_link . $message;
        } else {
            $message = $cart_link;
        }
        return apply_filters('wlr_single_product_message', $message, $this);
    }

    function processCartEarnMessageShortCode()
    {
        return $this->getCartMessage(true);
    }

    function getCartMessage($is_cart = true)
    {
        if (self::$woocommerce->isCartEmpty()) {
            return '';
        }
        $user_email = self::$woocommerce->get_login_user_email();
        $extra = array(
            'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'is_cart_message' => true
        );
        $earn_campaign = EarnCampaign::getInstance();
        $cart_action_list = $earn_campaign->getCartActionList();
        $reward_list = $earn_campaign->getActionEarning($cart_action_list, $extra);
        $point = $earn_campaign->addPointValue($reward_list);
        $available_rewards = $earn_campaign->concatRewards($reward_list);
        $reward_count = 0;
        if (!empty($available_rewards)) {
            $reward_count = count(explode(',', $available_rewards));
        }
        $point = $earn_campaign->roundPoints($point);
        $setting_option = get_option('wlr_settings', '');
        if (is_checkout() || !$is_cart) {
            $message = (isset($setting_option['wlr_checkout_earn_points_message']) && !empty($setting_option['wlr_checkout_earn_points_message'])) ? __($setting_option['wlr_checkout_earn_points_message'], 'wp-loyalty-rules') : __('Complete your order and earn {wlr_cart_points} {wlr_points_label} for a discount on a future purchase', 'wp-loyalty-rules');
        } else {
            $message = (isset($setting_option['wlr_cart_earn_points_message']) && !empty($setting_option['wlr_cart_earn_points_message'])) ? __($setting_option['wlr_cart_earn_points_message'], 'wp-loyalty-rules') : __('Complete your order and earn {wlr_cart_points} {wlr_points_label} for a discount on a future purchase', 'wp-loyalty-rules');
        }
        $short_code_list = array(
            '{wlr_points}' => $point > 0 ? self::$woocommerce->numberFormatI18n($point) : '',
            '{wlr_cart_point_or_reward}' => $earn_campaign->getPointOrRewardText($point, $available_rewards),
            '{wlr_cart_points}' => $point > 0 ? self::$woocommerce->numberFormatI18n($point) : '',
            '{wlr_points_label}' => $earn_campaign->getPointLabel($point),
            '{wlr_reward_label}' => $earn_campaign->getRewardLabel($reward_count),
            '{wlr_rewards}' => $available_rewards,
            '{wlr_cart_rewards}' => $available_rewards
        );
        $message = $earn_campaign->processShortCodes($short_code_list, $message);
        $message = apply_filters('wlr_points_rewards_earn_points_message', $message, $short_code_list);
        $message = Woocommerce::getCleanHtml($message);
        $message = $earn_campaign->getCartEarnMessageDesign($message);//$message = '<div class="wlr-message-info wlr_points_rewards_earn_points">' . $message . '</div>';
        if (empty($point) && empty($available_rewards)) {
            $message = '<div class="wlr-message-info wlr_points_rewards_earn_points" style="display: none;"></div>';
        }
        return $message;
    }

    function displayEarnPointsMessage()
    {
        $user_email = self::$woocommerce->get_login_user_email();
        $extra = array(
            'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'is_cart_message' => true
        );
        $earn_campaign = EarnCampaign::getInstance();
        $cart_action_list = $earn_campaign->getCartActionList();
        $reward_list = $earn_campaign->getActionEarning($cart_action_list, $extra);
        $point = $earn_campaign->addPointValue($reward_list);
        $available_rewards = $earn_campaign->concatRewards($reward_list);
        $reward_count = 0;
        if (!empty($available_rewards)) {
            $reward_count = count(explode(',', $available_rewards));
        }
        $point = $earn_campaign->roundPoints($point);
        $setting_option = get_option('wlr_settings', '');
        if (is_checkout()) {
            $message = (isset($setting_option['wlr_checkout_earn_points_message']) && !empty($setting_option['wlr_checkout_earn_points_message'])) ? __($setting_option['wlr_checkout_earn_points_message'], 'wp-loyalty-rules') : __('Complete your order and earn {wlr_cart_points} {wlr_points_label} for a discount on a future purchase', 'wp-loyalty-rules');
        } else {
            $message = (isset($setting_option['wlr_cart_earn_points_message']) && !empty($setting_option['wlr_cart_earn_points_message'])) ? __($setting_option['wlr_cart_earn_points_message'], 'wp-loyalty-rules') : __('Complete your order and earn {wlr_cart_points} {wlr_points_label} for a discount on a future purchase', 'wp-loyalty-rules');
        }

        $cart_earn_point_display = (isset($setting_option['wlr_cart_earn_point_display']) && !empty($setting_option['wlr_cart_earn_point_display']) ? $setting_option['wlr_cart_earn_point_display'] : 'before');
        $short_code_list = array(
            '{wlr_points}' => $point > 0 ? self::$woocommerce->numberFormatI18n($point) : '',
            '{wlr_cart_point_or_reward}' => $earn_campaign->getPointOrRewardText($point, $available_rewards),
            '{wlr_cart_points}' => $point > 0 ? self::$woocommerce->numberFormatI18n($point) : '',
            '{wlr_points_label}' => $earn_campaign->getPointLabel($point),
            '{wlr_reward_label}' => $earn_campaign->getRewardLabel($reward_count),
            '{wlr_rewards}' => $available_rewards,
            '{wlr_cart_rewards}' => $available_rewards,
        );
        $message = $earn_campaign->processShortCodes($short_code_list, $message);
        $message = apply_filters('wlr_points_rewards_earn_points_message', $message, $short_code_list);
        $message = Woocommerce::getCleanHtml($message);

        if (!wp_doing_ajax()) {
            if ($cart_earn_point_display == 'content' && !is_checkout()) {
                $message = '<tr><td colspan="6" class="wlr-message-info wlr_points_rewards_earn_points" >' . $message . '</td></tr>';
            } else {
                $message = $earn_campaign->getCartEarnMessageDesign($message);//'<div class="wlr-message-info wlr_points_rewards_earn_points">' . $message . '</div>';
            }
        }
        if (wp_doing_ajax()) {
            if (empty($point) && empty($available_rewards)) {
                $message = '';
            }
            wp_send_json_success($message);
        } else {

            if (empty($point) && empty($available_rewards)) {
                if ($cart_earn_point_display == 'content') {
                    $message = '<tr><td colspan="6" class="wlr-message-info wlr_points_rewards_earn_points">' . $message . '</td></tr>';
                } else {
                    $message = '<div class="wlr-message-info wlr_points_rewards_earn_points" style="display: none;"></div>';
                }
            }
            echo $message;
        }
    }

    /*function checkoutEarnPoints()
    {
        $user_email = self::$woocommerce->get_login_user_email();
        $extra = array(
            'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart'
        );
        $earn_campaign = EarnCampaign::getInstance();
        $cart_action_list = $earn_campaign->getCartActionList();
        $reward_list = $earn_campaign->getActionEarning($cart_action_list, $extra);
        $point = $earn_campaign->addPointValue($reward_list);
        $available_rewards = $earn_campaign->concateRewards($reward_list);
        $point = $earn_campaign->roundPoints($point);
        $point_label = $earn_campaign->getPointLabel($point);
        $setting_option = get_option('wlr_settings', '');
        $earn_point_summary_text = (isset($setting_option['wlr_earn_point_order_summary_text']) && !empty($setting_option['wlr_earn_point_order_summary_text']) ? $setting_option['wlr_earn_point_order_summary_text'] : 'Earn Points');
        if ($point > 0 || !empty($available_rewards)) {
            $text = '';
            if ($point > 0) {
                $text = $point . $point_label;
            }
            if (!empty($available_rewards)) {
                $text .= ' ' . __('And', 'wp-loyalty-rules') . ' ' . $available_rewards;
            }
            echo "<tr class=\"wlr-checkout-point\">
                    <th>" . __($earn_point_summary_text, 'wp-loyalty-rules') . "</th>
                    <td>" . $text . "</td>
                </tr>";
        }
    }*/

    function displayCheckoutMessageFragment($fragment)
    {
        if (self::$woocommerce->isCartEmpty()) {
            return $fragment;
        }
        if ($this->isCheckoutEarnMessageEnabled()) {
            $fragment['div.wlr-message-info.wlr_points_rewards_earn_points'] = $this->getCartMessage(false);
        }
        if ($this->isCheckoutRedeemMessageEnabled()) {
            $fragment['div.wlr-message-info.wlr_point_redeem_message'] = $this->getCartRedeemMessage(false);
        }
        return $fragment;
    }

    function getCartRedeemMessage($is_cart = true)
    {
        if (self::$woocommerce->isCartEmpty()) {
            return '';
        }
        $setting_option = get_option('wlr_settings', '');
        if (self::$woocommerce->isFullyDiscounted() || !wc_coupons_enabled()) {
            return '<div class="wlr-message-info wlr_point_redeem_message"></div>';
        }
        $user_email = self::$woocommerce->get_login_user_email();
        $message = '';
        if (!empty($user_email)) {
            $order_helper = Order::getInstance();
            //Get user points
            $points = $order_helper->getUserPoint($user_email);
            //Get all earned rewards
            $reward_helper = \Wlr\App\Helpers\Rewards::getInstance();
            $extra = array(
                'user_email' => $user_email, 'cart' => WC()->cart, 'is_calculate_based' => 'cart', 'allowed_condition' => array('user_role', 'customer', 'user_point', 'currency', 'language')
            );
            $user_reward = $reward_helper->getUserRewards($user_email, $extra);
            $point_rewards = $reward_helper->getPointRewards($user_email, $extra);
            $reward_list = array_merge($user_reward, $point_rewards);
            if (count($reward_list) > 0 || $points > 0) {
                if (is_checkout() || !$is_cart) {
                    $message = (isset($setting_option['wlr_checkout_redeem_points_message']) && !empty($setting_option['wlr_checkout_redeem_points_message'])) ? __($setting_option['wlr_checkout_redeem_points_message'], 'wp-loyalty-rules') : __('You have {wlr_redeem_cart_points} {wlr_points_label} earned choose your rewards {wlr_reward_link}', 'wp-loyalty-rules');
                } else {
                    $message = (isset($setting_option['wlr_cart_redeem_points_message']) && !empty($setting_option['wlr_cart_redeem_points_message'])) ? __($setting_option['wlr_cart_redeem_points_message'], 'wp-loyalty-rules') : __('You have {wlr_redeem_cart_points} {wlr_points_label} earned choose your rewards {wlr_reward_link}', 'wp-loyalty-rules');
                }
                $short_code_list = array(
                    '{wlr_points}' => $points > 0 ? self::$woocommerce->numberFormatI18n($points) : '',
                    '{wlr_redeem_cart_points}' => $points > 0 ? self::$woocommerce->numberFormatI18n($points) : '',
                    '{wlr_points_label}' => $order_helper->getPointLabel($points),
                    '{wlr_reward_label}' => $order_helper->getRewardLabel(count($reward_list)),
                    '{wlr_reward_link}' => '<a id="wlr-reward-link" href="javascript:void(0);">' . __('Click Here', 'wp-loyalty-rules') . '</a>'
                );
                $message = $order_helper->processShortCodes($short_code_list, $message);
                $message = apply_filters('wlr_point_redeem_points_message', $message);
                $cart_redeem_point_display = (isset($setting_option['wlr_cart_redeem_point_display']) && !empty($setting_option['wlr_cart_redeem_point_display']) ? $setting_option['wlr_cart_redeem_point_display'] : 'before');
                if ($cart_redeem_point_display == 'content' && !is_checkout()) {
                    $message = '<tr><td colspan="6" class="wlr-message-info wlr_point_redeem_message">' . $message . '</td></tr>';
                } else {
                    $message = $order_helper->getCartRedeemMessageDesign($message);//'<div class="wlr-message-info wlr_point_redeem_message">' . $message . '</div>';
                }
            }
        }
        return $message;
    }

    function displayCartMessageFragment($fragment)
    {
        if (self::$woocommerce->isCartEmpty()) {
            return $fragment;
        }
        $cart_earn_point_display = $this->getCartEarnMessageOption();
        if (!in_array($cart_earn_point_display, array('summary', 'content', 'hide'))) {
            $fragment['div.wlr-message-info.wlr_points_rewards_earn_points'] = $this->getCartMessage();
        }
        $cart_redeem_point_display = $this->getCartRedeemMessageOption();
        if (!in_array($cart_redeem_point_display, array('content', 'hide'))) {
            $fragment['div.wlr-message-info.wlr_point_redeem_message'] = $this->getCartRedeemMessage();
        }
        return $fragment;
    }

    function displayRedeemPointsMessage()
    {
        echo $this->getCartRedeemMessage();
    }

    function processCartRedeemMessageShortCode()
    {
        return $this->getCartRedeemMessage();
    }

    public function renderThankYouMessage($order_id)
    {
        $order = self::$woocommerce->getOrder($order_id);
        $order_email = self::$woocommerce->getOrderEmail($order);
        if (!empty($order_email) && !empty($order)) {
            $earn_campaign = EarnCampaign::getInstance();
            $point = $earn_campaign->getPointEarnedFromOrder($order_id, $order_email);
            $earn_campaign_trans = new EarnCampaignTransactions();
            $rewards = $earn_campaign_trans->getRewardEarnedFromOrder($order_id, $order_email);
            $setting_option = get_option('wlr_settings', '');
            $user_point = $earn_campaign->getUserPoint($order_email);
            $message = (isset($setting_option['wlr_thank_you_message']) && !empty($setting_option['wlr_thank_you_message'])) ? __($setting_option['wlr_thank_you_message'], 'wp-loyalty-rules') : __('You have earned {wlr_earned_points} {wlr_points_label} for this order. You have a total of {wlr_total_points}.', 'wp-loyalty-rules');
            if (!empty($message) && (!empty($point) || !empty($rewards))) {
                $message = \Wlr\App\Helpers\Woocommerce::getCleanHtml($message);
                $short_code_list = array(
                    '{wlr_points}' => $point > 0 ? self::$woocommerce->numberFormatI18n($point) : '',
                    '{wlr_earned_points}' => $point > 0 ? self::$woocommerce->numberFormatI18n($point) : '',
                    '{wlr_points_label}' => $earn_campaign->getPointLabel($point),
                    '{wlr_reward_label}' => $earn_campaign->getRewardLabel(count($rewards)),
                    '{wlr_rewards}' => implode(',', $rewards),
                    '{wlr_earned_rewards}' => implode(',', $rewards),
                    '{wlr_total_points}' => $user_point > 0 ? self::$woocommerce->numberFormatI18n($user_point) : '',
                    '{wlr_cart_point_or_reward}' => $earn_campaign->getPointOrRewardText($point, implode(',', $rewards)),
                );
                $message = $earn_campaign->processShortCodes($short_code_list, $message);
                $message = $earn_campaign->getThankfulPageDesign($message);
                echo apply_filters('wlr_thank_you_message', $message, $point, $user_point, $rewards);
            }
        }
    }
}