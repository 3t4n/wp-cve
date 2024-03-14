<?php
if (!defined('ABSPATH')) {
    exit;
}
/**
 * Thankyou Order details
 */


$order_items = $order->get_items(apply_filters('woocommerce_purchase_order_item_types', 'line_item'));
$show_purchase_note = $order->has_status(apply_filters('woocommerce_purchase_note_order_statuses', array('completed', 'processing')));
$order_again_url = shop_ready_gl_get_setting('woo_ready_enable_thankyou_order_again_button', 'yes') == 'yes' ? true : false;

?>

<section class="woocommerce-order-details woo-ready-order-details">

    <table class="woocommerce-table woocommerce-table--order-details shop_table order_details">

        <thead>
            <tr>
                <th class="woocommerce-table__product-name product-name">
                    <?php echo esc_html($settings['product_label']); ?>
                </th>
                <th class="woocommerce-table__product-table product-total">
                    <?php echo esc_html($settings['total_label']); ?>
                </th>
            </tr>
        </thead>

        <tbody>
            <?php
            do_action('woocommerce_order_details_before_order_table_items', $order);

            foreach ($order_items as $item_id => $item) {
                $product = $item->get_product();

                wc_get_template(
                    'order/order-details-item.php',
                    array(
                        'order' => $order,
                        'item_id' => $item_id,
                        'item' => $item,
                        'show_purchase_note' => $show_purchase_note,
                        'purchase_note' => $product ? $product->get_purchase_note() : '',
                        'product' => $product,
                    )
                );
            }

            do_action('woocommerce_order_details_after_order_table_items', $order);
            ?>
        </tbody>

        <tfoot>
            <?php

            foreach ($order->get_order_item_totals() as $key => $total) {
                ?>
                <tr>
                    <th scope="row">
                        <?php echo esc_html($total['label']); ?>
                    </th>
                    <td>
                        <?php echo 'payment_method' === $key ? wp_kses_post($total['value']) : wp_kses_post($total['value']); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                    </td>
                </tr>
                <?php
            }
            ?>

            <?php if ($order->get_customer_note()): ?>
                <tr>
                    <th>
                        <?php esc_html_e('Note:', 'shopready-elementor-addon'); ?>
                    </th>
                    <td>
                        <?php echo wp_kses_post(nl2br(wptexturize($order->get_customer_note()))); ?>
                    </td>
                </tr>
            <?php endif; ?>

        </tfoot>
    </table>

    <?php if ($settings['order_again'] == 'yes' && method_exists($order, 'get_id') && $order->has_status('completed')): ?>
        <?php $order_again = wp_nonce_url(add_query_arg('order_again', $order->get_id(), wc_get_cart_url()), 'woocommerce-order_again'); ?>
        <p class="order-again wready-btn">
            <a href="<?php echo esc_url($order_again_url); ?>" class="button wready-order-again">
                <?php esc_html_e('Order again', 'shopready-elementor-addon'); ?>
            </a>
        </p>
    <?php endif; ?>

</section>