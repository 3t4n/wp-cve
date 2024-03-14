<?php
/**
 * @author      Wployalty (Alagesan)
 * @license     http://www.gnu.org/licenses/gpl-2.0.html
 * @link        https://www.wployalty.net
 * */

namespace Wlr\App\Conditions;
defined('ABSPATH') or die();

class CartLineItemsCount extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->name = 'cart_line_items_count';
        $this->label = __('Line Item Count', 'wp-loyalty-rules');
        $this->group = __('Cart', 'wp-loyalty-rules');
    }

    public function isProductValid($options, $data)
    {
        return $this->check($options, $data);
    }

    function check($options, $data)
    {
        if (!is_object($options) || !isset($options->operator) || !isset($options->value)) {
            return false;
        }
        $operator = sanitize_text_field($options->operator);
        $value = $options->value;
        $is_calculate_base = $this->getCalculateBased($data);
        if (!$this->isValidCalculateBased($is_calculate_base)) {
            return false;
        }
        $item_count = 0;
        $logger = wc_get_logger();
        if ($is_calculate_base === 'cart' && isset($data[$is_calculate_base]) && !empty($data[$is_calculate_base]) && isset($options->sub_condition_type)) {
            $cart_items = self::$woocommerce_helper->getCartItems($data[$is_calculate_base]);
            $cart_items = apply_filters('wlr_before_cart_line_item_condition', $cart_items, $options, $data);
            switch ($options->sub_condition_type) {
                case "all_item_count":
                    $item_count = count($cart_items);
                    break;
                case "all_item_qty":
                    /*$cart = self::$woocommerce_helper->getCart($data[$is_calculate_base]);
                    if (!empty($cart)) {
                        $item_count = array_sum(wp_list_pluck($cart->get_cart(), 'quantity'));
                    }*/
                    foreach ($cart_items as $cart_item) {
                        $item_count += isset($cart_item['quantity']) && !empty($cart_item['quantity']) ? $cart_item['quantity'] : 0;
                    }
                    break;
                case "each_item_qty":
                    foreach ($cart_items as $cart_item) {
                        $item_count = $cart_count = isset($cart_item['quantity']) && !empty($cart_item['quantity']) ? $cart_item['quantity'] : 0;
                        if (!$this->doComparisionOperation($operator, $cart_count, $value)) {
                            return false;
                        }
                    }
                    break;
            }
        } elseif ($is_calculate_base === 'order' && isset($data[$is_calculate_base]) && !empty($data[$is_calculate_base]) && isset($options->sub_condition_type)) {
            $order_items = self::$woocommerce_helper->getOrderItems($data[$is_calculate_base]);
            $order_items = apply_filters('wlr_before_order_line_item_condition', $order_items, $options, $data);
            switch ($options->sub_condition_type) {
                case "all_item_count":
                    $item_count = count($order_items);
                    break;
                case "all_item_qty":
                    foreach ($order_items as $item) {
                        $item_count += $item->get_quantity();
                    }
                    /*$order = self::$woocommerce_helper->getOrder($data[$is_calculate_base]);
                    if (!empty($order)) {
                        $item_count = $order->get_item_count('line_item');
                    }*/
                    break;
                case 'each_item_qty':
                    $order_items = self::$woocommerce_helper->getOrderItems($data[$is_calculate_base]);
                    $logger->add('WPLoyalty', 'Common QTY:' . $value);
                    foreach ($order_items as $order_item) {
                        $order_item_qty = $order_item->get_quantity();
                        $logger->add('WPLoyalty', 'EACH QTY:' . $order_item_qty);
                        if (!$this->doComparisionOperation($operator, $order_item_qty, $value)) {
                            return false;
                        }
                    }
                    return true;
            }
        } elseif ($is_calculate_base === 'product') {
            return true;
        }
        return $this->doComparisionOperation($operator, $item_count, $value);
    }
}
