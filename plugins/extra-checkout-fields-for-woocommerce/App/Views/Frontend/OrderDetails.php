<?php

namespace ECFFW\App\Views\Frontend;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use ECFFW\App\Controllers\Admin\Settings;
use ECFFW\App\Views\Admin\Orders;

class OrderDetails
{
    /**
     * Order Details construct.
     */
    public function __construct()
    {
        add_action('woocommerce_order_details_after_customer_details', array($this, 'details'));
    }

    /**
     * Display Order Details.
     */
    public static function details($order)
    {
        $heading = '';
        $settings = Settings::get();
        if ($settings && isset($settings['custom_fields_heading'])) {
            $heading = $settings['custom_fields_heading'];
        }

        $billing_html = Orders::getMetaDataHtml($order, 'Billing: ');
        $shipping_html = Orders::getMetaDataHtml($order, 'Shipping: ');
        $order_html = Orders::getMetaDataHtml($order, 'Order: ');
        $custom_html = Orders::getMetaDataHtml($order, 'Custom: ');
        ?>
            <section class="woocommerce-columns woocommerce-columns--2 woocommerce-columns--details col2-set addresses">
                <div class="woocommerce-column woocommerce-column--1 woocommerce-column--billing-details col-1">
                    <?php
                        if ($billing_html) {
                            echo '<h3>' . __("Billing details", 'extra-checkout-fields-for-woocommerce') . '</h3>';
                            echo $billing_html;
                        }
                        if ($order_html) {
                            echo '<h3>' . __("Additional information", 'extra-checkout-fields-for-woocommerce') . '</h3>';
                            echo $order_html;
                        }
                    ?>
                </div>
                <div class="woocommerce-column woocommerce-column--2 woocommerce-column--shipping-details col-2">
                    <?php
                        if ($shipping_html) {
                            echo '<h3>' . __("Shipping details", 'extra-checkout-fields-for-woocommerce') . '</h3>';
                            echo $shipping_html;
                        }
                        if ($custom_html) {
                            echo '<h3>' . esc_html__($heading, 'extra-checkout-fields-for-woocommerce') . '</h3>';
                            echo $custom_html;
                        }
                    ?>
                </div>
            </section>
        <?php
    }
}
