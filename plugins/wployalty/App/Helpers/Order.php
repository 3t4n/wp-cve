<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Helpers;
defined('ABSPATH') or die;

class Order extends Base
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

    function get_eligible_order_price($rule, $action_data)
    {
        $price = 0;
        $cal_type = '';
        if (isset($action_data['is_calculate_based']) && !empty($action_data['is_calculate_based'])) {
            $cal_type = $action_data['is_calculate_based'];
        }
        $settings = self::$woocommerce_helper->getOptions('wlr_settings');
        $is_earn_point_after_discount = is_array($settings) && isset($settings['is_earn_point_after_discount']) && !empty($settings['is_earn_point_after_discount']) && in_array($settings['is_earn_point_after_discount'], array('yes', 'no')) ? $settings['is_earn_point_after_discount'] : 'no';
        $is_earn_point_after_discount = apply_filters('wlr_process_order_earn_after_discount', $is_earn_point_after_discount);
        if ($cal_type == 'cart' && isset($action_data[$cal_type]) && !empty($action_data[$cal_type])) {
            $cart_items = self::$woocommerce_helper->getCartItems($action_data[$cal_type]);
            if (empty($cart_items)) {
                return $price;
            }
            $price = $this->getCartEligiblePrice($price, $cart_items, $rule, $action_data, $is_earn_point_after_discount);
        } elseif ($cal_type == 'order' && isset($action_data[$cal_type]) && !empty($action_data[$cal_type])) {
            $order = self::$woocommerce_helper->getOrder($action_data[$cal_type]);
            $order_items = self::$woocommerce_helper->getOrderItems($action_data[$cal_type]);
            if (empty($order_items)) {
                return $price;
            }
            $price = $this->getOrderEligiblePrice($price, $order_items, $order, $rule, $action_data, $is_earn_point_after_discount);

        } elseif ($cal_type == 'product' && isset($action_data[$cal_type]) && !empty($action_data[$cal_type])) {
            $status = apply_filters('wlr_is_product_eligible_for_product', true, $action_data[$cal_type]);
            if (!$status) {
                return $price;
            }
            $product = self::$woocommerce_helper->getProduct($action_data[$cal_type]);
            if (!is_object($product)) {
                return $price;
            }
            if (!empty($rule) && is_object($rule)) {
                $action_data['current'] = $product;
                $allowed_condition = array();
                $allowed_condition = apply_filters('wlr_process_order_earn_allowed_condition', $allowed_condition);
                $action_data['allowed_condition'] = $allowed_condition;
                $status = $rule->processCampaignCondition($action_data, true);
                $item_price = self::$woocommerce_helper->getProductPrice($product, null, false, '');
                $item_price = apply_filters('wlr_is_product_page_price', $item_price, $product);
                $item_line_quantity = 1;
                if ($item_price > 0) {
                    $price += ($item_price * $item_line_quantity);
                }
                if (!$status) {
                    return $price;
                }
            }
        }
        return $price;
    }

    protected function getCartEligiblePrice($price, $cart_items, $rule, $action_data, $is_earn_point_after_discount)
    {
        if (empty($cart_items)) {
            return $price;
        }
        $allowed_condition = apply_filters('wlr_process_order_earn_allowed_condition', array());
        $action_data['allowed_condition'] = $allowed_condition;
        $action_data['is_calculate_based'] = 'cart';
        foreach ($cart_items as $cart_key => $cart_item) {
            if (isset($cart_item['loyalty_free_product']) && $cart_item['loyalty_free_product'] == 'yes') continue;
            $status = apply_filters('wlr_is_product_eligible_for_cart', true, $cart_item);
            if (!$status) {
                continue;
            }
            $product = self::$woocommerce_helper->getProduct($cart_item['data']);
            if (!is_object($product)) {
                continue;
            }
            if (!empty($rule) && is_object($rule)) {
                $action_data['current'] = $cart_item;
                $status = $rule->processCampaignCondition($action_data, true);
                if (!$status) {
                    continue;
                }
            }
            /* Earn point calculation after Discount Price */
            $product_price = self::$woocommerce_helper->getProductPrice($product);
            $product_price = apply_filters('wlr_cart_product_price', $product_price, $cart_key, $cart_item, $action_data);
            $cart_line_subtotal = isset($cart_item['line_subtotal']) && $cart_item['line_subtotal'] > 0 ? $cart_item['line_subtotal'] : 0;
            $cart_line_total = isset($cart_item['line_total']) && $cart_item['line_total'] > 0 ? $cart_item['line_total'] : 0;
            $cart_line_qty = isset($cart_item['quantity']) && $cart_item['quantity'] > 0 ? $cart_item['quantity'] : 1;
            /* Bundle product price adjustment */
            if ((isset($cart_item['data']) && is_object($cart_item['data']) && self::$woocommerce_helper->isMethodExists($cart_item['data'], 'get_type') && $cart_item['data']->get_type() == "bundle") || (isset($cart_item['bundled_by']) && !empty($cart_item['bundled_by']))) {
                $calculate_price = $cart_line_subtotal;
                if ($this->isIncludingTax()) {
                    $calculate_price = $cart_line_subtotal + $cart_item['line_subtotal_tax'];
                }
                $product_price = $calculate_price / $cart_line_qty;

            }
            /* End Bundle product price adjustment */
            if ($cart_line_subtotal != $cart_line_total && $is_earn_point_after_discount == 'yes') {
                $discount_price = ($cart_line_subtotal - $cart_line_total) / $cart_line_qty;
                if ($this->isIncludingTax()) {
                    $cart_line_tax = isset($cart_item['line_tax']) && $cart_item['line_tax'] > 0 ? $cart_item['line_tax'] : 0;
                    $cart_subtotal_tax = isset($cart_item['line_subtotal_tax']) && $cart_item['line_subtotal_tax'] > 0 ? $cart_item['line_subtotal_tax'] : 0;
                    $discount_price = (($cart_subtotal_tax + $cart_line_subtotal) - ($cart_line_tax + $cart_line_total)) / $cart_line_qty;
                }
                $discount_price = apply_filters('wlr_product_price', $discount_price, null, null, false, '');
                $product_price = $product_price - $discount_price;
            }
            if ($product_price > 0) {
                $price += ($product_price * $cart_line_qty);
            }
        }
        return $price;
    }

    protected function getOrderEligiblePrice($price, $order_items, $order, $rule, $action_data, $is_earn_point_after_discount)
    {
        if (empty($order_items) || !is_object($order)) {
            return $price;
        }
        $allowed_condition = apply_filters('wlr_process_order_earn_allowed_condition', array());
        $action_data['allowed_condition'] = $allowed_condition;
        $action_data['is_calculate_based'] = 'order';
        foreach ($order_items as $order_item) {
            $product = version_compare(WC_VERSION, '4.4.0', '<')
                ? $order->get_product_from_item($order_item)
                : $order_item->get_product();
            if (!is_object($product)) {
                continue;
            }
            $status = apply_filters('wlr_is_product_eligible_for_order', true, $order_item, $order);
            if (!$status) {
                continue;
            }
            if (!empty($rule) && is_object($rule)) {
                $action_data['current'] = $order_item;
                $status = $rule->processCampaignCondition($action_data, true);
                if (!$status) {
                    continue;
                }
            }
            $item_price = self::$woocommerce_helper->getProductPrice($product, $order_item, false, $order->get_currency());
            /* Earn point calculation after Discount Price */
            $item_line_subtotal = $order_item->get_subtotal();
            $item_line_total = $order_item->get_total();
            $item_line_quantity = $order_item->get_quantity();

            /* Bundle product price adjustment */
            $is_bundle_product = false;
            $itemMetaData = $order_item->get_meta_data();
            foreach ($itemMetaData as $singleMeta) {
                $singleMetaData = $singleMeta->get_data();
                if (isset($singleMetaData['key']) && $singleMetaData['key'] == '_bundle_cart_key') {
                    $is_bundle_product = true;
                    break;
                }
            }
            if ((self::$woocommerce_helper->isMethodExists($product, 'get_type') && $product->get_type() == 'bundle') || ($is_bundle_product)) {
                $calculate_price = $item_line_subtotal;
                if ($this->isIncludingTax()) {
                    $calculate_price = $item_line_subtotal + $order_item->get_subtotal_tax();
                }
                $item_price = $calculate_price / $item_line_quantity;
            }
            /* End Bundle product price adjustment */
            if ($item_line_subtotal != $item_line_total && $is_earn_point_after_discount == 'yes') {
                $line_item_discount_price = ($item_line_subtotal - $item_line_total) / $item_line_quantity;
                if ($this->isIncludingTax()) {
                    $line_item_line_tax = $order_item->get_total_tax();
                    $line_item_subtotal_tax = $order_item->get_subtotal_tax();
                    $line_item_discount_price = (($line_item_subtotal_tax + $item_line_subtotal) - ($line_item_line_tax + $item_line_total)) / $item_line_quantity;
                }
                $line_item_discount_price = apply_filters('wlr_product_price', $line_item_discount_price, null, $order_item, false, $order->get_currency());
                $item_price = $item_price - $line_item_discount_price;
            }
            if ($item_price > 0) {
                $price += ($item_price * $item_line_quantity);
            }
        }
        return $price;
    }

}