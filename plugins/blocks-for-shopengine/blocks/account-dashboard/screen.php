<?php

defined('ABSPATH') || exit;

\ShopEngine\Widgets\Widget_Helper::instance()->wc_template_filter();

if(!is_user_logged_in()) {

   return esc_html__('You need first to be logged in', 'shopengine-gutenberg-addon');
}

?>
<div class="shopengine shopengine-widget">
	<div class="shopengine-account-dashboard">
		<?php
			if($block->is_editor) {
				woocommerce_account_content();

			} else {
            //please todo wp notice  issue solve
				// wc_print_notices();
            // woocommerce_account_content();

            if(WC()->session) {
               wc_print_notices();
               woocommerce_account_content();
            }
			}
		  ?>
	</div>
</div>