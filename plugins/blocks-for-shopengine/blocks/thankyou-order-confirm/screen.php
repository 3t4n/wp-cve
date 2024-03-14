<?php
/**
 * Checkout Order Receipt Template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/order-receipt.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.2.0
 *
 * wp-content/plugins/woocommerce/templates/checkout/order-receipt.php
 */

defined('ABSPATH') || exit;


$post_type = get_post_type();

if (!function_exists('get_the_order_id')) {
   function get_the_order_id() {

      global $wp;
   
      //todo- should we return the last order id for editor???
   
      return isset($wp->query_vars['order-received']) ? $wp->query_vars['order-received'] : 0;
   }
}

$order_id = get_the_order_id();

if($post_type ==  \ShopEngine\Core\Template_Cpt::TYPE && $order_id == 0) {
   // get a order to show in editor mode
   $orders = \ShopEngine\Widgets\Products::instance()->get_a_order_id();

   if(!empty($orders[0])) {
      $order_id = $orders[0]->get_id();
   } else {
      ?>
          <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
         <?php esc_html_e('No order found.', 'shopengine-gutenberg-addon'); ?>
          </div>
      <?php

      return;
   }
}


$order = wc_get_order($order_id);

if(!$order) {
	return;
}

$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();


if($show_customer_details) {

	$inf = $order->get_order_item_totals();

	?>
    <div class="shopengine shopengine-widget">
    <div class="shopengine-thankyou-order-confirm">

        <table class="table table-bordered">
            <thead>
            <tr>
                <th colspan="2"><?php echo esc_html__('Order Confirmation', 'shopengine-gutenberg-addon'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr class="order-number">
                <td><?php echo esc_html__('Order number', 'shopengine-gutenberg-addon'); ?></td>
                <td>
                    <a href="<?php echo esc_url($order->get_view_order_url()) ?>">#<?php echo esc_html($order->get_order_number()) ?></a>
                </td>
            </tr>
            <tr class="order-date">
                <td><?php echo esc_html__('Order Date', 'shopengine-gutenberg-addon'); ?></td>
                <td><?php echo esc_html(wc_format_datetime($order->get_date_created())); ?></td>
            </tr>
            <tr class="order-status">
                <td><?php echo esc_html__('Order status', 'shopengine-gutenberg-addon'); ?></td>
                <td><?php echo esc_html(wc_get_order_status_name($order->get_status())) ?></td>
            </tr>

			<?php if(!empty($inf['payment_method'])) : ?>
                <tr class="order-method">
                    <td><?php echo esc_html($inf['payment_method']['label']); ?></td>
                    <td><?php echo esc_html($inf['payment_method']['value']); ?></td>
                </tr> <?php
			endif;

			if(!empty($inf['order_total'])) : ?>
                <tr class="order-total">
                    <td><?php echo esc_html($inf['order_total']['label']); ?></td>
                    <td><?php echo wp_kses_post($inf['order_total']['value']); ?></td>
                </tr> <?php
			endif; ?>

            </tbody>
        </table>

    </div>
    <div class="shopengine shopengine-widget">
	<?php

	do_action('woocommerce_receipt_' . $order->get_payment_method(), $order->get_id());
}
