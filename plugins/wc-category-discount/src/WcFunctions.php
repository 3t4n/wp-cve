<?php

namespace Wcd\DiscountRules;
if (!defined('ABSPATH')) exit;

use Wcd\DiscountRules\Main;

class WcFunctions
{
    /**
     * Get Item Price from Item Object
     * @param $item - product object
     * @return mixed - item price
     */
    function getItemPrice($item)
    {
        if (method_exists($item, 'get_price')) {
            $price = $item->get_price();
        } else {
            $price = $item->price;
        }
        return (float)$price;
    }

    /**
     * Save the order meta data
     * @param $item_id - item ID
     * @param $meta_key
     * @param $meta_value
     * @return bool
     * @throws \Exception
     */
    function addToOrderMeta($item_id, $meta_key, $meta_value)
    {
        if (function_exists('wc_add_order_item_meta'))
            wc_add_order_item_meta($item_id, $meta_key, $meta_value);
        return true;
    }

    /**
     * Get Order item quantity from item object
     * @param $item - product object
     * @return int - quantity of the item in order
     */
    function getOrderItemQuantity($item)
    {
        if (method_exists($item, 'get_quantity')) {
            return $item->get_quantity();
        } else {
            return 1;
        }
    }

    /**
     * Get Order item meta
     * @param $item_id - item id
     * @param null $key
     * @param bool $single
     * @return mixed
     * @throws \Exception
     */
    function getOrderItemMeta($item_id, $key = NULL, $single = true)
    {
        if (function_exists('wc_get_order_item_meta'))
            return wc_get_order_item_meta($item_id, $key, $single);
        else
            return false;
    }

    /**
     * @param $order_id - ID of the order
     * @return bool|\WC_Order - order object
     */
    function getOrder($order_id)
    {
        if (function_exists('wc_get_order'))
            return wc_get_order($order_id);
        else
            return false;
    }

    /**
     * Get Item Price suffix
     * @param $item - product object
     * @param $price - price suffix
     * @return string
     */
    function getPriceSuffix($item, $price)
    {
        if (method_exists($item, 'get_price_suffix'))
            return $item->get_price_suffix($price);
        else
            return '';
    }

    /**
     * Set Product Price
     * @param $item - product object
     * @param $price - new price to assign
     * @return bool
     */
    function setItemPrice($item, $price)
    {
        if (method_exists($item, 'set_price')) {
            return $item->set_price($price);
        } else {
            return $item->price = $price;
        }
    }


    /**
     * Extract Item Id form Item details
     * @param $item - product object
     * @return mixed - id
     */
    function getItemId($item)
    {
        if (method_exists($item, 'get_id')) {
            return $item->get_id();
        } else {
            $item_id = $item->id;
            if (isset($item->variation_id)) $item_id = $item->variation_id;
            return $item_id;
        }
    }

    /**
     * Extract variant id from product
     * @param $item - product object
     * @return int|string - variant id
     */
    function getVariantId($item)
    {
        if (method_exists($item, 'get_variation_id')) {
            return $item->get_variation_id();
        } else {
            return 0;
        }
    }

    /**
     * Get the product ID its different from Item ID
     * @param $item - product object
     * @return mixed - product_id
     */
    function getProductId($item)
    {
        if (method_exists($item, 'get_product_id')) {
            return $item->get_product_id();
        } else if (method_exists($item, 'get_id')) {
            return $item->get_id();
        } else {
            $item_id = $item->id;
            if (isset($item->variation_id)) $item_id = $item->variation_id;
            return $item_id;
        }
    }

    /**
     * Get Order Items from order object
     * @param $order - order Object
     * @return array - items in order
     */
    function getOrderItems($order)
    {
        if (method_exists($order, 'get_items')) {
            return $order->get_items();
        } else {
            return [];
        }
    }

    /**
     * Display the message | Notice to users
     * @param $message - message need to display
     * @param string $message_type - it may success, warning,error
     * @return bool
     */
    function displayMessage($message, $message_type = 'success')
    {
        if (function_exists('wc_print_notice')) {
            wc_print_notice($message, $message_type);
            return true;
        } else {
            return false;
        }
    }

    /**
     *
     * @param $item - product object
     * @return array - category ids
     */
    function getItemCategories($item)
    {
        if (method_exists($item, 'get_category_ids')) {
            return $item->get_category_ids();
        } else {
            return array();
        }
    }

    /**
     * Add admin js
     * @param $js_file - file path
     */
    function adminEnqueueJs($js_file)
    {
        if (function_exists('wp_enqueue_script')) {
            wp_enqueue_script('woocommerce_category_discount', $js_file);
        }
    }

    /**
     * Get parent Id for the variable products
     * @param $item
     * @return int
     */
    function getParentId($item)
    {
        if (method_exists($item, 'get_parent_id')) {
            return $item->get_parent_id();
        }
        return 0;
    }

    /**
     * get item from item id
     * @param $item_id
     * @return array|false|\WC_Product|null
     */
    function getItem($item_id)
    {
        if (function_exists('wc_get_product'))
            return wc_get_product($item_id);
        else
            return array();
    }

    /**
     * check that the item has children
     * @param $item
     * @return int
     */
    function isItemHasChildren($item)
    {
        if (method_exists($item, 'has_child'))
            return $item->has_child();
        else
            return 0;
    }

    /**
     * Get the children of the item
     * @param $item
     * @return array
     */
    function getItemChildren($item)
    {
        if (method_exists($item, 'get_children'))
            return $item->get_children();
        else
            return array();
    }

    /**
     * Make price
     * @param $price
     * @return array
     */
    function makePrice($price)
    {
        if (function_exists('wc_price'))
            return wc_price($price);
        else
            return $price;
    }

    /**
     * get Wc version
     * @param $version
     * @return bool|mixed.
     */
    public static function wcVersion($version)
    {
        if (defined('WC_VERSION') && WC_VERSION) {
            return version_compare(WC_VERSION, $version, '>=');
        } else if (defined('WOOCOMMERCE_VERSION') && WOOCOMMERCE_VERSION) {
            return version_compare(WOOCOMMERCE_VERSION, $version, '>=');
        } else {
            return false;
        }
    }


    /**
     * get product regular price
     * @param $product
     * @return mixed
     */
    public function getOriginalPrice($product)
    {
        if (method_exists($product, 'get_price')) {
            if ($product->get_price()) {
                return $product->get_price();
            }
            return array();
        }
    }

    /**
     * Get product price including tax
     *
     * @access public
     * @param object $product
     * @param int $quantity
     * @param float $price
     * @return int
     */
    public static function get_price_including_tax($product, $quantity = 1, $price = '')
    {
       return self::wcVersion('3.0') ? wc_get_price_including_tax($product, array('qty' => $quantity, 'price' => $price)) : $product->get_price_including_tax($quantity, $price);
    }

    /**
     * Get product price excluding tax
     *
     * @access public
     * @param object $product
     * @param int $quantity
     * @param float $price
     * @return float
     */
    public static function get_price_excluding_tax($product, $quantity = 1, $price = '')
    {
      return self::wcVersion('3.0') ? wc_get_price_excluding_tax($product, array('qty' => $quantity, 'price' => $price)) : $product->get_price_excluding_tax($quantity, $price);
    }
}
