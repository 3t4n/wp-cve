<?php defined('ABSPATH') || exit; ?>

<div class="shopengine shopengine-widget">
    <div class="shopengine-checkout-review-order">
        <?php do_action('woocommerce_checkout_before_order_review_heading'); ?>
        
        <h3 id="order_review_heading"><?php esc_html_e('Your order', 'shopengine-gutenberg-addon'); ?></h3>

        <div id="order_review" class="woocommerce-checkout-review-order">
			<?php

            do_action('woocommerce_checkout_before_order_review');

			global $wp;

			if(isset($wp->query_vars['order-pay'])) {

				WC_Shortcode_Checkout::output([]);

			} elseif($block->is_editor) {

                //some hardcoded html only for editor
                include __DIR__ . '/dummy-checkout-review.php';

			} else {

                //todo - No idea why in editor mode if the empty cart object is not checked gives fatal error even if the editor mode is TRUE!!!!!- AR
                if(!empty(WC()->cart)) {
	                woocommerce_order_review();
                }
            }

			?>
        </div>
		<?php do_action('woocommerce_checkout_after_order_review'); ?>
    </div>
</div>
