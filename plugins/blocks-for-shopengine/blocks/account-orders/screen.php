<?php 
defined('ABSPATH') || exit;

global $wp;

if(!is_user_logged_in()) {

   esc_html_e('You need first to be logged in', 'shopengine-gutenberg-addon');

   return;
}

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();


$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

?>

<div class="shopengine shopengine-widget">
   <div class="shopengine-account-orders">
      <?php woocommerce_account_orders($paged); ?>
   </div>

</div>



