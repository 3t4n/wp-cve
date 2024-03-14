<?php

namespace ECFFW\App\Views\Admin;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

class Orders
{
    /**
     * Orders construct.
     */
    public function __construct() 
    {
        add_action('woocommerce_admin_order_data_after_order_details', array($this, 'order'), 10, 1);
        add_action('woocommerce_admin_order_data_after_billing_address', array($this, 'billing'), 10, 1);
        add_action('woocommerce_admin_order_data_after_shipping_address', array($this, 'shipping'), 10, 1);
    }

    /**
     * Show Extra Order Details.
     */
    public static function order($order) {
        echo self::getMetaDataHtml($order, 'Order: ', 'form-field form-field-wide');
    }

    /**
     * Show Extra Billing Details.
     */
    public static function billing($order) {
        echo self::getMetaDataHtml($order, 'Billing: ');
    }

    /**
     * Show Extra Shipping and Other Details.
     */
    public static function shipping($order)
    {
        echo self::getMetaDataHtml($order, 'Shipping: ');

        $custom = self::getMetaDataHtml($order, 'Custom: ');
        if ($custom) {
            echo '<h3>Other</h3>';
            echo $custom;
        }
    }

    /**
     * Get Order Meta Data Html.
     * 
     * @param object order
     * @param string prefix
     * @param string class
     * 
     * @return string html
     */
    public static function getMetaDataHtml($order, $prefix, $class = '') 
    {
        $html = '';
        foreach ($order->get_meta_data() as $meta) {
            if (strpos($meta->key, $prefix) !== false) {
                $label = str_replace($prefix, '', $meta->key);
                $value = $meta->value;
                if ($value != '') {
                    if (wp_http_validate_url($value)) {
                        $url = sanitize_url($value);
                        $text = strlen($url) >= 12 ? substr($url, -12) : $url;
                        $value = '<a href="' . $url . '" target="_blank">' . $text . '</a>';
                    } else {
                        $value = esc_html($value);
                    }
                    $html .= '<p class="' . esc_attr($class) . '"><strong>' . esc_html($label) . ':</strong> ' . $value . '</p>';
                }
            }
        }
        return $html;
    }
}
