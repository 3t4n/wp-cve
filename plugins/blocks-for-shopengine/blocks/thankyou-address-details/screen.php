<?php

defined('ABSPATH') || exit;

$post_type = get_post_type();

if (! function_exists('get_the_order_id')) {
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


\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

$order = wc_get_order($order_id);

if(!$order) {
	return;
}

$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();


if($show_customer_details) {
	?>
   <div class="shopengine shopengine-widget">
    <div class="shopengine-thankyou-address-details">
		<?php
		wc_get_template('order/order-details-customer.php', ['order' => $order]);
		?>
    </div>
   </div>
	<?php
}
