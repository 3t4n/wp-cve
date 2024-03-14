<?php
/**
 ***
 *** Review order table
 */

defined('ABSPATH') || exit;

use Shop_Ready\helpers\classes\Elementor_Helper as WReady_Helper;

$review_enable = WReady_Helper::get_global_setting('shop_ready_pro_order_review_enable', 'yes');
$order_review_show_price = WReady_Helper::get_global_setting('shop_ready_pro_order_review_enable_price', 'yes');
$order_review_qty_change = WReady_Helper::get_global_setting('shop_ready_pro_order_review_enable_qty', 'no');
$enable_thumbnail = WReady_Helper::get_global_setting('shop_ready_pro_order_review_enable_thumbnail', '');
$review_order_layout = WReady_Helper::get_global_setting('shop_ready_pro_order_review_order_layout', '');
$item_data = WReady_Helper::get_global_setting('shop_ready_pro_order_review_item_data_enable', 'yes');
$product_name_limit = WReady_Helper::get_global_setting('shop_ready_pro_order_review_product_name_limit', '5');
if ($review_enable != 'yes') {
    return;
}



?>



<table class="woo-ready-review-order shop_table sr-shop-review-table-wrapper woocommerce-checkout-review-order-table">
    <thead>
        <tr>
            <th class="product-name">
                <?php esc_html_e('Product', 'shopready-elementor-addon'); ?>
            </th>
            <th class="product-total">
                <?php esc_html_e('Subtotal', 'shopready-elementor-addon'); ?>
            </th>
        </tr>
    </thead>
    <tbody>
        <?php

        do_action('woocommerce_review_order_before_cart_contents');

        foreach (WC()->cart->get_cart() as $cart_item_key => $cart_item) {
            $_product = apply_filters('woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);

            if ($_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters('woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key)) {
                ?>
                <tr
                    class="<?php echo esc_attr(apply_filters('woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key)); ?>">
                    <?php if ($review_order_layout == 'style1'): ?>
                        <td class="product-name">

                            <?php do_action('shop_ready_order_review_before_product_title', $_product, $cart_item); ?>
                            <div class="product-title">
                                <?php echo apply_filters('woocommerce_cart_item_name', wp_trim_words($_product->get_name(), $product_name_limit, ''), $cart_item, $cart_item_key) . '&nbsp;'; ?>
                            </div>
                            <div class="shop-ready-product-item-qty-wrapper">
                                <?php echo apply_filters('woo_ready_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key, $order_review_qty_change, $order_review_show_price); ?>
                            </div>
                            <?php if ($enable_thumbnail == 'yes'): ?>
                                <img src="<?php echo esc_url(wp_get_attachment_image_url($_product->get_image_id(), 'thumbnail')); ?>"
                                    class="shop-ready-product-order-review-img" />
                            <?php endif; ?>
                            <?php if ($item_data == 'yes'): ?>
                                <?php echo wc_get_formatted_cart_item_data($cart_item); ?>
                            <?php endif; ?>
                        </td>
                    <?php else: ?>
                        <td class="product-name">

                            <?php if ($enable_thumbnail == 'yes'): ?>
                                <img src="<?php echo esc_url(wp_get_attachment_image_url($_product->get_image_id(), 'thumbnail')); ?>"
                                    class="shop-ready-product-order-review-img" />
                            <?php endif; ?>

                            <?php do_action('shop_ready_order_review_before_product_title', $_product, $cart_item); ?>

                            <div class="product-title">
                                <?php echo apply_filters('woocommerce_cart_item_name', wp_trim_words($_product->get_name(), $product_name_limit, ''), $cart_item, $cart_item_key) . '&nbsp;'; ?>
                            </div>

                            <?php echo apply_filters('woo_ready_checkout_cart_item_quantity', ' <strong class="product-quantity">' . sprintf('&times;&nbsp;%s', $cart_item['quantity']) . '</strong>', $cart_item, $cart_item_key, $order_review_qty_change, $order_review_show_price); ?>

                            <?php if ($item_data == 'yes'): ?>
                                <?php echo wp_kses_post(wc_get_formatted_cart_item_data($cart_item)); ?>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td class="product-total">
                        <?php echo apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, esc_html($cart_item['quantity'])), $cart_item, $cart_item_key); ?>
                    </td>
                </tr>
                <?php
            }
        }

        do_action('woocommerce_review_order_after_cart_contents');

        ?>

    </tbody>
    <tfoot>

        <tr class="cart-subtotal">
            <th>
                <?php esc_html_e('Subtotal', 'shopready-elementor-addon'); ?>
            </th>
            <td>
                <?php wc_cart_totals_subtotal_html(); ?>
            </td>
        </tr>

        <?php foreach (WC()->cart->get_coupons() as $code => $coupon): ?>
            <tr class="cart-discount coupon-<?php echo esc_attr(sanitize_title($code)); ?>">
                <th>
                    <?php wc_cart_totals_coupon_label($coupon); ?>
                </th>
                <td>
                    <?php wc_cart_totals_coupon_html($coupon); ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (WC()->cart->needs_shipping() && WC()->cart->show_shipping()): ?>

            <?php do_action('woocommerce_review_order_before_shipping'); ?>

            <?php wc_cart_totals_shipping_html(); ?>

            <?php do_action('woocommerce_review_order_after_shipping'); ?>

        <?php endif; ?>

        <?php foreach (WC()->cart->get_fees() as $fee): ?>
            <tr class="fee">
                <th>
                    <?php echo esc_html($fee->name); ?>
                </th>
                <td>
                    <?php wc_cart_totals_fee_html($fee); ?>
                </td>
            </tr>
        <?php endforeach; ?>

        <?php if (wc_tax_enabled() && !WC()->cart->display_prices_including_tax()): ?>
            <?php if ('itemized' === get_option('woocommerce_tax_total_display')): ?>
                <?php foreach (WC()->cart->get_tax_totals() as $code => $tax): // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited ?>
                    <tr class="tax-rate tax-rate-<?php echo esc_attr(sanitize_title($code)); ?>">
                        <th>
                            <?php echo esc_html($tax->label); ?>
                        </th>
                        <td>
                            <?php echo wp_kses_post($tax->formatted_amount); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr class="tax-total">
                    <th>
                        <?php echo esc_html(WC()->countries->tax_or_vat()); ?>
                    </th>
                    <td>
                        <?php wc_cart_totals_taxes_total_html(); ?>
                    </td>
                </tr>
            <?php endif; ?>
        <?php endif; ?>

        <?php do_action('woocommerce_review_order_before_order_total'); ?>

        <tr class="order-total">
            <th>
                <?php esc_html_e('Total', 'shopready-elementor-addon'); ?>
            </th>
            <td>
                <?php wc_cart_totals_order_total_html(); ?>
            </td>
        </tr>

        <?php do_action('woocommerce_review_order_after_order_total'); ?>

    </tfoot>
</table>