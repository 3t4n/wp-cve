<?php
/**
 * Thankyou page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/thankyou.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.7.0
 *
 * wp-content/plugins/woocommerce/templates/checkout/thankyou.php
 */

defined( 'ABSPATH' ) || exit;

$post_type = get_post_type();


if(!function_exists('get_the_order_id')){
   function get_the_order_id() {

      global $wp;
      //todo- should we return the last order id for editor???
      return isset($wp->query_vars['order-received']) ? $wp->query_vars['order-received'] : 0;
   }
}
$order_id = get_the_order_id();

if($post_type == \ShopEngine\Core\Template_Cpt::TYPE && $order_id == 0) {
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

if(!$order_id) {
	return;
}


?>
<div class="shopengine shopengine-widget">
<div class="shopengine-thankyou-thankyou">

	<?php if($order->has_status('failed')) : ?>

        <h3>
			<?php esc_html_e('ORDER', 'shopengine-gutenberg-addon') ?> #<?php echo esc_html($order->get_order_number()); ?>
        </h3>
        <p>
			<?php esc_html_e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'shopengine-gutenberg-addon'); ?>
        </p>

	<?php else : ?>

        <h3>
			<?php esc_html_e('ORDER', 'shopengine-gutenberg-addon') ?> #<?php echo esc_html($order->get_order_number()); ?>
        </h3>
        <p>
			<?php echo wp_kses_post(apply_filters('woocommerce_thankyou_order_received_text', esc_html__('Thank you. Your order has been received.', 'shopengine-gutenberg-addon'), $order)); ?>
        </p>

	<?php endif; ?>

</div>
</div>