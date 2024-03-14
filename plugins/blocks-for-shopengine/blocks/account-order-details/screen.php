<?php

defined('ABSPATH') || exit;

global $wp;
if(!is_user_logged_in()) {
   ?>
      <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
      <?php esc_html_e('You need first to be logged in', 'shopengine-gutenberg-addon'); ?>
      </div>
    <?php

   return;
}

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

if (!empty($wp->query_vars['view-order'])) {
   $order_id = $wp->query_vars['view-order'];
} elseif (\ShopEngine\Core\Template_Cpt::TYPE) {
   $order_id = \ShopEngine\Widgets\Products::instance()->get_a_orders_from_my_account();
   if ($order_id == 0) {
?>
      <div class="shopengine shopengine-editor-alert shopengine-editor-alert-warning">
         <?php esc_html_e('No order found.', 'shopengine-gutenberg-addon'); ?>
      </div>
<?php
      return;
   }
}
?>
<div class="shopengine shopengine-widget">
   <div class="shopengine-account-order-details">
      <?php 
      if($block->is_editor){
         woocommerce_order_details_table($order_id);
      }else{
         do_action('woocommerce_view_order',$order_id);
      }
      
      
      ?>
   </div>
</div>
